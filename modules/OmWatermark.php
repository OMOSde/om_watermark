<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!'); 

/**
 * Contao module OM-Watermark
 * 
 * Copyright (C) 2013 OMOS.de
 * 
 * @author  René Fehrmann <rene.fehrmann@omos.de>
 * @package OM-Watermark
 * @link    http://www.omos.de
 * @license LGPL
 */
 

/**
 * Constant
 */
define("OM_WATERMARK_TYPE_BACKEND", 0);
define("OM_WATERMARK_TYPE_FRONTEND", 1);
 
 
/**
 * Class OmWatermark
 */
class OmWatermark extends \Contao\System {
  
  /**
   * Hook omProcessFormData
   */
  public function omProcessFormData($arrPost, $arrForm, $arrFiles) {
    
    // check file array
    if (count($arrFiles) > 0)
    {
      // put files in array
      foreach ($arrFiles as $file)
      {
        $arrFormFiles[] = $file['tmp_name'];
      }
      
      // execute watermarking
      $this->omGenerate($arrFormFiles, OM_WATERMARK_TYPE_FRONTEND);      
    }
  }


  /**
   * Hook omProcessEfgFormData
   */
  public function omProcessEfgFormData($arrSubmitted, $arrFiles, $intOldId, &$arrForm) {    
    // check file array
    if (count($arrFiles) > 0)
    {
      // put files in array
      foreach ($arrFiles as $file)
      {
        $arrFormFiles[] = $file['tmp_name'];
      }
      
      // execute watermarking
      $this->omGenerate($arrFormFiles, OM_WATERMARK_TYPE_FRONTEND);
    }
  }
  
  
  /**
   * Hook omPostUpload
   */
  public function omPostUpload($arrFiles) {
    // check file array
    if (count($arrFiles) > 0)
    {    
      $this->omGenerate($arrFiles, OM_WATERMARK_TYPE_BACKEND);
    }
  }
  
  
  /**
   * 
   */
  public function omGenerate($arrFiles = array(), $intType = OM_WATERMARK_TYPE_BACKEND)
  {
    $arrWatermarks = \OmWatermarksModel::findBy('published', 1);

    // check object
    if (!is_object($arrWatermarks))
    {
      return;
    }

    // get through all active watermarks
    while ($arrWatermarks->next())
    {
      $objDirectories = \FilesModel::findMultipleByIds(deserialize($arrWatermarks->directories));
      $objWatermark   = \FilesModel::findById($arrWatermarks->watermark);
      
      // walk through all uploaded files
      foreach ($arrFiles as $file)
      {
        // check extension
        $strPath = str_replace(strrchr($file, '/'), '', $file);
        
        $strExtension = strtolower(strrchr($file, '.'));
        $arrExtensions = array('.jpg', '.gif', '.png');
        
        // create array with directories
        while ($objDirectories->next())
        {
          $arrDirectories[] = $objDirectories->row();
        }
       
        // if file is an image
        if (in_array($strExtension, $arrExtensions)) {
          
          // reset directories 
          reset($arrDirectories);
                    
          // walk through all allowed directories
          foreach ($arrDirectories as $directory) {
            
            // handle type
            $strRoot = ($intType == OM_WATERMARK_TYPE_BACKEND) ? $this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/' : '';
            
            // directory allowed?
            if (strpos($strPath, $directory['path']) !== false) {
              
              // get image sizes
              $arrImageSize = getimagesize($strRoot . $file);
              $intWidth     = $arrImageSize[0];
              $intHeight    = $arrImageSize[1];
                            
              // load image depend on extension
              switch ($strExtension) {
                case '.jpg':
                  $resOrigPicture = imagecreatefromjpeg($strRoot . $file);
                  break;
                case '.gif':
                  $resOrigPicture = imagecreatefromgif($strRoot . $file);
                  break;
                case '.png':
                  $resOrigPicture = imagecreatefrompng($strRoot . $file);
                  break;
              }              
              
              // create new image
              $resNewPicture = imagecreatetruecolor($intWidth, $intHeight);
               
              // copy original image
              imagecopyresampled($resNewPicture, $resOrigPicture, 0, 0, 0, 0, $intWidth, $intHeight, $intWidth, $intHeight);
               
              // load file with watermark depend on extension
              switch ($objWatermark->extension) {
                case 'jpg':
                  $resWatermark = imagecreatefromjpeg($this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/' . $objWatermark->path);
                  break;
                case 'gif':
                  $resWatermark = imagecreatefromgif($this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/' . $objWatermark->path);
                  break;
                case 'png':
                  $resWatermark = imagecreatefrompng($this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/' . $objWatermark->path);
                  break;
                default:
                  break;
              }
              
              // get watermark values
              $intWatermarkWidth  = imagesx($resWatermark);
              $intWatermarkHeight = imagesy($resWatermark);
              $arrMargin          = deserialize($arrWatermarks->margins);
                            
              // calculate position
              switch ($arrWatermarks->position) {
                case 'top-left':
                  $intWatermarkPosX   = intval($arrMargin['left']);
                  $intWatermarkPosY   = intval($arrMargin['top']);
                  break;
                case 'top-center':
                  $intWatermarkPosX   = $intWidth / 2 - ($intWatermarkWidth / 2);
                  $intWatermarkPosY   = intval($arrMargin['top']);
                  break;
                case 'top-right':
                  $intWatermarkPosX   = $intWidth - $intWatermarkWidth - intval($arrMargin['right']);
                  $intWatermarkPosY   = intval($arrMargin['top']);
                  break;
                case 'center-left':
                  $intWatermarkPosX   = intval($arrMargin['left']);
                  $intWatermarkPosY   = $intHeight / 2 - ($intWatermarkHeight / 2);
                  break;
                case 'center-right':
                  $intWatermarkPosX   = $intWidth - $intWatermarkWidth - intval($arrMargin['right']);
                  $intWatermarkPosY   = $intHeight / 2 - ($intWatermarkHeight / 2);
                  break;
                case 'bottom-left':
                  $intWatermarkPosX   = intval($arrMargin['left']);
                  $intWatermarkPosY   = $intHeight - $intWatermarkHeight - intval($arrMargin['bottom']);
                  break;
                case 'bottom-center':
                  $intWatermarkPosX   = $intWidth / 2 - ($intWatermarkWidth / 2);
                  $intWatermarkPosY   = $intHeight - $intWatermarkHeight - intval($arrMargin['bottom']);
                  break;
                case 'bottom-right':
                  $intWatermarkPosX   = $intWidth - $intWatermarkWidth - intval($arrMargin['right']);
                  $intWatermarkPosY   = $intHeight - $intWatermarkHeight - intval($arrMargin['bottom']);
                  break;
                default:
                  $intWatermarkPosX   = $intWidth / 2 - ($intWatermarkWidth / 2);
                  $intWatermarkPosY   = $intHeight / 2 - ($intWatermarkHeight / 2);
                  break;
              }

              // merge original image with watermark
              imagecopy($resNewPicture, $resWatermark, $intWatermarkPosX, $intWatermarkPosY, 0, 0, $intWatermarkWidth, $intWatermarkHeight);
                            
              // create new filename
              $arrPathinfo = pathinfo($file);
              $newFile = $arrPathinfo['dirname'] . '/' . $arrPathinfo['filename'] . '.jpg';
              
              // delete original uploaded file
              unlink($strRoot . $file);

              // check new file exists
              if (!file_exists($strRoot . $newFile))
              {
                // save new image
                imagejpeg($resNewPicture, $strRoot . $newFile, $arrWatermarks->quality);
                
                // update hash in tl_files
                $objFile = \FilesModel::findByPath($file);
                if ($objFile)
                {
                  // get path info of new file
                  $arrPathInfo = pathinfo($strRoot . $newFile);
                  
                  // update image row in table tl_files
                  $objFile->tstamp    = time();
                  $objFile->path      = $newFile;
                  $objFile->extension = $arrPathInfo['extension'];
                  $objFile->hash      = md5_file($strRoot . $newFile);
                  $objFile->name      = $arrPathInfo['basename'];
                  $objFile->save();                  
                }                
                
                // write log?
                if ($arrWatermarks->log) {
                  
                  // log success
                  $this->log($GLOBALS['TL_LANG']['om_watermark']['created'] . ': ' . $newFile, 'OmWatermarker - omPostUpload()', TL_FILES);
                }
              } else {
                
                // overwrite existing file?
                if ($arrWatermarks->overwrite)
                {
                  // save new image
                  imagejpeg($resNewPicture, $this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/' . $newFile, $arrWatermarks->quality);                
                  
                  // write log?
                  if ($arrWatermarks->log) {
                    
                    // log success
                    $this->log($GLOBALS['TL_LANG']['om_watermark']['created'] . ': ' . $newFile, 'OmWatermarker - omPostUpload()', TL_FILES);
                  }
                } else {
                  // write log?
                  if ($arrWatermarks->log) {
                    
                    // log failure
                    $this->log($GLOBALS['TL_LANG']['om_watermark']['exists'] . ': ' . $newFile, 'OmWatermarker - omPostUpload()', TL_ERROR);
                  }
                }                
              }               
            }
          }
        }        
      }
    }    
  }
}
