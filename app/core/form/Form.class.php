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
 * Class used to define and handle Form.
 * All possible form field types and all value constraints are defined here.
 * @var <array> $fields fields in the form
 * @var <array> $buttons buttons in the form
 * @var <string> $method method used in form tag
 * @var <string> $action action (empty string almost allways)
 * @var <string> $label label of the form
 * @var <string> $description a little more specification of the form
 * @var <object> $dataModel model to be displayed in the form - defines fields
 * @var <object> $newDataModel tmp variable when inserting new item
 * @var <object> $req Request object
 * @var <array> $messages Error messages - while controlling user's input
 * @var <array> $predefinedValues values prefilled in the form
 * @var <string> $identifier id of the form - must be unique in the page
 * @var <bool> $useCaptcha classic captcha, does not work good enough
 * @var <bool> $useRecaptcha using of reCaptcha project, works good now
 * @var <bool> $useSendMail send an e-mail after submitting the form
 * @var <array> $email e-mail addresses array to send an e-mail too
 * @var <array> $alternativeActions actions, that can be selected by user after form submitting
 * @var <bool> $noRedirect No redirect after submiting
 * @var <array> $actionAccomplished form has been sent
 * @var <string> $saveAsAction Action to redirect before sending when pressed 'save as' button
 * @var <string> $sendAjax Set to true if form should be send with ajax
 * @see Restrictions.class.php
 */
class Form {
	
	const FORM_INPUT_NUMBER = 1;
	const FORM_INPUT_TEXT = 2;
	const FORM_TEXTAREA = 3;
	const FORM_HTML = 4;
	const FORM_RADIO = 5;
	const FORM_CHECKBOX = 6;
	const FORM_BUTTON_SUBMIT = 7;
	const FORM_BUTTON_CANCEL = 8;
	const FORM_SELECT = 9;
	const FORM_MULTISELECT = 10;
	const FORM_SELECT_FOREIGNKEY = 11;
	const FORM_MULTISELECT_FOREIGNKEY = 12;
	const FORM_INPUT_PASSWORD = 13;
	const FORM_ID = 14;
	const FORM_INPUT_DATETIME = 15;
	const FORM_INPUT_DATE = 16;
	const FORM_INPUT_TIME = 17;
	const FORM_INPUT_IMAGE = 18;
	const FORM_HTML_BBCODE = 19;
	const FORM_HIDDEN = 20;
	const FORM_CAPTCHA = 21;
	const FORM_RECAPTCHA = 22;
	const FORM_INPUT_FILE = 23;
	const FORM_BUTTON_CLEAR = 24;
	const FORM_BUTTON_SAVE = 25;
	const FORM_COLOR_RGBHEXA = 26;
	const FORM_RADIO_FOREIGNKEY = 27;
	const FORM_LINK = 28;
	const FORM_UPLOAD_FILE = 29;
	const FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE = 30;
	const FORM_BUTTON_SAVE_AS = 31;
	const FORM_BUTTON_SEND = 32;
	const FORM_CAPTCHA_TIMER = 33;
	const FORM_BUTTON_BACK = 34;

	const FORM_CUSTOM = 1001;
	
	const TAB_BASIC = '1_basic';
	const TAB_PROPERTIES = '2_properties';
	const TAB_SEO = '3_seo';
	const TAB_ADVANCED = '4_advanced';
	const TAB_NOT_IN_TAB = '999_not_in_tab';
	
	var $fields;
	var $buttons;
	var $method='post';
	var $action='';
	var $label='';
	var $description='';
	var $dataModel=null;
	var $newDataModel=null;
	var $req=null;
	var $messages=array();
	var $predefinedValues=array();
	var $identifier = 'form';
	var $useCaptcha = false;
	var $useRecaptcha = false;
	var $useCaptchaTimer = false;
	var $useSendMail = false;
	var $email = array();
	var $alternativeActions = array();
	var $noRedirect = false;
	var $actionAccomplished = false;
	var $useTabs = false;
	var $tabs = array();
	var $displayFormOnAccomplished = true;
	var $allowDependencies = array(); // TODO: allow to add items in selector
	var $saveAsAction=false;
	var $sendAjax=false;
	var $csrf=false;
	var $focusFirstItem = false;
	var $steps=1;
	var $step=1;
	var $buttonsList = array();
	
	/**
	 * Constructor, first timestamp will be set.
	 */
	public function __construct() {
		$this->fields = array();
		$this->buttons = array();
		$this->getActionAccomplished();
		$this->action = Request::getSameLink(array('accomplished' => false, '_pred_' => null));
		$this->predefinedValues = isset(Request::$get['_pred_']) ? Request::$get['_pred_'] : array();
		
		// default mail settings
		$this->email['from'] = 'email';
		$this->email['to'] = Config::Get('DEFAULT_EMAIL');
		$this->email['reply'] = '';
		$this->email['subject'] = 'Message from web';
		$this->email['package'] = 'Base';
		$this->email['theme'] = 'Common';
		$this->email['tplName'] = 'formEmail';
	}
	
	
	/**
	 * Gets identifier
	 * @return string identifier
	 */
	public function getIdentifier() {
		return $this->identifier;
	}
	
	
	/**
	 * Sets identifier
	 * @param string identifier
	 */
	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
	
	
	/**
	 * Gets copyAction
	 * @return string copyAction
	 */
	public function getSaveAsAction() {
		return $this->saveAsAction;
	}
	
	
	/**
	 * Sets copyAction
	 * @param string copyAction
	 */
	public function setSaveAsAction($saveAsAction) {
		$this->saveAsAction = $saveAsAction;
	}
	
