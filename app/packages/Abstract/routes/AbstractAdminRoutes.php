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


class AbstractAdminRoutes {

	var $abstract=true;

	static public function setRouter($object, $permission=null) {
		
		$actionsPrefix = isset($object->actionsPrefix) ? $object->actionsPrefix : 'admin/' . strtolower($object->package) . '/' . strtolower($object->name) . '/';
		
		if ($permission === null) {
			$permission = Permission::$CONTENT_ADMIN | Permission::$ADMIN;
		}

		Router::registerAction($object, 'actionEdit') 
			->addRuleUrl($actionsPrefix . 'edit/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultEdit')
			->setPermission($permission);

		Router::registerAction($object, 'actionSimpleEdit') 
			->addRuleUrl($actionsPrefix . 'simple-edit/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultSimpleEdit')
			->setPermission($permission);

		Router::registerAction($object, 'actionListing')
			->addRuleUrl($actionsPrefix . '$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultList')
			->setPermission($permission);
		
		Router::registerAction($object, 'actionJSONListing')
			->addRuleUrl($actionsPrefix . 'json-list/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|JSONvalue')
			->setPermission($permission);
		
		Router::registerAction($object, 'actionNew')
			->addRuleUrl($actionsPrefix . 'new/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultEdit')
			->setPermission($permission);
		
		Router::registerAction($object, 'actionSimpleNew')
			->addRuleUrl($actionsPrefix . 'simple-new/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultSimpleEdit')
			->setPermission($permission);
		
		Router::registerAction($object, 'actionRemove') 
			->addRuleUrl($actionsPrefix . 'remove/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setPermission($permission);
		
		Router::registerAction($object, 'actionView') 
			->addRuleUrl($actionsPrefix . 'view/$')
			->addRuleGet(array('id'=>'[id]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|defaultView')
			->setPermission($permission);
		
		if ($object instanceof AbstractPagesRoutes) {
			Router::registerAction($object, 'actionMoveUp')
				->addRuleUrl($actionsPrefix . 'moveup/$')
				->addRuleGet(array('id'=>'[id]'))
				->setBranch(Themes::BACK_END)
				->setPermission($permission);
			
			Router::registerAction($object, 'actionMoveDown') 
				->addRuleUrl($actionsPrefix . 'movedown/$')
				->addRuleGet(array('id'=>'[id]'))
				->setBranch(Themes::BACK_END)
				->setPermission($permission);
		}
	}
	
	static function getLinksAdminMenuLeft($object) {
		$listLink = new Link(array(
			'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
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
			'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
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
			'link' => Request::getLinkSimple($object->package, $object->name, 'actionNew'), 
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
			'link' => Request::getLinkSimple($object->package, $object->name, 'actionListing'), 
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