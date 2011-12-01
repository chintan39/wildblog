<?php

abstract class AbstractAdminController {

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
		
		if ($object instanceof AbstractPagesController) {
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