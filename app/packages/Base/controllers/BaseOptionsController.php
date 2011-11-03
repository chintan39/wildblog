<?php

class BaseOptionsController extends AbstractBasicController {

	/**
	 *
	 */
	public function actionGetMetaOptions($args) {
		
		$options = array();
		
		if (isset(Request::$get['field']) && isset(Request::$get['model'])) {
			$options = MetaDataContainer::getFieldOptions(Request::$get['model'], Request::$get['field']);
		}
		
		echo json_encode($options);
		
		exit;
	}


}

?>