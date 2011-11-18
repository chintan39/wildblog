<?php

/**
 * This class represents the collections of some items (data items). 
 * The collection could be another layer between controller and model, 
 * which basicly belongs to the controller, but it can handle some 
 * controller-independent operations.
 */
class ItemCollection {
	
	const BUTTON_EDIT = 1;
	const BUTTON_REMOVE = 2;
	const BUTTON_VIEW = 3;
	const BUTTON_MOVEUP = 4;
	const BUTTON_MOVEDOWN = 5;
	const BUTTON_DISABLE = 6;
	const BUTTON_EXPORT = 7;
	
	const treeRoot = 1;
	
	const treeAncestors = 1;
	const treeAllDescendants = 2;
	const treeDirectDescendants = 4;
	const treeSiblings = 8;
	const treeAll = 16;

	public
	$dm = null,
	$filterForm = false,
	$data, 				// array with keys items, itemsCount, columns, paging
	$pagingAjax = false,
	$containerId;
	
	private 
	$identifier, 
	$controller, 
	$dataModel, 
	$buttons, 
	$buttonsProceeded = false,
	$subCollection = null,
	$foreignKeyParent = 'parent',
	$linkAction = null,
	$linkAttribute = 'link',
	$qualification = false,
	$sorting = false,
	$sortingColumns = false,
	$sortingLinks = false,
	$filtersSettingsModel = false,
	$limit = -1,
	$forceLanguage = false,
	$modelParams = array(),
	$sortable = null,
	$treeBase=null,
	$treePull=self::treeAll;
	
	
	/**
	 * Constructor.
	 * @param string $identifier
	 * @param object $controller
	 * @param string $dataModel
	 * @param string $dataModelMethod='getCollectionItems'
	 * @param array $buttons
	 * @param array|int $subCollection
	 * @param string $linkAction
	 */
	public function __construct($identifier='defaultCollection', $controller, $dataModel=null, $dataModelMethod='getCollectionItems', $buttons=null, $subCollection=null, $linkAction=null) {
		$this->controller = $controller;
		if ($controller) {
			$this->limit = $controller->getListLimit();
		} else {
			$this->limit = Config::Get('DEFAULT_PROJECT_PAGING_LIMIT');
		}
		
		// set dataModel, if not set, default controller's model is used
		if ($dataModel === null) {
			$this->dataModel = $this->controller->getModel();
		} else {
			$this->dataModel = $dataModel;
		}
		
		// specify the method, if not set, standard method used
		$this->dataModelMethod = $dataModelMethod;
		$this->identifier = $identifier;
		$this->containerId = "container_$identifier";
		
		// buttons can be added after by using public method addButtons
		if ($buttons !== null) {
			$this->buttons = $buttons;
		}
		
		// link action to make a link by all items
		$this->linkAction = $linkAction;
		
		// to make a tree we can add subCollection (if it is integer, tree structure will be done to the high if the value)
		if (is_int($subCollection)) {
			$this->setTreeHigh($subCollection);
		} else {
			$this->subCollection = $this->addSubCollection($subCollection);
		}
	}
	
	/**
	 * Gets data from Data model and adds a paging items to the list.
	 */
	public function loadCollection($itemIdArray=array()) {
		$this->loadItems($itemIdArray);
		$this->addPagingLinks();
		
		// load Items to subCollection if there is one
		$this->loadSubItems();
	}
	
	private function getDm() {
		if ($this->dm === null) {
			$this->dm = new $this->dataModel();
		}
		return $this->dm;
	}
	
	/**
	 * Gets data from Data model
	 */
	private function loadItems($itemIdArray=array()) {
		
		// init
		$method = $this->dataModelMethod;
		
		// get qualification
		if ($this->qualification !== false) {
			$this->getDm()->setQualification($this->qualification); // set temporary qualification of the items
		}
		
		// sorting
		$sorting = $this->getSortingFromUrl();
		if ($sorting['column'] !== '') {
			$this->getDm()->setSorting($sorting); // set temporary sorting of the items
		} elseif ($this->sorting !== false) {
			$this->getDm()->setSorting($this->sorting); // set temporary sorting of the items
		}
		
		// if it could be sorted manualy
		$this->sortable = $this->getDm()->getSortable();
		
		// limit
		if ($this->limit !== false) {
			if (!is_array($this->limit)) {
				$paging = new Paging($this->limit);
				$limitStart = $paging->getStart($this->identifier);
				$this->limit = array('start' => $limitStart, 'limit' => $this->limit);
			}
			$this->getDm()->setLimit($this->limit); // set temporary limit of the items
		}
		
		// language
		if ($this->forceLanguage) {
			$this->getDm()->forceLanguage($this->forceLanguage);
		}
		
		// get data
		$this->data = $this->getDm()->$method($this->identifier, $itemIdArray);
	}
	
