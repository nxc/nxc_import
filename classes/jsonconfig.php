<?php
/**
 * @package nxcImport
 * @class   nxcImportJSONConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    22 Mar 2012
 **/

abstract class nxcImportJSONConfig extends nxcImportConfig
{
	protected $filterClassName = false;

	public function __construct() {
		if( $this->filterClassName === false ) {
			$this->filterClassName = __CLASS__;
		}

		parent::__construct();
	}

	public function getDataList() {
		$this->dataList = array();

		if( file_exists( $this->source ) ) {
			$content = trim( file_get_contents( $this->source ) );
			$data    = @json_decode( $content, true );

			if(
				is_array( $data )
				|| is_object( $data )
			) {
				$rows = call_user_func(
					array(
						$this->filterClassName,
						'filterResult'
					),
					$data
				);

				foreach( $rows as $row ) {
					$objectData = array(
						'JSONObject' => $row
					);

					$this->dataList[] = $objectData;
				}
			}
		}

		return $this->dataList;
	}

	protected static function filterResult( $data ) {
		$return = array();

		foreach( $data as $key => $value ) {
			if( is_object( $value ) ) {
				$return[] = $value;
			}
		}

		return $return;
	}

	protected function transformObjectAttribute( array $objectData, $attributeKey ) {
		if(
			isset( $objectData['JSONObject'] ) === true &&
			is_object( $objectData['JSONObject'] ) === true
		) {
			$object = $objectData['JSONObject'];
			if( isset( $object->$attributeKey ) === true ) {
				return $object->$attributeKey;
			}
		}

		return null;
	}
};
?>