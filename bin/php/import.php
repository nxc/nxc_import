#!/usr/bin/env php
<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    05 Nov 2010
 **/

ini_set( 'memory_limit', '1024M' );

require 'autoload.php';

$cli = eZCLI::instance();
$cli->setUseStyles( true );

$scriptSettings = array();
$scriptSettings['description'] = 'NXC Import';
$scriptSettings['use-session'] = true;
$scriptSettings['use-modules'] = true;
$scriptSettings['use-extensions'] = true;

$script = eZScript::instance( $scriptSettings );
$script->startup();
$script->initialize();
$options = $script->getOptions(
	'[remove:][use_state_hashes:][attributes:][update:][create:]',
	'[class]',
	array(
		'class'            => 'Import config class',
		'remove'           => 'Remove objects, which are published, but they aren`t in the import feed?',
		'use_state_hashes' => 'If false - not changed objects will be updated too',
		'attributes'       => 'Attributes, which will be updates (speareted by comma)',
		'update'           => 'Update objects. Only new objects will be created, if this setting is set to "false", "n", "no"',
		'create'           => 'Create objects. Objects will be only updated, if this setting is set to "false", "n", "no"',
	)
);

$ini           = eZINI::instance();
$userCreatorID = $ini->variable( 'UserSettings', 'UserCreatorID' );
$user          = eZUser::fetch( $userCreatorID );
if( ( $user instanceof eZUser ) === false ) {
	$cli->error( 'Cannot get user object by userID = "' . $userCreatorID . '". ( See site.ini [UserSettings].UserCreatorID )' );
	$script->shutdown( 1 );
}
eZUser::setCurrentlyLoggedInUser( $user, $userCreatorID );

if( count( $options['arguments'] ) < 1 ) {
	$cli->error( 'You should specify a import config class' );
	$script->shutdown( 1 );
}
if( class_exists( $options['arguments'][0] ) === false ) {
	$cli->error( 'Class "' . $options['arguments'][0] . '" isn`t available' );
	$script->shutdown( 1 );
}
$remove           = in_array( $options['remove'], array( 'true', 'yes', 'y' ) );
$useStateHashes   = !in_array( $options['use_state_hashes'], array( 'false', 'no', 'n' ) );
$update           = !in_array( $options['update'], array( 'false', 'no', 'n' ) );
$create           = !in_array( $options['create'], array( 'false', 'no', 'n' ) );
$filterAttributes = explode( ',', $options['attributes'] );
foreach( $filterAttributes as $key => $attribute ) {
	if( strlen( $attribute ) === 0 ) {
		unset( $filterAttributes[ $key ] );
	}
}

$importConfig = new $options['arguments'][0];
if( $importConfig->getContentClass() instanceof eZContentClass === false ) {
	$cli->error( 'Import config hasn`t a valid content class' );
	$script->shutdown( 1 );
}
if( $importConfig instanceof nxcImportConfig === false ) {
	$cli->error( 'Class "' . $options['arguments'][0] . '" isn`t a valid import config' );
	$script->shutdown( 1 );
}
if( count( $filterAttributes ) > 0 ) {
	$importConfig->setFilterAttributes( $filterAttributes );
}



$importController = new nxcImportController( $importConfig, $cli );
$importController->log( 'Starting import for ' . $importConfig, array( 'blue' ) );

$startTime = microtime( true );
$importController->run( $remove, $useStateHashes, $update, $create );

$executionTime = round( microtime( true ) - $startTime, 2 );

$importController->log( 'Import took ' . $executionTime . ' secs.' );
$importController->log( 'Created ' . $importController->counter['create'] . ' objects, updated ' . $importController->counter['update'] . ' objects, skiped ' . $importController->counter['skip'] . ' object.' );
$importController->log( 'Available objects in feed: ' . count( $importController->config->dataList ) . '.' );

if( $importController->counter['create'] + $importController->counter['update'] > 0) {
	$speed = round(
		( $importController->counter['create'] + $importController->counter['update'] ) / $executionTime,
		2
	);

	$importController->log( 'Average speed: ' . $speed . ' objects/sec.' );
}

if( $importController->counter['remove'] > 0 ) {
	$importController->log( $importController->counter['remove'] . ' objects was removed' );
}

unset( $importController );
$script->shutdown( 0 );
