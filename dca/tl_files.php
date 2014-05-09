<?php 

// Edit
$GLOBALS['TL_DCA']['tl_files']['edit']['buttons_callback'] = array(array('tl_om_watermarks_files', 'addWatermarkButton'));


/**
 * 
 */
class tl_om_watermarks_files extends \Contao\Backend 
{
  /**
   * Automatically generate the folder URL aliases
   * @return string
   */
  public function addWatermarkButton($arrButtons)
  {
    if (Input::post('FORM_SUBMIT') == 'tl_files' && isset($_POST['watermark']))
    {
      $session = $this->Session->getData();
      $files  = $session['CURRENT']['IDS'];
      
      $strRoot = $this->Environment->documentRoot . $GLOBALS['TL_CONFIG']['websitePath'] . '/';
      
      // create file array
      foreach ($files as $file)
      {
        // check for directory
        if (is_dir($strRoot . $file))
        {
          // open directory0
          if ($handle = opendir($strRoot . $file))
          {
            // walk through all files in directory
            while (false !== ($dirFile = readdir($handle)))
            {
              if ($dirFile != '.' && $dirFile != '..')
              {
                $arrFiles[] = $file . '/' . $dirFile;
              }              
            }
        
            closedir($handle);
          }          
        } else {
          // not a directory
          $arrFiles[] = $file;
        }
      }
            
      // create images with watermark
      $this->import('OmWatermark');
      $this->OmWatermark->omPostUpload($arrFiles);

      $this->redirect($this->getReferer());
    }
   
    if (\Input::get('act') == 'edit')
    {
      $arrButtons['watermark'] = '<input type="submit" name="watermark" id="watermark" class="tl_submit" accesskey="a" value="'.specialchars($GLOBALS['TL_LANG']['om_watermark']['button_edit']).'">';  
    }
        
    return $arrButtons; 
  }
}
