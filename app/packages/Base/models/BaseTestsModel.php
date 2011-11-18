<?php

class BaseTestsModel extends AbstractVirtualModel {
	
	var $package='Base';
	var $icon='database';

        /**
         *
         * @param array $itemCollectionIdentifier
         * @param <array> $modelName
         * @param <array> $filters
         * @param <type> $values
         * @param <type> $extra
         * @param <type> $justThese
         * @param <type> $order
         * @param <type> $limit
         * @return <type>
         */
	public function getCollectionItems() {
		$list = array();
		$list['items'] = array();
		$list['columns'] = array('id', 'package', 'description');
		foreach (Environment::getPackages() as $package) {
			foreach ($package->getTests() as $test) {
				$item = new BaseTestsPackageModel();
				$item->id = $test->getName();
				$item->description = $test->getDescription();
				$item->package = $package->getName();
				$list['items'][] = $item;
			}
		}
		return $list;
	}
	
	public function getVisibleColumnsInCollection($collectionIdentifier) {
		return array('id', 'package', 'description');
	}
	
	static public function getSortable() {
		return null;
	}

	public function Find($modelName, $filters, $values) {
		$modelName = $values[0];
		if (class_exists($modelName)) {
			$item = new BaseTestsPackageModel($modelName);
			$item->id = $modelName;
			return array($item);
		}
		return false;
	}

}

?>
