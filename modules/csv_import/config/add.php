<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$http   = eZHTTPTool::instance();
$config = new nxcCSVImportDBConfig( array() );
if( ( $r = $config->proccessHTTPInput( $Params['Module'], $http ) ) !== true ) {
	return $r;
}

$errors = array();
if( $http->hasVariable( 'StoreButton' ) ) {
	$errors = $config->validate();
	if( count( $errors ) === 0 ) {
		$config->store();
		$Params['Module']->redirectTo( 'csv_import/configs' );
	}
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'add', true );
$tpl->setVariable( 'config', $config );
$tpl->setVariable( 'classes', nxcCSVImportDBConfig::fetchContentClasses() );
$tpl->setVariable( 'class_attributes', $config->attribute( 'class_attributes' ) );
$tpl->setVariable( 'errors', $errors );

$Result = array();
$Result['content']   = $tpl->fetch( 'design:csv_import/config/edit.tpl' );
$Result['left_menu'] = 'design:csv_import/menu.tpl';
$Result['path']      = array(
	array(
		'text' => ezpI18n::tr( 'extension/nxc_import', 'CSV Import' ),
		'url'  => 'csv_import/configs'
	),
	array(
		'text' => ezpI18n::tr( 'extension/nxc_import', 'New Configuration' ),
		'url'  => false
	)
);
