<?php
/**
 * @package nxcImport
 * @class   nxcImportConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    05 Nov 2010
 **/

abstract class nxcImportConfig
{
	abstract public function getDataList();
	abstract protected function transformObjectAttribute( array $objectData, $attributeIdentifier );

	public $dataList = array();

	protected $contentClassIdentifier;
	protected $contentClass;
	protected $source;
	protected static $stateHashSeparator = ':::';
	public $filterAttributes = array();

	public function __construct() {
		$this->contentClass = eZContentClass::fetchByIdentifier( $this->contentClassIdentifier );
		$this->source       = $this->getSource();
	}

	protected function getSource() {
		return $this->source;
	}

	final public function setFilterAttributes( array $filterAttributes = array() ) {
		$this->filterAttributes = $filterAttributes;
	}

	final public function getContentClass() {
		return $this->contentClass;
	}

	final public function transformObjectAttributes( array $objectData ) {
		$attributes = array();

		$attributeIdentifiers = array();
		$classAttributes      = $this->contentClass->fetchAttributes();
		foreach( $classAttributes as $attribute ) {
			$attributeIdentifiers[] = $attribute->attribute( 'identifier' );
		}

		$transformMethods    = $this->getTransformAttributesMethods();
		$transformAttributes = $this->getTransformAttributesArray();

		$attributeIdentifiers = array_merge(
			$attributeIdentifiers,
			array_keys( $transformAttributes ),
			array_keys( $transformMethods )
		);
		$attributeIdentifiers = array_unique( $attributeIdentifiers );

		foreach( $attributeIdentifiers as $attributeIdentifier ) {
			if(
				count( $this->filterAttributes ) > 0
				&& in_array( $attributeIdentifier, $this->filterAttributes ) === false
			) {
				continue;
			}

			if( isset( $transformMethods[ $attributeIdentifier ] ) ) {
				$transformMethod = $transformMethods[ $attributeIdentifier ];
				$args = array( $objectData, $attributeIdentifier );
				if( isset( $transformMethod['params'] ) ) {
					$args = array_merge( $args, $transformMethod['params'] );
				}

				$attributes[ $attributeIdentifier ] = call_user_func_array(
					array( $transformMethod['class'], $transformMethod['method'] ),
					$args
				);
			} else {
				$transformAttributeIdentifier = isset( $transformAttributes[ $attributeIdentifier ] )
					? $transformAttributes[ $attributeIdentifier ]
					: $attributeIdentifier;
				$value = $this->transformObjectAttribute(
					$objectData,
					$transformAttributeIdentifier
				);
				if( $value !== null ) {
					$attributes[ $attributeIdentifier ] = $value;
				}
			}
		}

		return $attributes;
	}

	protected function getTransformAttributesArray() {
		return array();
	}

	protected function getTransformAttributesMethods() {
		return array();
	}

	public function getObjectRemoteID( array $objectData ) {
		return md5( microtime() );
	}

	public function getMainParentNodeID( array $objectData ) {
		return false;
	}

	public function getAdittionalParentNodeIDs( array $objectData ) {
		return array();
	}

	public function getLanguage( array $objectData ) {
		return false;
	}

	public function getVersionStatus( array $objectData ) {
		return eZContentObjectVersion::STATUS_PUBLISHED;
	}

	public function isVisible( array $objectData ) {
		return true;
	}

	public function getMainNodePriority( array $objectData ) {
		return false;
	}

	public function getStateHash( array $objectData ) {
		$state = $objectData['mainParentNodeID'] . self::$stateHashSeparator;
		foreach( $objectData['adittionalParentNodeIDs'] as $nodeID ) {
			$state .= $nodeID . self::$stateHashSeparator;
		}
		if( (bool) $objectData['visibility'] === false ) {
			$state .= 'hidden' . self::$stateHashSeparator;
		}

		if( isset( $objectData['attributes'] ) === true ) {
			foreach( $objectData['attributes'] as $key => $value ) {
				$state .= $key . self::$stateHashSeparator . $value . self::$stateHashSeparator;
			}
		}

		return md5( $state );
	}

	public function preProcessCallback( $object, array $objectData ) {
		return true;
	}

	public function postProcessCallback( $object, array $objectData ) {
		return true;
	}

	public function __toString() {
		return get_class( $this ) . ', source: ' . $this->source;
	}
};
