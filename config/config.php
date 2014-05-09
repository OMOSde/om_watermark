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
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['tl_om_watermarks'] = array
(
  'tables' => array('tl_om_watermarks'),
  'icon'   => 'system/modules/om_watermark/assets/icons/watermarks.png'
);


/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['postUpload'][]         = array('OmWatermark', 'omPostUpload');
$GLOBALS['TL_HOOKS']['processFormData'][]    = array('OmWatermark', 'omProcessFormData');
$GLOBALS['TL_HOOKS']['processEfgFormData'][] = array('OmWatermark', 'omProcessEfgFormData'); // seems not necessary