	public function setFocusFirstItem($focusFirstItem) {
		$this->focusFirstItem = $focusFirstItem;
	}
	
	
	/**
	 * Returns true if copyAction is set
	 * @param string copyAction
	 */
	public function hasSaveAsAction() {
		return ($this->saveAsAction !== false);
	}
	
	
	/**
	 * Gets displayFormOnAccomplished
	 * @return bool displayFormOnAccomplished
	 */
	public function getDisplayFormOnAccomplished() {
		return $this->displayFormOnAccomplished;
	}
	
	
	/**
	 * Sets displayFormOnAccomplished
	 * @param bool displayFormOnAccomplished
	 */
	public function setDisplayFormOnAccomplished($displayFormOnAccomplished) {
		$this->displayFormOnAccomplished = $displayFormOnAccomplished;
	}
	
	
	/**
	 * Adds fields from model, where properties are defined
	 * Can be override
	 */
	public function addFieldsFromDataModel($dataModel) {
	}

	
	/**
	 * Returns JS to init tabs.
	 */
	private function getTabsInitJS() {
		if (!$this->getUseTabs()) {
			return null;
		}
		$tabs = $this->getTabsContent();
		return Javascript::addTabs($this->getTabsContainerId(), isset($tabs[0]) ? $tabs[0]['id'] : self::TAB_BASIC);
	}

	
	/**
	 * Returns Tabs' container Id.
	 */
	private function getTabsContainerId() {
		return $this->identifier . '_tabs_cont';
	}

	
	/**
	 * Returns array of tabs with fields assigned to apropriate tab.
	 * If no tabs are used, null is returned.
	 * Returned array is composed of arrays with keys id, label, fields
	 */
	private function getTabsContent() {
		if (!$this->getUseTabs()) {
			// if we don't use tabs
			return null;
		} 
		if ($this->tabs) {
			// if already generated, just return it
			return $this->tabs;
		}
		// generate tabs
		$tmpTabs = array();
		foreach ($this->getFields() as $field) {
			$tabId = $field->getMeta()->getFormTab() ? $field->getMeta()->getFormTab() : self::TAB_BASIC;
			if (!array_key_exists($tabId, $tmpTabs)) {
				$tmpTabs[$tabId] = array(
					'id' => $tabId,
					'inTab' => ($tabId != self::TAB_NOT_IN_TAB),
					'label' => 'Tab ' . preg_replace('/^\d+_/', '', $tabId),
					'fields' => array(),
					);
			}
			$tmpTabs[$tabId]['fields'][] = $field;
		}
		// we want to sort tabs by the ID - so id should be something 
		// like 1_general, 2_url, 3_options, ...
		$tabs = array_keys($tmpTabs);
		sort($tabs);
		foreach ($tabs as $tabId) {
			$this->tabs[] = $tmpTabs[$tabId];
		}
		return $this->tabs;
	}
	
	
	/**
	 * Converts the form into array, that can be used in the template.
	 * TODO: shoudl be renamed to export
	 */
	public function toArray() {
		return array(
			'fields' => $this->getFields(), 
			'tabs' => $this->getTabsContent(), 
			'tabContainerId' => $this->getTabsContainerId(),
			'tabsInitJS' => $this->getTabsInitJS(),
			'buttons' => $this->buttons, 
			'action' => $this->action, 
			'method' => $this->method,
			'label' => $this->label,
			'issent' => $this->getIsSent(),
			'description' => $this->description,
			'messages' => $this->messages,
			'actionAccomplished' => $this->actionAccomplished,
			'formHasCompulsoryFields' => $this->getFormHasCompulsoryFields(),
			'displayForm' => !$this->actionAccomplished || $this->displayFormOnAccomplished,
			'messagesFromBus' => MessageBus::popMessages($this->identifier),
			'identifier' => $this->identifier,
			'sendAjax' => $this->sendAjax,
			'focusFirstItem' => $this->focusFirstItem,
			'steps' => $this->steps,
			'step' => $this->step,
			);
	}
	
	
	/**
	 * Returns true if form has some compulsory fields.
	 */
	private function getFormHasCompulsoryFields() {
		foreach ($this->dataModel->getMetadata() as $field) {
			if (Restriction::hasRestrictions($field->getRestrictions(), Restriction::R_NOT_EMPTY)) {
				return true;
			}
		}
	}
	
	
	/**
	 * Initializes the form using $item
	 * @param object $item DataModel item.
	 * @param array $buttonsList List of button types, that should be added.
	 */
	public function fill(&$item, $buttonsList=null) {
		$this->dataModel = $item;
		$this->dataModel->setPredefinedValues($this->predefinedValues);
		$this->updateValuesFromDataModel();
		$this->addButtons($buttonsList);
	}


