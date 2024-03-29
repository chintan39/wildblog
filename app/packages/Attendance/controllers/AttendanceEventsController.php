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


class AttendanceEventsController extends AbstractNodesController {
	
	var $detailMethodName = 'actionDetail';

	public function actionDetail($event) {
		
		// event detail processing
		$event->addNonDbProperty('participants');
		$event->addNonDbProperty('participantsCount');
		$event->participants = $event->Find('AttendanceParticipantsModel');
		$event->participantsCount = $event->participants ? count($event->participants) : 0;

		// assign to template
		$this->assign('title', $event->title);
		$this->assign('pageTitle', $event->title . ' | ' . tp('Project Title Short'));
		$this->assign('event', $event);
		
		// process form only if there are free places
		if ($event->capacity > $event->participantsCount) {
			$registration = new AttendanceRegistrationModel();
			$registration->event = $event;
			$form = new Form();
			$form->useRecaptcha(true);
			$form->setIdentifier('registrationForm');
			$form->fill($registration);
			$form->setLabel(tg('Register'));
			// handeling the form request
			$form->handleRequest(array('all' => array(
				'package' => $this->package, 
				'controller' => $this->name, 
				'action' => 'actionDetail',
				'item' => $event)));
			$this->assign($form->getIdentifier(), $form->toArray());
		}
	}
	
	/**
	 * Returns all articles, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array(), array('actionDetail' => tg('Registration for event')));
	}
	
	public function actionRemoveAllParticipants($event) {
		$event->DisconnectAll('AttendanceParticipantsModel');
		Request::redirect(Request::getLinkItem($this->package, 'Events', 'actionEdit', $event->id));
	}
}

?>
