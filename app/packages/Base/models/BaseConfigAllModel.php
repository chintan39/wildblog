<?php

/**
 * 
 */
 
class BaseConfigAllModelItem extends AbstractBasicModel {
	public function getValue($fieldName) {
		return $this->$fieldName;
	}
} 

class BaseConfigAllModel extends BaseConfigModel {
	
    var $useInInitDatabase = false;
	
	public function getCollectionItems() {
		// we are overriding limit from arg
		$limit = $this->getLimit();
		$list = array();
		$list["items"] = array();
		$count = $index = 0;
		$keys = array_keys(Config::$data);
		sort($keys);
		foreach ($keys as $key) {
			$value = Config::Get($key);
			if  ($this->qualification) {
				if (isset($this->qualification['filters']['key'][0]) && !preg_match('/'.$this->qualification['filters']['key'][0].'/i', $key)) {
					continue;
				}
				if (isset($this->qualification['filters']['text'][0]) && !preg_match('/'.$this->qualification['filters']['text'][0].'/i', $value)) {
					continue;
				}
			}
			if (++$count > $limit['limit']+$limit['start'] || $index++ < $limit['start'])
				continue;
			$meta = Config::$meta[$key];
			$newVal = new BaseConfigAllModelItem();
			$newVal->id = ($meta->inDB > 0) ? $meta->inDB : -1;
			$newVal->key = $key;
			$newVal->text = $value;
			$list["items"][] = $newVal;
		}
		$list["columns"] = $this->getVisibleColumnsInCollection();
		$list["itemsCount"] = $count;
		return $list;
	}
}


?>
