<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


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
