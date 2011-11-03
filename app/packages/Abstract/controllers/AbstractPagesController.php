<?php

class AbstractPagesController extends AbstractNodesController {
	
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