	/**
	 * Gets data from Data model
	 */
	public function loadItemsFromItems($itemList, $limit=DEFAULT_PAGING_LIMIT) {
		$paging = new Paging($limit);
		$limitStart = $paging->getStart($this->identifier);
		$limit = array('start' => $limitStart, 'limit' => $limit);
		$this->data = array();
		$this->data['items'] = array();
		for ($i = $limit['start']; $i < $limit['start']+$limit['limit']; $i++) {
			if (isset($itemList[$i])) {
				$this->data['items'][] = $itemList[$i];
			}
		}
		if ($itemList && count($itemList)) {
			$this->data['columns'] = $itemList[0]->getVisibleColumnsInCollection($this->identifier);
		} else {
			$this->data['columns'] = array();
		}
		$this->data['itemsCount'] = count($itemList);
	}
	
	/**
	 * Add items to the collection data
	 */
	public function addItems($items) {
		$this->data['items'] = array_merge($this->data['items'], $items);
	}
	

	/**
	 * Add a column to the collection data 
	 */
	public function addColumns($columns) {
		$this->data['columns'] = array_merge($this->data['columns'], $items);
	}
	
	/**
	 * Add parameters to model for the future.
	 */
	public function addModelParams($name, $value) {
		$this->modelParams[$name] = $value;
	}
	

	/**
	 * Assign parameters to model.
	 */
	public function assignModelParams() {
		$this->getDm()->setLoadParams($this->modelParams);
	}
	

