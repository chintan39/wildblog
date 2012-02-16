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


/** 
 * Defualt conroller class. 
 * Controller is a layer between model and view layers, it is part of MVC application model.
 * The main purposes of the controller are:
 *  - definitions of the request routes (@see Request.class.php) for the current controller, 
 *    that will be integrated in the request parser (router)
 *  - handle values from forms
 *  - working with database using some DB model
 *  - displaying pages using corresponding smarty templates
 */
class AbstractDefaultController extends AbstractBasicController{
	
	var $listLimit;
	var $detailMethodName = null;
	var $removeMethodName = 'actionRemove';
	var $newMethodName = 'actionNew';
	
	/**
	 * Constructor
	 * Bind controller with the Data model
	 * Set the template engine reference to the attribute to confortable accesss
	 * @param string $model Data model name
	 */
	public function __construct($model, $package) {
		parent::__construct($model, $package);
		$this->listLimit = Config::Get('DEFAULT_PROJECT_PAGING_LIMIT');
	}
	
	public function getListLimit() {
		return $this->listLimit;
	}
	
	/**
	 * Request handler
	 * List of items will be stored in ItemCollection object, then data from the collection 
	 * will be printed with specified buttons, paging, etc.
	 */
	public function actionListing($args) {

		$items = new ItemCollection($this->getMainListIdentifier(), $this);
		$items->setPagingAjax(true);
		$items->setQualification(null); // we overload filters - no qualifications are used
		$items->setDefaultFilters();
		$items->handleFilters();
		$items->forceLanguage(Language::get(Themes::FRONT_END));
		$items->loadCollection();

		$buttons = $this->getListingButtons();
		
		$items->addButtons($buttons);
		
		$this->assign($items->getIdentifier(), $items);
		$this->assign('title', tg('List of ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
	}

	/**
	 * Request handler
	 * List of items will be stored in ItemCollection object, then data from the collection 
	 * will be printed with specified buttons, paging, etc.
	 */
	public function actionJSONListing($args) {

		$item = new $this->model();
		$items = $item->getItems();
		$jsonItems = array();
		foreach ($items as $item) {
			$jsonItems[] = array(
				'value' => $item->id, 
				'text' => $item->makeSelectTitle(),
				); 
		}
		$this->assign('jsonValue', json_encode($jsonItems));
	}
	
	protected function addTopMenu() {
		$adminMenuTop = new LinkCollection();
		$adminMenuTop->setIgnorePermissionDenied(true);
		foreach(AbstractAdminController::getLinksAdminMenuTop($this) as $link) {
			$adminMenuTop->addLink($link);
		}
		$adminMenuTop->markAllLinks();
		$adminMenuTop->sort('order');
		$this->assign('adminMenuTop', $adminMenuTop->getLinks());
	}
	
	
	protected function getListingButtons() {
		
		$buttons = array(
			ItemCollection::BUTTON_EDIT => 'actionEdit', 
			ItemCollection::BUTTON_REMOVE => 'actionRemove', 
			ItemCollection::BUTTON_VIEW => 'actionView',
			//ItemCollection::BUTTON_DISABLE => 'disable'
			);
		return $buttons;
	}

	/**
	 *
	 */
	public function actionSimpleEdit($args) {
		return $this->actionEdit($args);
	}	
	
	
	/**
	 *
	 */
	public function actionEdit($args) {
		
		$item = $args;
		$this->actionEditAdjustItem($item);
		$form = new Form();
		$form->setUseTabs(true);
		$form->setIdentifier(strtolower($this->name));

		// new action if specified
		if ($this->newMethodName) {
			$form->setSaveAsAction(Request::getLinkSimple($this->package, $this->name, $this->newMethodName));
		}
		
		$form->fill($item, $this->getEditButtons());
		$form->addAlternativeAction($this->package, $this, 'actionEdit', $item, tg('Edit item form'));
		$form->addAlternativeAction($this->package, $this, 'actionListing', null, tg('Items list'));
		$form->setDescription($this->getFormDescription());
		
		// handeling the form request
		$form->handleRequest($this->getEditActionsAfterHandlin());
		$this->assign('form', $form->toArray());

		$this->assign('title', tg('Edit ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// detail action if specified
		if ($this->detailMethodName) {
			$this->assign('detailLink', Request::getLinkItem($this->package, $this->name, $this->detailMethodName, $item));
		}
		
		// detail action if specified
		if ($this->removeMethodName) {
			$this->assign('removeLink', Request::getLinkItem($this->package, $this->name, $this->removeMethodName, $item));
		}
		
		// Top menu
		$this->addTopMenu();
		
		if (Config::Get('EDIT_TIMEOUT_WARNING')) {
			Javascript::addTimeout('Your session will time out soon.', Config::Get('EDIT_TIMEOUT_WARNING'));
		}
	}
	
	
	/**
	 * Empty, prepared to overoad. Used to change some model's properties.
	 */
	protected function actionEditAdjustItem(&$item) {
	}
	
	
	protected function getEditButtons() {
		return array(Form::FORM_BUTTON_SAVE, Form::FORM_BUTTON_CANCEL);
	}
	
	
	protected function getEditActionsAfterHandlin() {
		return null;
	}
	
	
	/**
	 *
	 */
	public function actionView($args) {
		$item = $args;
		$changableColumns = $item->getChangeAbleMetaData();
		foreach ($changableColumns as $k => $col) {
			if (in_array($col->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE))) {
				unset($changableColumns[$k]);
			}
		}
		$this->assign('changableColumns', $changableColumns);
		$this->assign('item', $item);

		$this->assign('title', tg('View ' . strtolower($this->name)));
		
		// Top menu
		$this->addTopMenu();
	}
	
	
	/**
	 *
	 */
	public function actionRemove($args) {
		$item = $args;
		$item->DeleteYourself();
		Request::redirect(Request::getLinkSimple($this->package, $this->name, 'actionListing'));
	}
	
	
	/**
	 *
	 */
	public function actionSimpleNew($args) {
		return $this->actionNewSelf('actionSimpleEdit');
	}
	
	
	/**
	 *
	 */
	public function actionNew($args) {
		$this->actionNewSelf('actionEdit');
	}


	private function actionNewSelf($actionAfterSubmit) {
		$item = new $this->model();
		$form = new Form();
		$form->setIdentifier(strtolower($this->name));
		$form->setUseTabs(true);
		$form->fill($item);
		$form->setDescription($this->getFormDescription());
		
		// handeling the form request
		$form->handleRequest(array('all' => array(
			'package' => $this->package, 
			'controller' => $this->name, 
			'action' => $actionAfterSubmit)));
		$this->assign('form', $form->toArray());
		
		$this->assign('title', tg('Insert new ' . strtolower($this->name)));
		$this->assign('help', tg($this->description));
		
		// Top menu
		$this->addTopMenu();
	}
	
	
	/**
	 * Returns the string identifier of the items collection, data in the template 
	 * will be accessible using this identifier.
	 * @return string identifier of the list collection
	 */
	public function getMainListIdentifier() {
		return 'main';
	}

	/**
	 * Returns general name of the form.
	 * @return string identifier of the form in Smarty.
	 */
	protected function getFormName() {
		return 'Edit';
	}

	/**
	 * Returns general description of the form.
	 * @return string description of the form
	 */
	protected function getFormDescription() {
	
		return tg('Default form description');
	}
	
	
	/**
	 * Returns one item specified by ID.
	 * @param int $itemId ID of the item
	 * @return object Returns data entry if found, false if not found.
	 */
	public function getItem($itemId) {
		$data = new $this->model($itemId);
		return $data;
	}
	
	
	/**
	 * Returns one item specified by ID.
	 * @param int $itemId ID of the item
	 * @return object Returns data entry if found, false if not found.
	 */
	public function getItemFilter($filters, $values) {
		$data = new $this->model();
		$items = $data->Find($this->model, $filters, $values);
		if ($items) {
			return $items[0];
		} else {
			return false;
		}
	}
	
	public function getIcon() {
		$modelClass = $this->model;
		if (!$modelClass) {
			throw new Exception ($modelClass);
		}
		$model = new $modelClass();
		return $model->icon;
	}
	
	protected function getCacheFile($cacheIdentification) {
		return DIR_CONTROLLERS_CACHE . $this->package . '__' . $this->name . '__' . $cacheIdentification . '.inc';
	}
	
	protected function getAllCacheFiles() {
		$result = array();
		foreach (scandir(DIR_CONTROLLERS_CACHE) as $file) {
			if (is_file(DIR_CONTROLLERS_CACHE . $file) && preg_match('/^' . $this->package . '__' . $this->name . '__/', $file)) {
				$result[] = DIR_CONTROLLERS_CACHE . $file;
			}
		}
		return $result;
	}
	
	protected function removeAllCacheFiles() {
		foreach ($this->getAllCacheFiles() as $file) {
			unlink($file);
		}
	}
	
	protected function loadCache($cacheIdentification) {
		if (Config::Get('ALLOW_CACHE') && file_exists($this->getCacheFile($cacheIdentification))) {
			return unserialize(file_get_contents($this->getCacheFile($cacheIdentification)));
		} else {
			return false;
		}
	}
	
	private function cacheRemoveNeedlessParts(&$data) {
		if (is_object($data) && (get_class($data) == 'ItemCollection' 
			|| is_subclass_of($data, 'ItemCollection') 
			|| is_subclass_of($data, 'AbstractDefaultModel')
			|| is_subclass_of($data, 'AbstractDefaultController')))
		{
			return $data->removeNeedlessParts();
		} elseif (is_array($data)) {
			$result = array();
			foreach ($data as $key => $item) {
				$result[$key] = $this->removeNeedlessParts($item);
			}
		} else {
			return $data;
		} 
	}
	
	protected function saveCache($cacheIdentification, $data, $modelsAffected = array()) {
		if (!Config::Get('ALLOW_CACHE')) {
			return;
		} 
		file_put_contents($this->getCacheFile($cacheIdentification), serialize($this->cacheRemoveNeedlessParts($data)));
		foreach ($modelsAffected as $m) {
			$mo = new $m();
			$mo->cacheAddAffected($this->getCacheFile($cacheIdentification));
		}
		chmod($this->getCacheFile($cacheIdentification), 0600);
	}
	
	protected function invalidateCache() {
		$this->removeAllCacheFiles();
	}

	public function removeNeedlessParts() {
		$clone = clone $this;
		$this->tpl = null;
		return $clone;
	}


	/**
	 * Generates all public links to all items and actions.
	 * @param array $actionsSimple Simple actions in format array(array('link' => 'actionName', 'title' => 'Action name'))
	 * @param array $actionsItems Actions using items in format array(array('link' => 'actionName', 'title' => 'Action name'))
	 * @return array Link list in format array(array('link' => 'package::controller::actionName::id', 'title' => 'Title of item'))
	 */
	public function getItemsLinksDefault($actionsSimple = array(), $actionsItems=array()) {
		
		$result = array();

		// simple actions
		if (!empty($actionsSimple)) {
			foreach ($actionsSimple as $action => $actionDesc) {
				$result[] = new Link(array(
					'link' => Request::getLinkSimple($this->package, $this->name, $action), 
					'label' => '(' . $this->name . ') ' . $actionDesc, 
					'title' => $actionDesc,
					'action' => array(
						'package' => $this->package,
						'controller' => $this->name,
						'action' => $action,
						),
					));
			}
		}
		
		// actions using items
		if (!empty($actionsItems)) {
			$m = new $this->model();
			if ($m->hasMetaData('active')) {
				$filters = array('active = ?');	
				$values = array(1);
			} else {
				$filters = array();	
				$values = array();
			}
			if ($m->hasMetaData('title')) {
				$order = array('ORDER BY title');
			} else {
				$order = array();
			}
			$items = $m->Find($this->model, $filters, $values, $order);
			if ($items) {
				foreach ($actionsItems as $action => $actionDesc) {
					foreach ($items as $item) {
						$result[] = new Link(array(
							'link' => Request::getLinkItem($this->package, $this->name, $action, $item), 
							'label' => '(' . $actionDesc . ') ' . $item->makeSelectTitle(), 
							'title' => $item->makeSelectTitle(),
							'action' => array(
								'package' => $this->package,
								'controller' => $this->name,
								'action' => $action,
								'item' => $item->id,
								),
							));
					}
				}
			}
		}

		return $result;
		
	}
	
	
	
}

?>