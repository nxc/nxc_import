<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$http = eZHTTPTool::instance();
if( $http->hasVariable( 'NewButton' ) ) {
	return $Params['Module']->redirectTo( 'csv_import/add_config' );
}

if( $http->hasVariable( 'RemoveButton' ) ) {
	$IDs = (array) $http->variable( 'ConfigIDs' );
	foreach( $IDs as $id ) {
		if( ( $config = nxcCSVImportDBConfig::fetch( $id ) ) instanceof nxcCSVImportDBConfig ) {
			$config->remove();
		}
	}
}


$configs = nxcCSVImportDBConfig::fetchList();

$tpl = eZTemplate::factory();
$tpl->setVariable( 'configs', $configs );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:csv_import/config/list.tpl' );
$Result['left_menu'] = 'design:csv_import/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezpI18n::tr( 'extension/nxc_import', 'CSV Import' ),
		'url'  => false
	)
);