	/**
	 * Loads subitems (items depended on items on the higher level)
	 * Used by multi-level collections
	 */
	private function loadSubItems() {
		if ($this->subCollection) {
			$foreignKeyParent = $this->foreignKeyParent;
			$itemIdArray = array();
			if (is_array($this->data['items'])) {
				foreach ($this->data['items'] as $i) {
					$itemIdArray[$i->id] = $i;
				}
			}
			
			// loading the sub collection 
			$this->subCollection->loadCollection(array_keys($itemIdArray));
			if (is_array($this->subCollection->data['items'])) {
				foreach ($this->subCollection->data['items'] as $subItem) {
					if (array_key_exists($subItem->$foreignKeyParent, $itemIdArray)) {
						$i = $itemIdArray[$subItem->$foreignKeyParent];
						$i->subItems[] = $subItem;
					}
				}
			}
		}
	}
	
	
	/**
	 * Returns identifier of the list.
	 */
	public function getIdentifier() {
		return $this->identifier;
	}	
	
	
	/**
	 * Adds links to the existing paging structure.
	 */
	private function addPagingLinks() {
		if ($this->limit['limit'] > 0) {
			$paging = new Paging($this->limit['limit']);
			$this->data['paging'] = $paging->getStructure($this->identifier, $this->data['itemsCount']);
			foreach (array('first', 'prev', 'next', 'last', 'prevList', 'nextList', 'actual') as $key) {
				if (is_array($this->data['paging'][$key])) {
					foreach ($this->data['paging'][$key] as $key2 => $val2) {
						$v = $this->data['paging'][$key][$key2];
						if (!is_array($this->data['paging'][$key])) {
							$this->data['paging'][$key] = array();
						}
						$this->data['paging'][$key][$key2] = array(
								'value' => $v + 1,
								'link' => Request::getSameLink(array('paging' => array($this->identifier => $v)))
						);
					}
				} else {
					$v = $this->data['paging'][$key];
					$this->data['paging'][$key] = array(
							'value' => $v + 1,
							'link' => Request::getSameLink(array('paging' => array($this->identifier => $v)))
						); 
				}
			}
		} else {
			$this->data['paging'] = false;
		}
	}
	
	
	/**
	 * Adds links to the existing paging structure.
	 */
	private function addSortingLinks() {
		$this->sortingLinks = array();
		$sorting = $this->getSortingFromUrl();
		foreach ($this->getSortingColumns() as $column) {
			$sortingText = $column;
			if ($sorting['column'] == $column) {
				switch ($sorting['direction']) {
					default: 
					case SORTING_ASC: 
						$sortingText .= '-' . SORTING_DESC; 
						break;
					case SORTING_DESC: 
						$sortingText .= '-' . SORTING_ASC; 
						break;
				}
			} else {
				$sortingText .= '-' . SORTING_ASC; 
			}
			$this->sortingLinks[$column] = array(
				'name' => $column,
				'label' => ($this->getDm()->hasMetaData($column) ? $this->getDm()->getMetaData($column)->getLabel() : $column),
				'link' => Request::getSameLink(array('sorting' => array($this->identifier => $sortingText))),
				'direction' => (($sorting['column'] == $column) ? $sorting['direction'] : ''),
				'active' => ($sorting['column'] == $column),
			);
		}
	}
	
	
	/**
	 * Analyze url against sorting parameter.
	 * @return array false or column name and direction or only column
	 */
	public function getSortingFromUrl() {
		$sorting = false;
		if (isset(Request::$get['sorting']) && isset(Request::$get['sorting'][$this->identifier]) && ($sorting = Request::$get['sorting'][$this->identifier])) {
			$sorting = explode('-', $sorting);
		}
		$result = false;
		if (is_array($sorting) && isset($sorting[0]) && in_array($sorting[0], $this->getSortingColumns())) {
			$result = array('column' => $sorting[0]);
			if (isset($sorting[1]) && in_array($sorting[1], array(SORTING_ASC, SORTING_DESC))) {
				$result['direction'] = $sorting[1];
			} else {
				$result['direction'] = '';
			}
		} else {
			$result['column'] = '';
		}
		return $result;
	}
	
	
	/**
	 * Parse url and sets the filters to $filters attribute
	 * @return array $filters same format as qualification
	 *                        Example:
	 *                        array('category' => array('category = ?' => 1))
	 */
	private function getFiltersFromUrl() {
		$form = new Form();
		$form->setIdentifier('filterForm');
		$form->setNoRedirect(true);
		$form->setMethod('get');
		$form->fill($this->filtersSettingsModel);
		$form->handleRequest();
		$this->addQualification($this->filtersSettingsModel->getQualifications());
		$this->filterForm = $form->toArray();
	}
	
	
	/**
	 * Handles filters
	 */
	public function handleFilters() {
		$this->getFiltersFromUrl();
	}
	
	
	/**
	 * Returns filters settings avaible
	 */
	private function getFilters() {
		return $this->filtersSettingsModel;
	}
	
	
	/**
	 * Sets filters using specification in $filters
	 * @param array $filters filters specification
	 */
	public function setFilters($filters) {
		$this->filtersSettingsModel = $filters;
	}
	
	
	/**
	 * Sets the filters automaticly
	 */
	public function setDefaultFilters() {
		$this->filtersSettingsModel = new BaseFiltersModel();
		foreach ($this->getDm()->getVisibleColumnsInCollection() as $column) {
			$type = false;
			$restrictions = 0;
			$condition = '__field__ = ?';	// standard condition
			$valueAdjust = '__value__';		// no value adjust
			$options = array();
			switch ($this->getDm()->getMetaData($column)->getType()) {
			case Form::FORM_ID:
			case Form::FORM_INPUT_NUMBER:
				$type = Form::FORM_INPUT_TEXT;
				$restrictions = Restriction::R_NUMBER | Restriction::R_EMPTY;
				break;
			case Form::FORM_SELECT:
				$type = Form::FORM_SELECT;
				$restrictions = Restriction::R_NUMBER | Restriction::R_EMPTY;
				$options = array();
				$options = array_merge($options, $this->getDm()->getMetaData($column)->getOptions());
				break;
			case Form::FORM_INPUT_TEXT:
			case Form::FORM_TEXTAREA:
			case Form::FORM_HTML:
			case Form::FORM_HTML_BBCODE:
				$type = Form::FORM_INPUT_TEXT;
				$restrictions = 0;
				//$restrictions = Restriction::R_TEXT;
				$condition = 'CONVERT(__field__ USING utf8) LIKE ?';
				$valueAdjust = '%__value__%';
				break;
			case Form::FORM_CHECKBOX:
				$type = Form::FORM_SELECT;
				$restrictions = Restriction::R_NUMBER | Restriction::R_EMPTY;
				$options = array(
					array('id' => '1', 'value' => tg('Yes')),
					array('id' => '0', 'value' => tg('No')),
					);
				break;
			case Form::FORM_RADIO:
			case Form::FORM_SELECT_FOREIGNKEY:
			case Form::FORM_MULTISELECT_FOREIGNKEY:
			case Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE:
			case Form::FORM_INPUT_DATETIME:
			case Form::FORM_INPUT_DATE:
			case Form::FORM_INPUT_TIME:
			case Form::FORM_INPUT_IMAGE:
			default:
				// not implemented yet
				break;
			}
			if ($type) {
				$this->filtersSettingsModel->addMetaData(ModelMetaItem::create($column)
					->setLabel($this->getDm()->getMetaData($column)->getLabel())
					->setDescription($this->getDm()->getMetaData($column)->getDescription())
					->setRestrictions($restrictions)
					->setType($type)
					->setValueConditionPattern($condition)
					->setValueAdjustPattern($valueAdjust)
					->setOptions($options));
				$this->filtersSettingsModel->values[$column] = '';
			}
		}
	}
	

