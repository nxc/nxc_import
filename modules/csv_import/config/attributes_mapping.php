<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$class           = eZContentClass::fetch( (int) $Params['ClassID'] );
$classAttributes = $class instanceof eZContentClass ? $class->attribute( 'data_map' ) : array();
$config          = nxcCSVImportDBConfig::fetch( (int) $Params['ConfigID'] );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'class_attributes', $classAttributes );
$tpl->setVariable( 'config', $config );
echo $tpl->fetch( 'design:csv_import/config/attributes_mapping.tpl' );
eZExecution::cleanExit();