	/**
	 * This method will get values from dataModel and sets them in the $this->fields array.
	 * It is called even when form is not sent.
	 * $this->fields is used in the HTML generating.
	 */
	private function updateValuesFromDataModel() {
		$this->fields = array();
		$modelName = $this->dataModel->name;
		
		// standard columns
		foreach ($this->dataModel->getMetadata() as $fieldName => $meta) {
			if (!$meta) {
				//throw new Exception ("Field $fieldName has not metadata defined.");
				throw new Exception("Field $fieldName has not metadata defined.");
			}
			
			$isChangeAble = $meta->isChangeAble(isset($this->dataModel->id) ? $this->dataModel->id : null);
			$isVisibleInForm = $meta->getIsVisibleInForm(isset($this->dataModel->id) ? $this->dataModel->id : null);
			
			if (!$isChangeAble && !$isVisibleInForm) {
				// if we cannot edit the field
				continue;
			}
			if (array_key_exists($fieldName, $this->predefinedValues) && !$isVisibleInForm) {
				// if the field is preset, we cannot edit the field
				continue;
			}
			$field = FormFieldFactory::getInstance($meta->getType(), $this->identifier);
			$field->setMeta($this->dataModel->getMetaData($fieldName));
			$field->setDataModel($this->dataModel);
			$field->setValue($this->dataModel->getValue($fieldName));
			
			// special behaviour of the 1:many relation
			if (in_array($meta->getType(), array(self::FORM_RADIO_FOREIGNKEY, self::FORM_SELECT_FOREIGNKEY))) {
				$listMethodName = $meta->getOptionsMethod();
				$foreignModelName = $this->dataModel->getRelationModel($fieldName);
				if (!class_exists($foreignModelName)) throw new Exception("Class '$foreignModelName' used by field '$fieldName' is not defined.");
				$foreignModel = new $foreignModelName();
				$field->setOptions($foreignModel->$listMethodName());
			}
			
			// special behaviour of the many:many relation
			if (in_array($meta->getType(), array(self::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE))) {
				$listMethodName = $meta->getOptionsMethod();
				$foreignModelName = $meta->getOptionsModel();
				//$connectorModelName = $this->dataModel->relations[$foreignModelName]->connectorClass;
				//$connectorModel = new $connectorModelName();
				$field->setValue(array());
				if ($this->dataModel->id) {
					$relatedItems = $this->dataModel->Find($foreignModelName, array(), array(), array(), array("id"));
					if (is_array($relatedItems)) {
						foreach ($relatedItems as $item) {
							$field->addValue($item->id);
						}
					}
				}
				$foreignModel = new $foreignModelName();
				$field->setOptions($foreignModel->$listMethodName());
			}
			
			if (in_array($meta->getType(), array(self::FORM_MULTISELECT))) {
				$field->setValue(array());
				$values = $this->dataModel->getValue($fieldName);
				foreach (explode(';', $values) as $id) {
					$field->addValue($id);
				}
			}
			$this->fields[] = $field;
		}

		// properties handling
		$propModel = $this->dataModel->getPropertiesModel();
		if ($propModel) {
			$properties = $propModel->getPropertiesItem($this->dataModel);
			foreach (array_keys($propModel->properties) as $fieldName) {
				if (!isset($propModel->properties[$fieldName])) {
					//throw new Exception ("Field $fieldName has not metadata defined.");
					throw new Exception("Property $fieldName has not metadata defined.");
				}
				$field = $propModel->properties[$fieldName];
				if ($properties) {
					foreach ($properties as $propItem) {
						if ($propItem->value_name == $fieldName) {
							switch ($propItem->value_type) {
							case AbstractPropertiesModel::VALUE_NUMBER: $field->setValue($propItem->value_number); break;
							case AbstractPropertiesModel::VALUE_STRING: $field->setValue($propItem->value_string); break;
							case AbstractPropertiesModel::VALUE_DATETIME: $field->setValue($propItem->value_datetime); break;
							default: break;
							}
						}
					}
				}
				$this->fields[] = $field;
			}
		}
	}


	/**
	 * This will take values from request ($post or $get array, depends on form
	 * configuration) and saves them in the own property.
	 */
	private function updateValuesFromReq() {
		foreach ($this->fields as $name => $field) {
			$metaName = $field->getMeta()->getName();
			$this->fields[$name]->setValue(isset($this->req[$metaName]) ? $this->req[$metaName] : null);
			$this->fields[$name]->setErrorMessage((isset($this->messages['errors'][$metaName]) && $this->messages['errors'][$metaName]) ? $this->messages['errors'][$metaName] : '');
			$this->fields[$name]->setWarningMessage((isset($this->messages['warnings'][$metaName]) && $this->messages['warnings'][$metaName]) ? $this->messages['warnings'][$metaName] : '');
		}
	}
	
	
	/**
	 * This will take values from request ($post or $get array, depends on form
	 * configuration) and saves them in the own property.
	 */
	private function updateValuesFromGet() {
		foreach ($this->fields as $name => $field) {
			if (isset(Request::$get['_pred_'][$field->getMeta()->getName()])) {
				$this->fields[$name]->setValue(Request::$get['_pred_'][$field->getMeta()->getName()]);
			}
		}
	}
	
	
	/**
	 * Adds specificated buttons to the form, if $buttonsList is null, standard buttons are added.
	 * @param array $buttonsList List of button types, that should be added.
	 */
	public function addButtons($buttonsList=null) {
		$this->buttonsList = ($buttonsList === null) ? array(self::FORM_BUTTON_SUBMIT, self::FORM_BUTTON_CANCEL) : $buttonsList;
		
		if ($this->hasSaveAsAction()) {
			$this->buttonsList[] = self::FORM_BUTTON_SAVE_AS;
		}

		if ($this->steps > 1) {
			$this->buttonsList[] = self::FORM_BUTTON_BACK;
		}
	}
	
