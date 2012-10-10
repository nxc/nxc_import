<?php
/**
 * @package nxcImport
 * @class   nxcImportXMLConfigTest
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    05 Nov 2010
 **/

class nxcImportXMLConfigTest extends nxcImportXMLConfig {

	protected $objectTagName          = 'test_object';
	protected $source                 = 'extension/nxc_import/share/import/xml/test.xml';
	protected $contentClassIdentifier = 'nxc_import_test';

	protected function getTransformAttributesArray() {
		return array(
			'name'        => 'name',
			'description' => 'description'
		);
	}

	public function getMainParentNodeID( $objectData ) {
		if( $objectData['remoteID'] != 'nxc_import_test_2' ) {
			return 122;
		} else {
			return 123;
		}
	}

	public function getAdittionalParentNodeIDs( array $objectData ) {
		if( isset( $objectData['XMLNode'] ) === true &&
			( $objectData['XMLNode'] instanceof DOMElement ) === true
		) {
			$objectXMLNode = $objectData['XMLNode'];
			$attributeNode = $objectXMLNode->getElementsByTagName( 'locations' );
			if( (int) $attributeNode->length === 1 ) {
				$locations = (string) $attributeNode->item( 0 )->nodeValue;
				if( strlen( $locations ) > 0 ) {
					return explode( ',', $locations );
				}
			}
		}

		return array();
	}
};
?>