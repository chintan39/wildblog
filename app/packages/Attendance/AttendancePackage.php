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


class AttendancePackage extends Package {
		
	var $icon="comment";
	
	public function setDefaultConfig() {
		Config::Set("ATTENDANCE_PACKAGE_ORDER", 7, null, Config::INT, true);
		Config::Set("ATTENDANCE_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("ATTENDANCE_PACKAGE_ALLOW", false, null, Config::BOOL, false);
		Config::Set('ATTENDANCE_CONFIRMATION_REPLYTO', '', 'An e-mail set as Reply to in confirmation e-mail', Config::STRING, true);
		Config::Set('ATTENDANCE_CONFIRMATION_BCC', '', 'Colon-separated list of e-mails where to send confirmation as bcc', Config::STRING, true);
		Config::Set('ATTENDANCE_CONFIRMATION_TEXT', '', 'Text of the confirmation e-mail', Config::STRING, true);
		Config::Set('ATTENDANCE_CONFIRMATION_SUBJECT', '', 'Subject of the confirmation e-mail', Config::STRING, true);
	}
	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'label' => tg($this->name . " package"), 
			'title' => tg(""), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Events", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
}

?>
