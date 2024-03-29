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
 * This class contains a tool to make a complex paging structures. 
 * These structures are assign to/use in the items collection.
 * This could be a part of the collection itself, it is not able to use it independently. 
 */
class Paging {

	/**
	 * Constructor.
	 */
	public function __construct($limit=DEFAULT_PAGING_LIMIT) {
		$this->limit = $limit;
	}
	
	
	/**
	 * Get start part of the select query.
	 */
	public function getStart($itemCollectionIdentifier) {
		$actualPage = $this->getActualPage($itemCollectionIdentifier);
		return $actualPage * $this->limit;
	} 
	
	
	/**
	 * Convert the limit of the paging to the SQL command.
	 */
	public function getLimitSQL($itemCollectionIdentifier) {
		if ($this->limit) {
			$actualPage = $this->getActualPage($itemCollectionIdentifier);
			$start = $actualPage * $this->limit;
			return " limit $start, " . $this->limit . " ";
		} else {
			return "";
		}
	}
	
	
	/**
	 * Gets the actual page from the request.
	 */
	protected function getActualPage($itemCollectionIdentifier) {
		return isset(Request::$get["paging"][$itemCollectionIdentifier]) ? Request::$get["paging"][$itemCollectionIdentifier] * 1 : 0;
	}
	
	
	/**
	 * Makes the paging structure.
	 */
	public function getStructure($itemCollectionIdentifier, $totalItems) {
		$paging = array();
		$paging["limit"] = $this->limit;
		$paging["actual"] = $this->getActualPage($itemCollectionIdentifier);
		$paging["totalItems"] = $totalItems;
		$paging["totalPages"] = floor(((int)($totalItems - 1)) / $this->limit) + 1;
		$paging["first"] = $paging["actual"] > 0 ? 0 : false ;
		$paging["prev"] = $paging["actual"] > $paging["first"] ? $paging["actual"] - 1 : false;
		$paging["last"] = $paging["totalPages"] - 1 > $paging["actual"] ? $paging["totalPages"] - 1 : false;
		$paging["next"] = $paging["last"] && $paging["actual"] < $paging["last"] ? $paging["actual"] + 1 : false;
		$paging["prevList"] = ($paging["first"] !== false) ? Utilities::range($paging["first"], $paging["actual"] - 1, Config::Get('PAGING_LIST_COUNT'), true) : array();
		$paging["nextList"] = ($paging["last"] !== false) ? Utilities::range($paging["actual"] + 1, $paging["last"], Config::Get('PAGING_LIST_COUNT')) : array();
		return $paging;
	}

}

?>