<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$http    = eZHTTPTool::instance();
$configs = nxcCSVImportDBConfig::fetchList();
$config  = null;
$errors  = array();
$counter = array();

if( $http->hasVariable( 'ImportButton' ) ) {
	$config = nxcCSVImportDBConfig::fetch( (int) $http->variable( 'config_id' ) );
	if( $config instanceof nxcCSVImportDBConfig === false ) {
		$errors[] = ezpI18n::tr( 'extension/nxc_import', 'Please select Import Configuration' );
	}

	$allowedMimeTypes = array(
		'text/csv',
		'text/plain',
		'application/csv',
		'text/comma-separated-values',
		'application/excel',
		'application/vnd.ms-excel',
		'application/vnd.msexcel',
		'text/anytext',
		'application/octet-stream',
		'application/txt'
	);
	$file = $_FILES['file'];
	if(
		empty( $file['name'] )
		|| in_array( $file['type'], $allowedMimeTypes ) === false
	) {
		$errors[] = ezpI18n::tr( 'extension/nxc_import', 'Please upload valid CSV file' );
	}

	if( count( $errors ) === 0 ) {
		$importConfig = new nxcImportDatabaseCSVConfig();
		$importConfig->setConfig( $config );
		$importConfig->setSource( $file['tmp_name'] );

		$controller = new nxcImportController( $importConfig, false, false );
		$controller->run( false, false, true, true );
		$counter = $controller->counter;
	}
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'configs', $configs );
$tpl->setVariable( 'selected_config', $config );
$tpl->setVariable( 'errors', $errors );
$tpl->setVariable( 'counter', $counter );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:csv_import/config/import.tpl' );
$Result['left_menu'] = 'design:csv_import/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezpI18n::tr( 'extension/nxc_import', 'CSV Import' ),
		'url'  => false
	)
);
