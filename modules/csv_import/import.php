<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$configs = nxcCSVImportDBConfig::fetchList();

$tpl = eZTemplate::factory();
$tpl->setVariable( 'configs', $configs );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:csv_import/config/import.tpl' );
$Result['left_menu'] = 'design:csv_import/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezpI18n::tr( 'extension/nxc_import', 'CSV Import' ),
		'url'  => false
	)
);
