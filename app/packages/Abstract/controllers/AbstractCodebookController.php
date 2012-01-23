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



class AbstractCodebookController extends AbstractDefaultController {
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

	public function moveItem($item, $direction='up') {
		$model = new $this->model();
		$sortable = $model->getSortable();
		$sortableDirection = $sortable ? $sortable : 'desc';
		/* if sorting is desc and we're moving down 
		      or sorting is asc and we're moving up, we need rank < actual.
		   if sorting is asc and we're moving down 
		      or sorting is desc and we're moving up, we need rank > actual. 
		   So simply: sorting is desc xor moving up
		 */
		$operator = ($sortableDirection == 'desc' xor $direction == 'up') ? '<' : '>';
		$findingDirection = ($sortableDirection == 'desc' xor $direction == 'up') ? 'desc' : 'asc';
		$prev = $model->Find($this->model, array("rank $operator ?"), array($item->rank), array("order by rank $findingDirection", "limit 1"));
		if ($prev) {
			$tmp1 = $prev[0]->rank;
			$tmp2 = $item->rank;
			// temporary value to not get unique error
			$prev[0]->rank = -1;
			$prev[0]->Save();
			$item->rank = $tmp1;
			$item->Save();
			$prev[0]->rank = $tmp2;
			$prev[0]->Save();
		}
		
	}
	
	public function actionMoveUp($arg) {
		$this->moveItem($arg, 'up');
		Request::redirect(Request::getLinkSimple($this->package, $this, "actionListing", array('paging' => PRESERVE_VALUE, 'order' => PRESERVE_VALUE)));
	}
	
	public function actionMoveDown($arg) {
		$this->moveItem($arg, 'down');
		Request::redirect(Request::getLinkSimple($this->package, $this, "actionListing", array('paging' => PRESERVE_VALUE, 'order' => PRESERVE_VALUE)));
	}
	
	protected function getListingButtons() {
		$buttons = parent::getListingButtons();
		$buttons = $buttons + array(
			ItemCollection::BUTTON_MOVEUP => "actionMoveUp", 
			ItemCollection::BUTTON_MOVEDOWN => "actionMoveDown",
			);
		return $buttons;
	}
}

?>