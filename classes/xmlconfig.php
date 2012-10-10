<?php
/**
 * @package nxcImport
 * @class   nxcImportXMLConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    05 Nov 2010
 **/

abstract class nxcImportXMLConfig extends nxcImportConfig {

	protected $objectTagName;
	protected $DOMDocument;

	public function getDataList() {
		$this->dataList = array();

		$content     = file_get_contents( $this->source );
		$this->DOMDocument = new DOMDocument();
		$this->DOMDocument->loadXML( $content );

		$nodes = $this->DOMDocument->getElementsByTagName( $this->objectTagName );
		foreach( $nodes as $node ) {
			$objectData = array(
				'XMLNode' => $node
			);

			$this->dataList[] = $objectData;
		}

		return $this->dataList;
	}

	protected function transformObjectAttribute( array $objectData, $attributeIdentifier ) {
		if( isset( $objectData['XMLNode'] ) === true &&
			( $objectData['XMLNode'] instanceof DOMElement ) === true
		) {
			$objectXMLNode = $objectData['XMLNode'];
			$attributeNode = $objectXMLNode->getElementsByTagName( $attributeIdentifier );
			if( (int) $attributeNode->length === 1 ) {
				return $attributeNode->item( 0 )->nodeValue;
			} else {
				if( $objectXMLNode->hasAttribute( $attributeIdentifier ) === true ) {
					return $objectXMLNode->getAttribute( $attributeIdentifier );
				}
			}
		}

		return null;
	}

	public function getObjectRemoteID( array $objectData ) {
		if( isset( $objectData['XMLNode'] ) === true &&
			( $objectData['XMLNode'] instanceof DOMElement ) === true
		) {
			$objectXMLNode = $objectData['XMLNode'];
			if( $objectXMLNode->hasAttribute( 'id' ) === true ) {
				return $this->contentClass->attribute( 'identifier' ) . '_' . $objectXMLNode->getAttribute( 'id' );
			}
		}

		return parent::getObjectRemoteID( $objectData );
	}
};
?>