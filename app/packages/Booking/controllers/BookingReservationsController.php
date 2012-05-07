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


class BookingReservationsController extends AbstractDefaultController {
	
	public $order = 7;				// order of the controller (0-10 asc)
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}

	/**
	 * Archive action
	 */
	public function actionShowRooms($args) {
		
		$items = new ItemCollection("rooms", Environment::getPackage($this->package)->getController('Rooms'));
		$items->loadCollection();
		
		if (isset(Request::$get['date_from']) && preg_match('/\d{4}-\d{2}-\d{2}/', Request::$get['date_from']))
			$dateFrom = Request::$get['date_from'];
		else
			$dateFrom = date('Y-m-d');
			
		$nights = isset(Request::$get['nights']) ? (int)Request::$get['nights'] : '3';
		$nights = ($nights > 100 || $nights <= 0) ? 3 : $nights;
		
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$item->addNonDbProperty("info");
				$item->info = BookingRoomsModel::getRoomInfo($item, $dateFrom, $nights);
				$minFree = $item->capacity;
				foreach ($item->info as $i)
					if ($i->free < $minFree)
						$minFree = $i->free;
				$item->addNonDbProperty("beds");
				if ($item->priceType == BookingRoomsModel::PRICE_ROOM)
					$item->beds = array(0, $minFree);
				else
					$item->beds = range(0, $minFree);
			}
		}

		$this->assign("rooms", $items);
		$this->assign("dates", Utilities::dateRangeDays($dateFrom, $nights));
		
		$this->assign("title", tg('Reservations from') . ' ' . $dateFrom . ' ' . tg('for #$nights# nights', array('nights' => $nights)));
	}
}

?>