	private function parseButtonList() {
		$this->buttons = array();
		foreach ($this->buttonsList as $button) {
			switch ($button) {
				case self::FORM_BUTTON_SUBMIT:
					$this->buttons[] = array('name' => 'submit', 'value' => 'Submit', 'type' => self::FORM_BUTTON_SUBMIT);
					break;
				case self::FORM_BUTTON_SEND:
					$this->buttons[] = array('name' => 'send', 'value' => 'Send', 'type' => self::FORM_BUTTON_SEND);
					break;
				case self::FORM_BUTTON_SAVE:
					$this->buttons[] = array('name' => 'save', 'value' => 'Save', 'type' => self::FORM_BUTTON_SAVE);
					break;
				case self::FORM_BUTTON_CANCEL:
					$b = array('name' => 'cancel', 'value' => 'Cancel', 'type' => self::FORM_BUTTON_CANCEL);
					if (Request::isAjax())
						$b['onclick'] = 'Dialog.closeInfo(); return false;';
					$this->buttons[] = $b;
					break;
				case self::FORM_BUTTON_CLEAR:
					$this->buttons[] = array('name' => 'clear', 'value' => 'Clear', 'type' => self::FORM_BUTTON_CLEAR);
					break;
				case self::FORM_BUTTON_SAVE_AS:
					$this->buttons[] = array('name' => 'saveas', 'value' => 'Save As', 'type' => self::FORM_BUTTON_SAVE_AS, 'action' => $this->getSaveAsAction());
					break;
				case self::FORM_BUTTON_BACK:
					if ($this->step > 1)
						$this->buttons[] = array('name' => 'back', 'value' => 'Back', 'type' => self::FORM_BUTTON_BACK);
					break;
				default: break;
			}
		}
	}


	/**
	 * Sets the form label.
	 * @param string $label Label of the form
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	
	/**
	 * Gets the form option - if tabs should be used.
	 * @return bool True if tabs should be used.
	 */
	public function getUseTabs() {
		return $this->useTabs;
	}

	
	/**
	 * Sets the form option - if tabs should be used.
	 * @param string $useTabs True if tabs should be used.
	 */
	public function setUseTabs($useTabs) {
		$this->useTabs = $useTabs;
	}

	
	/**
	 * Sets the noRedirect property
	 * @param bool $noRedirect noRedirect property value
	 */
	public function setNoRedirect($noRedirect) {
		$this->noRedirect = $noRedirect;
	}

	
	/**
	 * Sets the method
	 * @param string $method method (post, get)
	 */
	public function setMethod($method) {
		$this->method = $method;
	}
	
	/**
	 * Sets the form description.
	 * @param string $label Description of the form
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	
	/**
	 * Sets the predefined value.
	 * These values cannot be edited any more.
	 * @param string $field DataModel field
	 * @param mixed $value Value 
	 */
	public function addPredefinedValue($field, $value) {
		$this->predefinedValues[$field] = $value;
	}
	
	
	/**
	 * Returns true if this form has been sent.
	 */
	private function getIsSent() {
		$action = $this->getAction();
		return $action == self::FORM_BUTTON_SUBMIT 
			|| $action == self::FORM_BUTTON_SEND 
			|| $action == self::FORM_BUTTON_SAVE 
			|| $action == self::FORM_BUTTON_SAVE_AS;
	}
	
	/**
	 * Returns true if this form has been sent using back button.
	 */
	private function getIsSentBack() {
		$action = $this->getAction();
		return $action == self::FORM_BUTTON_BACK;
	}
	
	/**
	 * Returns true if this form has not been sent, but pressed Cancel.
	 */
	private function getCancelIsSent() {
		$action = $this->getAction();
		return $action == self::FORM_BUTTON_CANCEL;
	}
	
	private function redirect($link) {
		if ($this->sendAjax) {
			echo json_encode(array('form_result' => 'OK', 'redirect' => $link));
			Request::finish();
		}
		return Request::redirect($link);
	}	
	
