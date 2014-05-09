<?php

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
 * Table tl_om_watermarks
 */
$GLOBALS['TL_DCA']['tl_om_watermarks'] = array
(

  // Config
  'config' => array
  (
    'dataContainer'               => 'Table',
    'enableVersioning'            => true,
    'sql' => array
    (
      'keys' => array
      (
        'id' => 'primary'
      )
    )
  ),

  // List
  'list' => array
  (
    'sorting' => array
    (
      'mode'                    => 1,
      'fields'                  => array('title'),
      'flag'                    => 1,
      'panelLayout'             => 'filter;sort,search,limit',
    ),
    'label' => array
    (
      'fields'                  => array('title')
    ),
    'global_operations' => array
    (
      'all' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
        'href'                => 'act=select',
        'class'               => 'header_edit_all',
        'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
      )
    ),
    'operations' => array
    (
      'edit' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['edit'],
        'href'                => 'act=edit',
        'icon'                => 'edit.gif'
      ),
      'copy' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['copy'],
        'href'                => 'act=copy',
        'icon'                => 'copy.gif'
      ),
      /*
      'cut' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['cut'],
        'href'                => 'act=paste&amp;mode=cut',
        'icon'                => 'cut.gif'
      ),
       */
      'delete' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['delete'],
        'href'                => 'act=delete',
        'icon'                => 'delete.gif',
        'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
      ),
      'toggle' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['toggle'],
        'icon'                => 'visible.gif',
        'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
        'button_callback'     => array('tl_om_watermarks', 'toggleIcon')
      ),
      'show' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_om_watermarks']['show'],
        'href'                => 'act=show',
        'icon'                => 'show.gif'
      )
    )
  ),

  // Palettes
  'palettes' => array
  (
    'default'                     => '{title_legend},title;{directory_legend},directories;{watermark_legend},watermark;{position_legend},position,margins;{settings_legend},quality,log,overwrite;{publish_legend},published'
  ),

  // Fields
  'fields' => array
  (
    'id' => array
    (
      'sql'                     => "int(10) unsigned NOT NULL auto_increment"
    ),
    'tstamp' => array
    (
      'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ),
    'title' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['title'],
      'inputType'               => 'text',
      'eval'                    => array('mandatory' => true),
      'sql'                     => "varchar(255) NOT NULL default ''"
    ),
    'directories' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['directories'],
      'inputType'               => 'fileTree',
      'eval'                    => array('multiple' => true, 'fieldType'=>'checkbox', 'files'=>false, 'mandatory' => true),
      'sql'                     => "blob NULL"
    ),
    'watermark' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['watermark'],
      'inputType'               => 'fileTree',
      'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'extensions'=>'jpg,gif,png',  'filesOnly'=>true, 'mandatory'=>true, 'tl_class'=>'clr'),
      'sql'                     => "binary(16) NULL"
    ),
    'position' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['position'],
      'inputType'               => 'select',
      'options'                 => array('top-left', 'top-center', 'top-right', 'center-left', 'center-center', 'center-right', 'bottom-left', 'bottom-center', 'bottom-right'),
      'reference'               => &$GLOBALS['TL_LANG']['om_watermark'],
      'eval'                    => array('tl_class'=>'w50'),
      'sql'                     => "varchar(20) NOT NULL default ''"
    ),
    'margins' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['margins'],
      'inputType'               => 'trbl',
      'options'                 => array('px'),
      'eval'                    => array('tl_class'=>'w50'),
      'sql'                     => "blob NULL"
    ),
    'quality' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['quality'],
      'default'                 => '80',
      'inputType'               => 'text',
      'eval'                    => array('rgxp'=>'prcnt', 'nospace'=>true, 'tl_class'=>'clr'),
      'sql'                     => "int(10) unsigned NOT NULL default '0'"
    ),
    'log' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['log'],
      'inputType'               => 'checkbox',
      'eval'                    => array('tl_class'=>'w50'),
      'sql'                     => "char(1) NOT NULL default ''"      
    ),
    'overwrite' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['overwrite'],
      'inputType'               => 'checkbox',
      'eval'                    => array('tl_class'=>'w50'),
      'sql'                     => "char(1) NOT NULL default ''"      
    ),
    'published' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_om_watermarks']['published'],
      'inputType'               => 'checkbox',
      'eval'                    => array('doNotCopy'=>true),
      'sql'                     => "char(1) NOT NULL default ''"
    )
  )
);


/**
 * Class tl_om_watermarks
 */
class tl_om_watermarks extends Backend
{  
  /**
   * Import the back end user object
   */
  public function __construct()
  {
    parent::__construct();
    $this->import('BackendUser', 'User');
  }

  
  /**
   * Return the "toggle visibility" button
   * @param array
   * @param string
   * @param string
   * @param string
   * @param string
   * @param string
   * @return string
   */
  public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
  {
    if (strlen(Input::get('tid')))
    {
      $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
      $this->redirect($this->getReferer());
    }

    // Check permissions AFTER checking the tid, so hacking attempts are logged
    if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_watermarks::published', 'alexf'))
    {
      return '';
    }

    $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

    if (!$row['published'])
    {
      $icon = 'invisible.gif';
    }

    return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
  }


  /**
   * Disable/enable a watermark
   * @param integer
   * @param boolean
   */
  public function toggleVisibility($intId, $blnVisible)
  {
    // Check permissions to publish
    if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_watermarks::published', 'alexf'))
    {
      $this->log('Not enough permissions to publish/unpublish Watermark ID "'.$intId.'"', 'tl_om_watermarks toggleVisibility', TL_ERROR);
      $this->redirect('contao/main.php?act=error');
    }

    // Update the database
    $this->Database->prepare("UPDATE tl_om_watermarks SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
                   ->execute($intId);
  }
}
