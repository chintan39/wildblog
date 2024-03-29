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
 * Class represents general DB index.
 */

class ModelMetaIndex {
	const PRIMARY     = 'primary';
	const INDEX       = 'index';
	const UNIQUE      = 'unique';
	const UNIQUE_LANG = 'unique_lang';
	const FULLTEXT    = 'fulltext';
	
	public $name;
	public $type;
	public $columns;
	public $lengths = array(); /* if column is CLOB/TEXT, then we need to specify key lenght */
	
	/**
	 * Constructor
	 * @param string|array $columns
	 * @param string $type
	 * @param array $lengths how long text keys should be
	 * @param string $indexName use this name instead of original
	 */
	function __construct($columns, $type=self::INDEX, $lengths=array(), $indexName='') {
		if (!is_array($columns))
			$columns = array($columns);
		
		$this->name = $indexName ? strtolower($indexName) : ($type == self::PRIMARY ? 'primary' : ($type . '_' . implode('_', $columns)));
		$this->type = $type;
		$this->columns = $columns;
		$this->lengths = $lengths;
	}
}


/**
 * Class represents general DB column.
 */

class ModelMetaColumn {
	
	public $name;
	public $type;
	
	/**
	 * Constructor
	 * @param string $name
	 * @param string $type
	 */
	function __construct($name, $type) {
		$this->name = $name;
		$this->type = $type;
	}
}

?>
