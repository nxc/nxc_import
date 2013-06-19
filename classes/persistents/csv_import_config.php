<?php
/**
 * @package nxcImport
 * @class   nxcCSVImportDBConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    18 Jun 2013
 **/

class nxcCSVImportDBConfig extends eZPersistentObject
{
	public static function definition() {
		return array(
			'fields'              => array(
				'id' => array(
					'name'     => 'ID',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'name' => array(
					'name'     => 'Name',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				),
				'class_id' => array(
					'name'     => 'ClassID',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'parent_node_id' => array(
					'name'     => 'NodeID',
					'datatype' => 'integer',
					'default'  => 0,
					'required' => true
				),
				'attributes_mapping_serialized' => array(
					'name'     => 'attributesMapping',
					'datatype' => 'string',
					'default'  => null,
					'required' => true
				)
			),
			'function_attributes' => array(
				'class'              => 'getClass',
				'class_attributes'   => 'getClassAttributes',
				'parent_node'        => 'getParentNode',
				'attributes_mapping' => 'getAttributesMapping'
			),
			'keys'                => array( 'id' ),
			'sort'                => array( 'id' => 'asc' ),
			'increment_key'       => 'id',
			'class_name'          => __CLASS__,
			'name'                => 'nxc_csv_import_config'
		);
	}

	public function getClass() {
		return eZContentClass::fetch( $this->attribute( 'class_id' ) );
	}

	public function getClassAttributes() {
		$class = $this->attribute( 'class' );
		return $class instanceof eZContentClass ? $class->attribute( 'data_map' ) : array();
	}


	public function getParentNode() {
		return eZContentObjectTreeNode::fetch( $this->attribute( 'parent_node_id' ) );
	}

	public function getAttributesMapping() {
		return unserialize( $this->attribute( 'attributes_mapping_serialized' ) );
	}

	public static function fetch( $id ) {
		return eZPersistentObject::fetchObject(
			self::definition(),
			null,
			array( 'id' => $id ),
			true
		);
	}

	public static function fetchList( $conditions = null, $limitations = null, $sorts = null ) {
		return eZPersistentObject::fetchObjectList(
			self::definition(),
			null,
			$conditions,
			$sorts,
			$limitations
		);
	}

	public function proccessHTTPInput( $module, $http, $edit = false ) {
		if( $http->hasVariable( 'DiscardButton' ) ) {
			return $module->redirectTo( 'csv_import/configs' );
		}

		if( $http->hasVariable( 'BrowseParentNode' ) ) {
			$url = $edit
				? 'csv_import/edit_config/' . $this->attribute( 'id' )
				: 'csv_import/add_config';
			$browseParameters = array(
				'action_name' => 'AddParentNode',
				'type'        => 'NewObjectAddNodeAssignment',
				'from_page'   => $url
			);
			return eZContentBrowse::browse( $browseParameters, $module );
		}

		if( $http->hasVariable( 'SelectedNodeIDArray' ) ) {
			$nodeIDs = (array) $http->variable( 'SelectedNodeIDArray' );
			$this->setAttribute( 'parent_node_id', (int) $nodeIDs[0] );
		} elseif( $http->hasVariable( 'parent_node_id' ) ) {
			$this->setAttribute( 'parent_node_id', (int) $http->variable( 'parent_node_id' ) );
		}

		if( $http->hasVariable( 'name' ) ) {
			$this->setAttribute( 'name', $http->variable( 'name' ) );
		}
		if( $http->hasVariable( 'class_id' ) ) {
			$this->setAttribute( 'class_id', $http->variable( 'class_id' ) );
		}
		if( $http->hasVariable( 'attributes_mapping' ) ) {
			$this->setAttribute( 'attributes_mapping_serialized', serialize( $http->variable( 'attributes_mapping' ) ) );
		}

		return true;
	}

	public function validate() {
		$errors = array();
		if( strlen( $this->attribute( 'name' ) ) === 0 ) {
			$errors['Name'] = 'Input required';
		}
		if( $this->attribute( 'parent_node' ) instanceof eZContentObjectTreeNode === false ) {
			$errors['Parent node'] = 'Input required';
		}
		if( $this->attribute( 'class' ) instanceof eZContentClass === false ) {
			$errors['Content Class'] = 'Input required';
		}

		$r = array();
		foreach( $errors as $field => $error ) {
			$r[ ezpI18n::tr( 'extension/nxc_import', $field ) ] = ezpI18n::tr( 'extension/nxc_import', $error );
		}
		return $r;
	}

	public static function fetchContentClasses() {
		return eZContentClass::fetchList(
			eZContentClass::VERSION_STATUS_DEFINED,
			true,
			false,
			array( 'name' => false )
		);
	}
}
