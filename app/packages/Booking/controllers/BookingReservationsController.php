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
		
		$reservationFormModel = new BookingReservationFormModel();
		
		$items = new ItemCollection("rooms", Environment::getPackage($this->package)->getController('Rooms'));
		$items->loadCollection();
		
		if ($items->data["items"]) {
			foreach ($items->data["items"] as $key => $item) {
				$reservationFormModel->addRoom($item);
			}
		}
		
		$form = new Form();
		$form->setIdentifier('reservationForm');
		$form->setSteps(3); /* must be called before ::fill to add buttons */
		$form->setDisplayFormOnAccomplished(false);
		$form->fill($reservationFormModel);
		$form->handleRequest();

		$this->assign($form->getIdentifier(), $form->toArray());
		$this->assign("rooms", $items);
		$this->assign("dates", Utilities::dateRangeDays($reservationFormModel->date_from, $reservationFormModel->nights));
		
		$this->assign("title", tg('Reservations from') . ' ' . $reservationFormModel->date_from . ' ' . tg('for #$nights# nights', array('nights' => $reservationFormModel->nights)));
	}
}

?>