	/**
	 * Adds buttons to every item in the collection.
	 */
	public function addButtons($buttons=array()) {
		$this->buttons = $buttons;
		$this->buttonsProceed();
	}
	
	
	/**
	 * Adds buttons to every item in the collection.
	 */
	public function buttonsProceed() {
		$this->buttonsProceeded = true;
		$buttonsEnable = false;
		if (is_array($this->data['items']) && count($this->data['items'])) {
			foreach ($this->data['items'] as $key => $item) {
				$buttonsSet = array();
				foreach ($this->buttons as $buttonType => $action) {
					if ($this->sortable || ($buttonType != ItemCollection::BUTTON_MOVEUP && $buttonType != ItemCollection::BUTTON_MOVEDOWN)) {
						// nasty hack to insert a new value instead edditing when id is -1
						// this is valid only by config now, but has to be solve generally somehow
						$actionLink = $action;
						$params = array('paging' => PRESERVE_VALUE, 'order' => PRESERVE_VALUE);
						if ($action == 'actionEdit' && $item->id == -1) {
							$actionLink = 'actionNew';
							$params['_pred_'] = array('key' => $item->key);
						}
						
						$buttonsSet[] = array(
							'link' => Request::getLinkItem($this->controller->package, $this->controller, $actionLink, $item, $params),
							'action' => $action,
							'button' => $buttonType);
					}
				}
				if (!empty($buttonsSet)) {
					$this->data['items'][$key]->setButtonSet($buttonsSet);
					$buttonsEnable = true;
				}
			}
			if ($buttonsEnable) {
				$this->data['columns'][] = 'buttonsSet';
			}
		}
	}
	
	
	/**
	 * Adds links to items in the collection.
	 * Parameters will specify the action of the link.
	 */
	public function addLinks($controller=null, $action=null, $attribute=null) {
		if (!$controller) {
			$controller = $this->controller;
		}
		$package = $controller->package;
		if (is_array($this->data['items']) && count($this->data['items'])) {
			if ($attribute !== null) {
				$this->linkAttribute = $attribute;
			}
			if ($this->linkAction === null) {
				$this->linkAction = $action;
			}
			if ($this->linkAction === null) {
				throw new Exception('Action not specified.');
			}
			foreach ($this->data['items'] as $key => $item) {
				$item->addNonDbProperty($this->linkAttribute);
				$a = $this->linkAttribute;
				$item->$a = Request::getLinkItem($package, $controller, $this->linkAction, $item);
			}
			if ($this->subCollection) {
				$this->subCollection->addLinks();
			}
		}
	}
	
	
	/**
	 * This will store future adding of links in the collection.
	 */
	public function setLinks($action=null, $attribute='link') {
		$this->linkAction = $action;
		$this->linkAttribute = $attribute;
	}
	
	
	/**
	 * Returns the collection as an array. Used to assign the collection data structure 
	 * to the smarty template variable.
	 */
	public function getData() {
		if (!$this->buttonsProceeded) {
			$this->buttonsProceed();
		}
		return $this->data;
	}
	
	/**
	 * Adds an subcollection
	 * @param 
	 */
	public function addSubCollection($subCollection) {
		$this->subCollection = $subCollection;
	}
	
	
	/**
	 * Recursively creates copies of the collection and connects them to the current collection as a subCollection.
	 * @param 
	 */
	public function setTreeHigh($treeHigh) {
		if ($treeHigh > 1) {
			$newCollection = new self($this->identifier, $this->controller, $this->dataModel, $this->dataModelMethod, $this->buttons, $treeHigh-1, $this->linkAction);
			$this->subCollection = $newCollection;
		}
	}
	
	
	/**
	 * Makes a list prepared into the select tag and displaying as tree.
	 * Works recursively.
	 * This is initiation of the recursion.
	 */
	public function toSimpleSelectTree() {
		$output = array();
   		$output[] = array('id' => 0, 'value' => 'Top');
   		$this->toSimpleSelectTreeLevel($this->data['items'], $output, 0, false);
   		return $output;
	}
	

