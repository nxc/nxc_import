<?php
/**
 * @package nxcImport
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

$Module = array(
	'name'            => 'CSV Import',
 	'variable_params' => true
);

$ViewList = array(
	'configs' => array(
		'functions'               => array( 'config' ),
		'script'                  => 'config/list.php',
		'params'                  => array(),
		'default_navigation_part' => 'ezsetupnavigationpart'
	),
	'add_config' => array(
		'functions'               => array( 'config' ),
		'script'                  => 'config/add.php',
		'params'                  => array(),
		'default_navigation_part' => 'ezsetupnavigationpart'
	),
	'edit_config' => array(
		'functions'               => array( 'config' ),
		'script'                  => 'config/edit.php',
		'params'                  => array( 'ConfigID' ),
		'default_navigation_part' => 'ezsetupnavigationpart'
	),
	'attributes_mapping' => array(
		'functions' => array( 'config' ),
		'script'    => 'config/attributes_mapping.php',
		'params'    => array( 'ClassID', 'ConfigID' )
	),
	'import' => array(
		'functions'               => array( 'import' ),
		'script'                  => 'import.php',
		'params'                  => array(),
		'default_navigation_part' => 'ezsetupnavigationpart'
	)
);

$FunctionList = array(
	'config' => array(),
	'import' => array()
);