	/**
	 * Handling the form is done as follows:
	 * 1) get values from request
	 * 2) update fields if needed (checkbox is true if isset() is true, etc.)
	 * 3) check values of the fields
	 * 4) handle the database changes if no errors and if needed
	 * @param <array> $actionAfterHandling array of structure (pacakge, controller, action, item) where user should be redirected to after submitng. Keys are 'all' or a button_id (see Form class).
	 * @param <string> $confirmationMessage Message to be displayed to user
	 */
	public function handleRequest($actionAfterHandling=array(), $confirmationMessage=null) {
		if ($this->method == "post") {
			$this->req = &Request::$post;
		} else {
			$this->req = &Request::$get;
		}
		$this->step = isset($this->req['form_step']) ? (int)$this->req['form_step'] : $this->step;
		$this->parseButtonList();
		if ($this->getIsSentBack()) {
			$this->decreaseStep();
			$this->noRedirect = true;
		} elseif ($this->getIsSent()) {
			if (isset($this->req["form_action"]) && is_numeric($this->req["form_action"])) {
				$this->storeAlternativeAction($this->req["form_action"]);
			}
			$this->checkFields();
			if (!count($this->messages["errors"])) {
				// handle form in more steps
				if ($this->steps > 1 && $this->step < $this->steps) {
					$this->increaseStep();
					$this->noRedirect = true;
				} else {
					
					// Saving...
					$this->updateDataModelFromRequest();
					
					// and sending message
					MessageBus::sendMessage($confirmationMessage ? $confirmationMessage : tg('Form has been sent.'), false, $this->identifier);
					
					// mail sending
					if ($this->useSendMail) {
						$this->sendMail();
					}
				}

				// redirection
				if ($this->noRedirect) {
					// no redirection is set
					$this->updateValuesFromReq();
				} else {
					// next action is selected by form
					if (isset($this->req["form_action"]) && $this->req["form_action"] && array_key_exists($this->req["form_action"], $this->alternativeActions)) {
						$action = $this->alternativeActions[$this->req["form_action"]];
						if ($action["item"]) {
							$this->redirect(Request::getLinkItem($action["package"], $action["controller"], $action["method"], $action["item"]));
						} else {
							$this->redirect(Request::getLinkSimple($action["package"], $action["controller"], $action["method"]));
						}
					
					// default action is used (button is specified)
					} elseif ($actionAfterHandling && isset($actionAfterHandling[$action])) {
						$this->redirect(Request::getLinkItem(
							$actionAfterHandling[$action]['package'], 
							$actionAfterHandling[$action]['controller'], 
							$actionAfterHandling[$action]['action'], 
							(isset($actionAfterHandling[$action]['item']) ? $actionAfterHandling[$action]['item'] : $this->dataModel), 
							array('accomplished' => false)));
						
					// default action is used (button is not specified)
					} elseif ($actionAfterHandling && isset($actionAfterHandling['all'])) {
						if (isset($actionAfterHandling['all']['args'])) {
							$this->redirect(Request::getLinkSimple(
								$actionAfterHandling['all']['package'], 
								$actionAfterHandling['all']['controller'], 
								$actionAfterHandling['all']['action'], 
								array_merge(array('accomplished' => false), $actionAfterHandling['all']['args'])));
						} else {
							$this->redirect(Request::getLinkItem(
								$actionAfterHandling['all']['package'], 
								$actionAfterHandling['all']['controller'], 
								$actionAfterHandling['all']['action'], 
								(isset($actionAfterHandling['all']['item']) ? $actionAfterHandling['all']['item'] : $this->dataModel), 
								array('accomplished' => false)));
						}
						
					// use the same action
					} else {
						if ($this->sendAjax)
							$this->redirect('');
						else
							$this->redirect(Request::getSameLink(array('accomplished' => false, '_pred_' => null)));
					}
				}
			} else {
				if ($this->sendAjax) {
					$allMessages = array();
					foreach (array('errors', 'warnings') as $type)
						foreach ($this->messages[$type] as $messages)
							$allMessages = array_merge($allMessages, $messages);
					echo json_encode(array('form_result' => 'ERROR', 'messages' => $allMessages));
					exit;
				}
				$this->updateValuesFromReq();
			}
		} else {
			if ($this->getCancelIsSent() && $actionAfterHandling && isset($actionAfterHandling['cancel'])) {
				if (isset($actionAfterHandling['cancel']['args'])) {
					$this->redirect(Request::getLinkSimple(
						$actionAfterHandling['cancel']['package'], 
						$actionAfterHandling['cancel']['controller'], 
						$actionAfterHandling['cancel']['action'], 
						array_merge(array('accomplished' => false), $actionAfterHandling['cancel']['args'])));
				} else {
					$this->redirect(Request::getLinkItem(
						$actionAfterHandling['cancel']['package'], 
						$actionAfterHandling['cancel']['controller'], 
						$actionAfterHandling['cancel']['action'], 
						(isset($actionAfterHandling['cancel']['item']) ? $actionAfterHandling['cancel']['item'] : $this->dataModel), 
						array('accomplished' => false)));
				}
			}
			$this->updateValuesFromGet();
		}
	}

	
	/**
	 * Increase actual step
	 */
	private function increaseStep() {
		if ($this->step < $this->steps)
			$this->step++;
		$this->parseButtonList();
	}

	
	/**
	 * Decrease actual step
	 */
	private function decreaseStep() {
		if ($this->step > 1)
			$this->step--;
		$this->parseButtonList();
	}
	
	
	/**
	 * Returns action (button pressed) of the form sent.
	 */
	private function getAction() {
		if (!isset($this->req['form_identifier']) || $this->req['form_identifier'] != $this->identifier) {
			return null;
		}
		if (isset($this->req['saveas'])) {
			return self::FORM_BUTTON_SAVE_AS;
		}
		foreach ($this->buttons as $button) {
			if (isset($this->req[$button['name']])) {
				return $button['type'];
			}
		}
		return null;
	}
	
	
	/**
	 * Copy values from form to data model. This is needed to use them in the form 
	 * displaying or in data in DB updating.
	 * This is the method which calls Save method in the model.
	 */
	private function updateDataModelFromRequest() {
		$metadata = $this->dataModel->getMetadata();
		foreach ($this->dataModel->getChangeAbleOrAutoFilledMetaData() as $field => $meta) {
			if ($meta->getType() == Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE) {
				continue;
			}
			if (!in_array($meta->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, Form::FORM_CUSTOM)) || $meta->getUpdateHandleDefault()) {
				if (array_key_exists($field, $this->predefinedValues)) {
					$val = $this->predefinedValues[$field];
					// we do not hash the field if it should not be changed on empty and the field is empty
					if ($val != "" || !Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NO_EDIT_ON_EMPTY)) {
						$this->dataModel->$field = $val;
	
					}
				} else {
					$val = isset($this->req[$field]) ? $this->req[$field] : "";
					// we do not hash the field if it should not be changed on empty and the field is empty
					if ($val != "" || !Restriction::hasRestrictions($meta->getRestrictions(), Restriction::R_NO_EDIT_ON_EMPTY)) {
						$this->dataModel->$field = $val;
					}
				}
			}
			if ($meta->getType() == Form::FORM_MULTISELECT && is_array($this->dataModel->$field)) {
				$this->dataModel->$field = implode(';', $this->dataModel->$field);
			}
		}
		
		
		// store changes made in item into DB
		$changes = $this->dataModel->getChanges();

		// save data in the DB or in other way (depends on the model)
		$this->dataModel->Save();
		
		// handle the relation many:many
		foreach ($this->dataModel->getChangeAbleOrAutoFilledMetaData() as $field => $meta) {
			if (in_array($meta->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE))) {
				//$this->dataModel->Save();
				$relationModelName = $meta->getOptionsModel();
				$oldItems = $this->dataModel->Find($relationModelName);
				$oldIds = array();
				$connectChange = '';
				if (is_array($oldItems)) {
					foreach ($oldItems as $oldItem) {
						// the item is no more connected
						if (array_key_exists($field, $this->req) && is_array($this->req[$field]) && !in_array($oldItem->id, $this->req[$field])) {
							$this->dataModel->Disconnect($oldItem);
							$connectChange .= "- {$oldItem->id}\n";
						} else {
							// if the item is connected and the connection should remain, store the Id for the future
							$oldIds[] = $oldItem->id;
						}
					}
				}
				if (array_key_exists($field, $this->req) && is_array($this->req[$field])) {
					foreach ($this->req[$field] as $newItemId) {
						if (!in_array($newItemId, $oldIds)) {
							// make a new connection if it is not made yet
							$this->dataModel->Connect(new $relationModelName($newItemId));
							$connectChange .= "+ $newItemId\n";
						}
					}
				}
				
				// store changes in connections
				if ($connectChange) {
					$change = new BaseChangesModel();
					$change->packagename = $this->dataModel->package;
					$change->model = $this->dataModel->getName();
					$change->item = $this->dataModel->id;
					$change->field = $field;
					$change->data = $connectChange;
					$changes[] = $change;
				}
			}
		}
		
		// handle the properties
		$propModel = $this->dataModel->getPropertiesModel();
		if ($propModel) {
			$propClass = get_class($propModel);
			$propModel->deleteAll($this->dataModel);
			foreach ($propModel->getPossibleProperties() as $field) {
				$val = isset($this->req[$field->getName()]) ? $this->req[$field->getName()] : "";
				$newProp = new $propClass();
				$relationColumn = $newProp->relations[get_class($this->dataModel)]->sourceProperty;
				$newProp->$relationColumn = $this->dataModel->id;
				$newProp->value_type = $field->getPropValueType();
				$newProp->value_name = $field->getName();
				switch ($field['value_type']) {
				case AbstractPropertiesModel::VALUE_NUMBER: $newProp->value_number = $val; break;
				case AbstractPropertiesModel::VALUE_STRING: $newProp->value_string = $val; break;
				case AbstractPropertiesModel::VALUE_DATETIME: $newProp->value_datetime = $val; break;
				default: break;
				}
				$newProp->Save();
			}
		}
		
		// store changes into DB
		foreach ($changes as $change) {
			$change->Save();
		}
	}
	
	
	/**
	 * Checks values from the user.
	 * If error, set self::addMessage - this will indicate an errror.
	 */
	private function checkFields() {
		$this->messages = $this->dataModel->checkFields($this->req, $this->predefinedValues);
		
		$this->checkCaptcha();
		$this->checkRecaptcha();
		$this->checkCaptchaTimer();
		$this->checkCsrf();
		
	}

	private function checkCsrf() {
		return !$this->csrf || $this->req['form_token'] == Request::$tokenPrevious;
	}

	/**
	 * Checks if any of captcha tests has been passed before in current session.
	 */
	private function getCaptchaPassed() {
		return isset($_SESSION['captcha_passed']) && $_SESSION['captcha_passed'];
	}
	
	
	/**
	 * Sets if captcha test has been passed in current session.
	 */
	private function setCaptchaPassed($passed=true) {
		$_SESSION['captcha_passed'] = $passed;
	}
	
	
	/**
	 * Checks classic captcha test.
	 * If error, set self::addMessage - this will indicate an errror.
	 */
	private function checkCaptcha() {
		if ($this->useCaptcha && !$this->getCaptchaPassed()) {
			require_once(DIR_LIBS . 'captcha/class/Captcha.class.php');
			if (!Captcha::checkCaptcha($this->req['form_captcha'])) {
				self::addMessage($this->messages, 'errors', 'form_captcha', tg('Anti-spam protection not passed.')); 
			} else {
				$this->setCaptchaPassed();
			}
		} 
	}
	
	
	/**
	 * Checks reCapthca test. 
	 * If error, set self::addMessage - this will indicate an errror.
	 */
	private function checkRecaptcha() {
		if ($this->useRecaptcha && !$this->getCaptchaPassed()) {
			require_once(DIR_LIBS . 'recaptcha/recaptchalib.php');
			$privatekey = "6LebUwsAAAAAALmol4cCKXCiv3I82V2oXAcXUkQ6";// private key from recaptcha.net
			$resp = recaptcha_check_answer ($privatekey,
											$_SERVER['REMOTE_ADDR'],
											$this->req['recaptcha_challenge_field'],
											$this->req['recaptcha_response_field']);
			if (!$resp->is_valid) {
				self::addMessage($this->messages, 'errors', 'recaptcha_response_field', tg('Anti-spam protection not passed.')); 
			} else {
				$this->setCaptchaPassed();
			}
		} 
	}
	
	
	/**
	 * Checks Capthca Timer test. 
	 * If error, set self::addMessage - this will indicate an errror.
	 */
	private function checkCaptchaTimer() {
		if ($this->useCaptchaTimer && !$this->getCaptchaPassed()) {
			$timestamp = Utilities::simpleDecrypt($this->req['captcha_timer_response_field'], Config::Get('CAPTCHA_TIMER_KEY'));
			if (!is_numeric($timestamp) || ((int)time() - (int)$timestamp) < (int)Config::Get('CAPTCHA_TIMER_LIMIT')) {
				self::addMessage($this->messages, 'errors', 'captcha_timer_response_field', tg('Anti-spam protection not passed. If you are not a robot, contact the webmaster: ') . Config::Get('DEFAULT_EMAIL')); 
			} else {
				$this->setCaptchaPassed();
			}
		} 
	}
	
	
	/**
	 * Adding message is used to indicate an error.
	 * @param <string> &$messages Error messages list
	 * @param <string> $type can be one of the 'errors', 'waringns'
	 * @param <string> $field name of the field
	 * @param <string> $message message to be added
	 */
	static public function addMessage(&$messages, $type, $field, $message) {
		if (!array_key_exists($field, $messages[$type])) {
			$messages[$type][$field] = array();
		}
		$messages[$type][$field][] = $message;
	}
	
	
	/**
	 * Generates message to be displayed to user after sending the form.
	 */
	private function getActionAccomplished() {
		$this->actionAccomplished = isset(Request::$get['accomplished']);
	}
	
	
	/**
	 * Returns fields to export.
	 * Includes identifier.
	 * @return array $fields from model and specific for the form
	 */
	 private function getFields() {
		
		// form identificator
		$formId = new FormFieldHidden($this->identifier);
		$formId->setValue($this->identifier);
		$formId->setMeta(AtributesFactory::create('form_identifier')
			->setType(self::FORM_HIDDEN));

		// csrf token
		$csrfToken = new FormFieldHidden($this->identifier);
		$csrfToken->setValue(Request::$tokenCurrent);
		$csrfToken->setMeta(AtributesFactory::create('form_token')
			->setType(self::FORM_HIDDEN));

		// form step
		$formStep = new FormFieldHidden($this->identifier);
		$formStep->setValue($this->step);
		$formStep->setMeta(AtributesFactory::create('form_step')
			->setType(self::FORM_HIDDEN));

		$fieldsExtra = array($formId, $csrfToken, $formStep);

		// captcha item
		if ($this->useCaptcha && !$this->getCaptchaPassed()) {
			$formCaptcha = new FormFieldCaptcha($this->identifier);
			$formCaptcha->setValue('');
			$formCaptcha->setMeta(AtributesFactory::create('form_captcha')
				->setLabel(tg('Anti-spam protection'))
				->setDescription(tg('Write out the result from the image'))
				->setType(self::FORM_CAPTCHA));
			$fieldsExtra[] = $formCaptcha;
		}
		
		// Captcha-timer item
		if ($this->useCaptchaTimer && !$this->getCaptchaPassed()) {
			$formCaptchaTimer = new FormFieldHidden($this->identifier);
			$formCaptchaTimer->setValue(Utilities::simpleEncrypt(time(), Config::Get('CAPTCHA_TIMER_KEY')));
			$formCaptchaTimer->setMeta(AtributesFactory::create('captcha_timer_response_field')
				->setType(self::FORM_HIDDEN));
			$fieldsExtra[] = $formCaptchaTimer;
		}
		
		// reCaptcha item
		if ($this->useRecaptcha && !$this->getCaptchaPassed()) {
			require_once(DIR_LIBS . 'recaptcha/recaptchalib.php');
			$publickey = '6LebUwsAAAAAAJDQornmGKt0dVL3oyIEdzgMHh66'; // public key from recaptcha.net
			$formRecaptcha = new FormFieldRecaptcha($this->identifier);
			$formRecaptcha->setValue(recaptcha_get_html($publickey));
			$formRecaptcha->setMeta(AtributesFactory::create('recaptcha_response_field')
				->setLabel(tg('Anti-spam protection'))
				->setDescription(tg('Write out the text from the image'))
				->setType(self::FORM_RECAPTCHA));
			$fieldsExtra[] = $formRecaptcha;
		}
		

		// init alternativ actions, that can be selected by user 
		// and defines where user will be redirected to after 
		// sending the form
		if (count($this->alternativeActions) > 1) {
			$options = array();
			
			foreach ($this->alternativeActions as $key => $action) {
				$options[] = array('id' => $key, 'value' => $action['title']);
			}
			$fieldAlterAct = new FormFieldSelect($this->identifier);
			$fieldAlterAct->setValue($this->getAlternativeActionKey());
			$fieldAlterAct->setOptionsFromModel(false);
			$fieldAlterAct->setMeta(AtributesFactory::create('form_action')
				->setLabel(tg('Action after submit'))
				->setDescription(tg('Select action to continue'))
				->setType(self::FORM_SELECT)
				->setOptions($options)
				->setLineClass('alternativeAction')
				->setFormTab(self::TAB_NOT_IN_TAB)
				->setOptionsMustBeSelected(true));
			$fieldsExtra[] = $fieldAlterAct;
		}
		$allFields = array_merge($this->fields, $fieldsExtra);
		foreach ($allFields as $k => $field)
			$allFields[$k]->setFormStepActual($this->step);
		return $allFields;
	}
	
	
	/**
	 * Returns the selected index in the alternative actions table
	 * @return <int> Selected index in the alternative actions table
	 */
	private function getAlternativeActionKey() {
		$keys = array_keys($this->alternativeActions);
		return (isset($_SESSION[$this->identifier . "_action"]) && is_numeric($_SESSION[$this->identifier . "_action"])) ? $_SESSION[$this->identifier . "_action"] : $keys[0];
	}
	
	
	/**
	 * Stroring the selected index in the alternative actions table
	 */
	private function storeAlternativeAction($key) {
		$_SESSION[$this->identifier . "_action"] = $key;
	}

	/**
	 * Sets using Ajax for sending data ON/OFF
	 * @param bool $sendAjax
	 */
	public function setSendAjax($sendAjax) {
		$this->sendAjax = $sendAjax;
	}

	/**
	 * Returns true if Ajax is used for sending data
	 * @return bool $sendAjax
	 */
	public function getSendAjax() {
		return $this->sendAjax;
	}
	
	/**
	 * Sets using Captcha ON/OFF
	 * @param bool $useCaptcha
	 */
	public function useCaptcha($useCaptcha) {
		$this->useCaptcha = $useCaptcha;
	}
	
	
	/**
	 * Sets using Recaptcha ON/OFF
	 * @param bool $useRecaptcha
	 */
	public function useRecaptcha($useRecaptcha) {
		$this->useRecaptcha = $useRecaptcha;
	}
	
	
	/**
	 * Sets using Captcha Timer ON/OFF
	 * @param bool $useCaptchaTimer
	 */
	public function useCaptchaTimer($useCaptchaTimer) {
		$this->useCaptchaTimer = $useCaptchaTimer;
	}
	
	
	/**
	 * Sets the sending email ON and sets additional parameters.
	 * @param $email array configuration of the email (from, to, ...)
	 */
	public function useSendMail($email=array()) {
		$this->useSendMail = true;
		foreach ($email as $key => $values) {
			$this->email[$key] = $values;
		}
	}
	
	public function setCsrf($csrf) {
		$this->csrf = $csrf;
	}
	
	/**
	 * Sends email with content from the form.
	 * @return int return value from Send() function
	 */
	private function sendMail() {
		// compose and export values from form
		$values = array();
		foreach ($this->getFields() as $field) {
			$meta = $field->getMeta();
			if (!in_array($meta->getName(), array('form_captcha', 'captcha_timer_response_field', 'recaptcha_response_field', 'form_identifier', 'form_action'))) {
				$values[$meta->getName()] = $this->req[$meta->getName()];
			}
		}
		Environment::$smarty->assign("values", $values);
		
		// make email from template
		$mailBody = Environment::$smarty->fetch("file:/" . Themes::getTemplatePath($this->email['package'], $this->email['theme'], $this->email['tplName'] . '.html'));
		$mailAltBody = Environment::$smarty->fetch("file:/" . Themes::getTemplatePath($this->email['package'], $this->email['theme'], $this->email['tplName']));

		// compose email
		$mail = new Email(); // defaults to using php "mail()"

		// use reply if set
		if ($this->email['reply']) {
			$mail->ClearReplyTos();
			foreach ($this->getEmail($this->email['reply'], $values) as $m) {
				$mail->AddReplyTo($m);
			}
		}
		
		// use from if set
		if ($this->email['from']) {
			$mailsFrom = $this->getEmail($this->email['from'], $values);
			$mail->SetFrom($mailsFrom[0]);
		}
		
		foreach ($this->getEmail($this->email['to'], $values) as $m) {
			$mail->AddAddress($m);
		}
		
		$mailSubject = tp($this->email['subject']);

		// fill basic fields
		$mail->Subject = $mailSubject;
		$mail->AltBody = $mailAltBody; 
		$mail->MsgHTML($mailBody);

		// sending the email
		$sent = $mail->Send();
		
		return $sent;
	}
	
	/**
	 * Return E-mail for specified key.
	 * If $emails is / are in email format, it is used.
	 * If $emails is key / are keys in $values array, the value is used.
	 * Values in $emails can be separated using ',' or it can be array
	 * @param string $email email or key from $values array
	 * @param array $values values filled in form
	 * @return string emails in right format or exception threw
	 */
	private function getEmail($emails, &$values) {
		if (is_string($emails)) {
			$emails = preg_split('/[,;]/', $emails);
		}
		if (!is_array($emails)) {
			throw new Exception("Wrong format of emails parameter.");
		}
		$result = array();
		foreach ($emails as $email) {
			$email = trim($email);
			if (Utilities::checkEmailFormat($email)) {
				$result[$email] = 1;
			} else if (isset($values[$email]) && Utilities::checkEmailFormat($values[$email])) {
				$result[$values[$email]] = 1;
			}
		}
		if (empty($result)) {
			throw new Exception("No email specified.");
		}
		return array_keys($result);
	}
	
	
	/**
	 * Adds an action into the alternative actions table
	 */
	public function addAlternativeAction($package, $controller, $method, $item=null, $title) {
		$this->alternativeActions[] = array(
			"package" => $package, 
			"controller" => $controller, 
			"method" => $method, 
			"item" => $item,
			"title" => $title,
			);
	}
	
	
	public function allowDependencies($metaItem, $metaItemsEditted=array()) {
		$this->allowDependencies[$metaItem->getName()] = $metaItemsEditted;
	}
		
	
	public function addFieldAttribute($fieldName, $actionType, $actionJSCode) {
		foreach ($this->fields as $k => $field) {
			if ($field->getMeta()->getName() == $fieldName) {
				$field->attributes[$actionType] = $actionJSCode;
			}
		}
	}
	
	
	/**
	 * Gets steps
	 * @return string steps
	 */
	public function getSteps() {
		return $this->steps;
	}
	
	
	/**
	 * Sets steps
	 * @param string steps
	 */
	public function setSteps($steps) {
		$this->steps = $steps;
	}
	
	
}

?>
