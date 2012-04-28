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


abstract class AbstractAdminController {

	static function getLinksAdminMenuLeft($object) {
		$listLink = new Link(array(
			//'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
			'label' => tg($object->name), 
			'title' => tg('list of ' . strtolower($object->name)), 
			'image' => $object->getIcon(), 
			'action' => array(
				'package' => $object->package, 
				'controller' => $object->name, 
				'action' => 'actionListing')));
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionEdit');
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionNew');
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionView');
		$listLink->setOrder($object->order);
		return array($listLink);
	}

	static function getLinksAdminMenuTop($object) {
		$listLink = new Link(array(
			//'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
			'label' => (tg('list of' . ' ' . strtolower($object->name))),
			'title' => (tg('list of' . ' ' . strtolower($object->name))), 
			'image' => 'list', 
			'action' => array(
				'package' => $object->package, 
				'controller' => $object->name, 
				'action' => 'actionListing')));
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionEdit');
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionView');
		$listLink->setOrder($object->order);
		$newLink = new Link(array(
			//'link' => Request::getLinkSimple($object->package, $object->name, 'actionNew'), 
			'label' => tg('insert new' . ' ' . strtolower($object->name)), 
			'title' => tg('insert new' . ' ' . strtolower($object->name)), 
			'image' => 'add', 
			'action' => array(
				'package' => $object->package, 
				'controller' => $object->name, 
				'action' => 'actionNew')));
		$newLink->setOrder($object->order);
		return array($listLink, $newLink);
	}

	static function getLinksAdminMenuLeftListing($object) {
		$listLink = new Link(array(
			//'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
			'label' => tg($object->name), 
			'title' => tg('list of ' . strtolower($object->name)), 
			'image' => $object->getIcon(), 
			'action' => array(
				'package' => $object->package, 
				'controller' => $object->name, 
				'action' => 'actionListing')));
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionEdit');
		$listLink->addSuperiorActiveActions($object->package, $object->name, 'actionNew');
		$listLink->setOrder($object->order);
		return array($listLink);
	}
}

?>