	/**
	 * Makes a list prepared into the select tag and displaying as tree.
	 * Works recursively.
	 */
	public function toSimpleSelectTreeLevel(&$items, &$output, $indent, $disabled) {
		$requestAction = Request::getRequestAction();
		$indentStr = $this->getIndent($indent);
		if ($items) {
			foreach ($items as $i) {
				$newItem = array('id' => $i->id, 'value' => $indentStr . $i->makeSelectTitle());
				$newDisabled = ($disabled || $requestAction['item'] && get_class($requestAction['item']) == get_class($i) && $requestAction['item']->id == $i->id);
				if ($newDisabled) {
					$newItem['disabled'] = true;
				}
				$output[] = $newItem;
				if ($i->subItems) {
					$this->toSimpleSelectTreeLevel($i->subItems, $output, $indent+1, $newDisabled);
				}
			}
		}
   	}	
   	
   	
	/**
	 * Returns intent of the lines in tree in select
	 * @param int $indent
	 * @return string
	 */
	public function getIndent($indent) {
		return str_repeat('&nbsp;', 4 * $indent) . (($indent > 0) ? '!-' : '');
	}

	
	/**
	 * We can overriden standard qualifications of the items by this function.
	 * @param array $qualification array of filtername => array filter => value
	 *                             Example:
	 *                             array('category' => array('category = ?' => 1))
	 */
	public function setQualification($qualification) {
		$this->qualification = $qualification;
	}

	
	/**
	 * We add some qualifications of the items by this function.
	 * @param array $qualification
	 * @see setQualification()
	 */
	public function addQualification($qualification) {
		if (!is_array($this->qualification) || !is_array($qualification)) {
			$this->qualification = $qualification;
		} else {
			$this->qualification = array_merge($this->qualification, $qualification);
		}
	}

	
	/**
	 * We can overriden standard sorting of the items by this function.
	 * @param string $sorting column name/s with/out DESCfdd
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	
	/**
	 * We can set sortingColumns, which items will be sorted by
	 * @param array $sortingColumns column names
	 */
	public function setSortingColumns($sortingColumns=array()) {
		$this->sortingColumns = $sortingColumns;
	}

	
	/**
	 * Getter for sortingColumns, which items will be sorted by
	 * @return array $sortingColumns column names
	 */
	public function getSortingColumns() {
		if ($this->sortingColumns === false) {
			return $this->getDm()->getVisibleColumnsInCollection($this->identifier);
		}
		if ($this->sortingColumns === null) {
			return array();
		}
		return $this->sortingColumns;
	}

	
	/**
	 * Getter for sorting links
	 * @return array $sortingColumns column names and properties
	 */
	public function getSortingLinks() {
		if ($this->sortingLinks === false) {
			$this->addSortingLinks();
		}
		return $this->sortingLinks;
	}

	
	/**
	 * We can overriden standard limit of the items by this function.
	 * @param int $limit count of the items
	 */
	public function setLimit($limit) {
		$this->limit = $limit;
	}
	
	
	/**
	 * Set the language to get items in.
	 * @param int $lang id of the lang
	 */
	public function forceLanguage($lang) {
		$this->forceLanguage = $lang;
	}

	
	/**
	 * Removes refereneces to large obejcts and returns clone of the object.
	 * Used before serialization, some large parts not needed to be serialized.
	 */
	public function removeNeedlessParts() {
		$clone = clone $this;
		$clone->controller = null;
		return $clone;
	}
	
	
	public function toLinkCollection() {
	
	}
	
	
	/**
	 * Enables or disables using Ajax by paging.
	 * @param <bool> $value
	 */
	public function setPagingAjax($value) {
		$this->pagingAjax = $value;
	}
	

	/**
	 * Sets base item or root
	 * @param <object> $baseItem
	 */
	public function treeBase($baseItem) {
		$this->treeBase = $baseItem;
	}
	

	/**
	 * Sets what items to pull in tree
	 * @param <int> $pullSet
	 */
	public function treePull($pullSet) {
		$this->treePull = $pullSet;
	}
	
	
}

?>
