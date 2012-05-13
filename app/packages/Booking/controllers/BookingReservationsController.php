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
		
		$form = new Form();
		$form->setIdentifier('reservationForm');
		$form->setSteps(3); /* must be called before ::fill to add buttons */
		$form->setDisplayFormOnAccomplished(false);
		$form->fill($reservationFormModel);
		$form->handleRequest();

		$this->assign($form->getIdentifier(), $form->toArray());
		$this->assign("dates", Utilities::dateRangeDays($reservationFormModel->date_from, $reservationFormModel->nights));
		
		$this->assign("title", tg('Reservations from') . ' ' . $reservationFormModel->date_from . ' ' . tg('for #$nights# nights', array('nights' => $reservationFormModel->nights)));
	}

	
	/**
	 *
	 */
	public function actionEditSelf($args, $isSimple=false) {
		$item = new BookingReservationViewModel($args->id);
		Request::reGenerateToken();
		$this->actionEditAdjustItem($item);
		$form = new Form();
		$form->setFocusFirstItem(true);
		$form->setSendAjax(Request::isAjax());
		$form->setUseTabs(true);
		$form->setCsrf(true);
		$form->setIdentifier(strtolower($this->name));
		$form->setSteps(2);

		$form->fill($item, $this->getEditButtons());
		$form->setDescription($this->getFormDescription());
		
		// handeling the form request
		$form->handleRequest($this->getEditActionsAfterHandlin(), tg('Item has been saved.'));
		$this->assign('form', $form->toArray());

		$this->assign('title', tg('Edit ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
		
		if (Config::Get('EDIT_TIMEOUT_WARNING')) {
			Javascript::addTimeout('Your session will time out soon.', Config::Get('EDIT_TIMEOUT_WARNING'));
		}
	}
	
	
	/**
	 *
	 */
	public function actionView($args) {
		$item = $args;
		echo "I'm changing this.".$item->id;
		$item->getMetaData('date_from')->setIsEditable(ModelMetaItem::NEVER);
		$changableColumns = $item->getChangeAbleOrAutoFilledMetaData();
		foreach ($changableColumns as $k => $col) {
			if (in_array($col->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE))) {
				unset($changableColumns[$k]);
			}
		}
		
		$changes = BaseChangesModel::Search('BaseChangesModel', array('packagename = ? and model = ? and item = ?'), array($item->package, $item->getName(), $item->id), array("ORDER BY `inserted` DESC"));
		if (!$changes) $changes = array();
		
		$this->assign('changableColumns', $changableColumns);
		$this->assign('item', $item);
		$this->assign('changes', $changes);
		$this->assign('editLink', Request::getLinkItem($this->package, $this->name, 'actionEdit', $item));

		$this->assign('title', tg('View ' . strtolower($this->name) . ' (#' . $item->id . ')'));
		
		// Top menu
		$this->addTopMenu();
	}
}

?>