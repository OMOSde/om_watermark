<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Om_watermark
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'OmWatermark'       => 'system/modules/om_watermark/modules/OmWatermark.php',

	// Models
	'OmWatermarksModel' => 'system/modules/om_watermark/models/OmWatermarksModel.php',
));
