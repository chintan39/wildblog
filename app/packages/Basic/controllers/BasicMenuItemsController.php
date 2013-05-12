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


class BasicMenuItemsController extends AbstractStructuredCodebookController {
	
	public $order = 2;				// order of the controller (0-10 asc)

	public function actionMoveUp($arg) {
		$this->moveItem($arg, 'up');
		Request::redirect(Request::getLinkItem($this->package, 'Menu', 'actionEdit', $arg->menu, array('paging' => PRESERVE_VALUE, 'order' => PRESERVE_VALUE)));
	}
	
	public function actionMoveDown($arg) {
		$this->moveItem($arg, 'down');
		Request::redirect(Request::getLinkItem($this->package, 'Menu', 'actionEdit', $arg->menu, array('paging' => PRESERVE_VALUE, 'order' => PRESERVE_VALUE)));
	}
	
	
}

?>