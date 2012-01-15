<?php

class FormField {
	
	public $name = null;		// attribute name in form
	public $actions = array();	// 
	public $meta = null;		// metadata of the field
	public $dataModel = null;	// data model doesn't have to be set
	public $modelName = null;	// name of the data model
	public $message = null; 	// messages related to this field
	public $options = null;		// values options
	public $value = null;		// value of the field
	public $classes = array();
	public $style='';
	public $onclick='';
	public $onchange='';
	public $disabled='';
	public $hasBox = true;
	public $hasLabel = true;
	public $html = null;
	public $lineStyle = '';
	public $formIdentifier = '';
	public $optionsFromModel = true;
	public $isChangeAble = true;
	public $isVisibleInForm = true;

	public function __construct($formIdentifier) {
		$this->message = new stdClass; 
		$this->message->error = array();
		$this->message->warning = array();
		$this->formIdentifier = $formIdentifier;
	}
	
	public function addClass($class) {
		if (!in_array($class, $this->classes)) {
			$this->classes[] = $class;
		}
	}
	
	public function addStyle($style) {
		$this->style .= $style;
	}

	public function setStyle($style=null) {
		
		$this->style .= $style;
	}

	public function getStyleAttr() {
		if (empty($this->style)) {
			return '';
		}
		return ' style="' . $this->style . '"';
	}
	
	public function getModelName() {
		return $this->modelName;
	}
	
	public function getDataModel() {
		return $this->dataModel;
	}

	public function getMeta() {
		return $this->meta;
	}

	public function getClassAttr() {
		if (empty($this->classes)) {
			return '';
		}
		return ' class="' . implode(' ', $this->classes) . '"';
	}

	public function getNameAttr() {
		return ' name="' . $this->name . '"';
	}

	public function getIdAttr($suffix='') {
		return ' id="' . $this->getIdValue($suffix) . '"';
	}

	public function getIdValue($suffix='') {
		return $this->getFormPrefix() . $this->name . ($suffix ? ('_' .  $suffix) : '');
	}
	
	public function getFormPrefix() {
		return 'form' . $this->formIdentifier . '_';
	}

	public function removeClass($class) {
		if (in_array($class, $this->classes)) {
			foreach($this->classes as $key => $value) {
				if ($value == $class) 
					unset($this->classes[$key]);
			}
		}
	}
	
	public function getLabel() {
		return "\n<label for=\"" . $this->getIdValue() . "\">" 
			. tg($this->meta->getLabel())
			. (Restriction::hasRestrictions($this->meta->getRestrictions(), Restriction::R_NOT_EMPTY) ? '<span class="required">*</span>' : '')
			. ($this->meta->getDescription() ? "<span class=\"small\">" . tg($this->meta->getDescription()) . "</span>":"") 
			. ($this->message->error ? "<span class=\"small error\">" . implode("<br />", $this->message->error) . "</span>":"") 
			. ($this->message->warning ? "<span class=\"small error\">" . implode("<br />", $this->message->warning) . "</span>":"") 
			. "</label>\n";
	}

	
	public function addBox() {
		$this->hasBox = true;
		return $this;
	}
	
	public function addLabel() {
		$this->hasLabel = true;
		return $this;
	}
	
	public function getFormIdentifier() {
		return $this->formIdentifier;
	}
	
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		return $this;
	}
	
	public function getHTML() {
		$this->adjustValue();
		$onclick = $this->onclick ? " onclick=\"{$this->onclick}\"": '';
		$onchange = $this->onchange ? " onchange=\"{$this->onchange}\"": '';
		$style = $this->style ? " style=\"{$this->style}\"": '';
		$this->disabled = $this->isChangeAble ? '' : " disabled=\"disabled\"";
		if ($this->html === null) {
			$this->setHTML($this->getClassAttr(), $this->getStyleAttr(), $onclick, $onchange);
		}
		$output = $this->html;
		if ($this->hasLabel) {
			$output = $this->getLabel() . $output;
		}
		if ($this->hasBox) {
			$lineStyle = $this->lineStyle ? " style=\"{$this->lineStyle}\"": '';
			$lineClass = $this->meta->getLineClass();
			$lineClass = 'line' . (($lineClass) ? ' ' . $lineClass : '');
			$output = "\n\n<!-- Form field " . $this->meta->getName() . " (begin) -->\n"
			. "<div class=\"$lineClass\"" . $this->getIdAttr('line') . " $lineStyle>"
				. $output
				. "\n<div class=\"clear\"></div>"
				. "\n</div>"
				. "\n<!-- Form field " . $this->meta->getName() . " (end) -->\n";
		}
		return $output;
	}

	protected function adjustValue() {
		$this->value = htmlspecialchars($this->value);
			
		// we don't need add autolink in the forms..
		$this->value = preg_replace('/autolink:(\w+)::(\w+)::(\w+)/', 'autolink!:$1::$2::$3', $this->value);
	}
	
}









class FormFieldFactory {
	static public function getInstance($fieldType, $formIdentifier) {
		switch ($fieldType) {
			case Form::FORM_SPECIFIC_NOT_IN_DB: return new FormFieldSpecificNotInDb($formIdentifier); break;
			case Form::FORM_INPUT_NUMBER: return new FormFieldInputNumber($formIdentifier); break;
			case Form::FORM_INPUT_TEXT: return new FormFieldInputText($formIdentifier); break;
			case Form::FORM_TEXTAREA: return new FormFieldHTML($formIdentifier); break;
			case Form::FORM_HTML: return new FormFieldHTML($formIdentifier); break;
			case Form::FORM_RADIO: return new FormFieldRadio($formIdentifier); break;
			case Form::FORM_CHECKBOX: return new FormFieldCheckbox($formIdentifier); break;
			case Form::FORM_SELECT: return new FormFieldSelect($formIdentifier); break;
			case Form::FORM_MULTISELECT: return new FormFieldMultiSelect($formIdentifier); break;
			case Form::FORM_SELECT_FOREIGNKEY: return new FormFieldSelect($formIdentifier); break;
			case Form::FORM_MULTISELECT_FOREIGNKEY: return new FormFieldMultiSelectForeignKey($formIdentifier); break;
			case Form::FORM_INPUT_PASSWORD: return new FormFieldPassword($formIdentifier); break;
			case Form::FORM_ID: return new FormFieldInputId($formIdentifier); break;
			case Form::FORM_INPUT_DATETIME: return new FormFieldInputDateTime($formIdentifier); break;
			case Form::FORM_INPUT_DATE: return new FormFieldInputDate($formIdentifier); break;
			case Form::FORM_INPUT_TIME: return new FormFieldInputTime($formIdentifier); break;
			case Form::FORM_INPUT_IMAGE: return new FormFieldImage($formIdentifier); break;
			case Form::FORM_HTML_BBCODE: return new FormFieldHTML($formIdentifier); break;
			case Form::FORM_HIDDEN: return new FormFieldHidden($formIdentifier); break;
			case Form::FORM_CAPTCHA: return new FormFieldCaptcha($formIdentifier); break;
			case Form::FORM_RECAPTCHA: return new FormFieldRecaptcha($formIdentifier); break;
			case Form::FORM_INPUT_FILE: return new FormFieldFile($formIdentifier); break;
			case Form::FORM_COLOR_RGBHEXA: return new FormFieldColorRHBHEXA($formIdentifier); break;
			case Form::FORM_RADIO_FOREIGNKEY: throw new Exception("Not implemented yet."); break;
			case Form::FORM_LINK: return new FormFieldLink($formIdentifier); break;
			case Form::FORM_UPLOAD_FILE: return new FormFieldUploadFile($formIdentifier); break;
			case Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE: return new FormFieldMultiSelectForeignKeyInteractive($formIdentifier); break;
		}
	}
}




	
class FormFieldSpecificNotInDb extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = $this->meta->getRenderObject()->getFormHTML($this);
	}
}

class FormFieldHidden extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = "<p class=\"nodisplay\"><input type=\"hidden\"" . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\" /></p>";
	}
	public function getHTML() {
		if ($this->html === null) {
			$this->setHTML('', '', '', '', '');
		}
		return $this->html;
	}
}

class FormFieldInputDate extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		Javascript::addFile(Request::$url['base'] . DIR_LIBS . 'datetimepicker/datetimepicker.js');
		Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'datetimepicker/stylesheets/calendarview.css');
		Javascript::addScript("Event.observe(window, 'load', function() { Calendar.setup({
		dateField: '" . $this->meta->getIdValue() . "',
		triggerElement: '" . $this->meta->getIdValue('button') . "',
		timeMode: 0})});");
		$this->html = "<input type=\"text\"" . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\" />"
			."<a href=\"JavaScript:void(0);\" onclick=\"return false;\"" . $this->getIdAttr('button') . "><img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . '32/calendar_view.png' . "\" alt=\"" . tg("Choose date") . "\"class=\"choose\" /></a>";
	}
}

class FormFieldInputTime extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		Javascript::addFile(Request::$url['base'] . DIR_LIBS . 'datetimepicker/datetimepicker.js');
		Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'datetimepicker/stylesheets/calendarview.css');
		Javascript::addScript("Event.observe(window, 'load', function() { Calendar.setup({
		dateField: '" . $this->getIdValue() . "',
		triggerElement: '" . $this->getIdValue('button') . "',
		timeMode: 1,
		timeStep: 30})});");
		$this->html = "<input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\" />"
			. "<a href=\"JavaScript:void(0);\" onclick=\"return false;\" " . $this->getIdAttr('button') . "><img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . '32/calendar_view.png' . "\" alt=\"" . tg("Choose time") . "\"class=\"choose\" /></a>";
	}
}

class FormFieldInputDateTime extends FormFieldInputTime {
}

class FormFieldInputText extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = "<input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\"{$onclick}{$onchange}{$style} />";
	}
}

class FormFieldInputId extends FormFieldInputText {
}

class FormFieldInputNumber extends FormFieldInputText {
}

class FormFieldCheckbox extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$class = 'checkbox';
		$this->html = "<input type=\"checkbox\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"1\"". (($this->value) ? " checked=\"checked\"" : "") ." class=\"$class\" />";
	}
}

class FormFieldPassword extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = "<input type=\"password\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"\" class=\"$class\" />";
		$origFieldId = $this->getIdValue();
		if (Restriction::hasRestrictions($this->meta->getRestrictions(), Restriction::R_CONFIRM_DOUBLE)) {
			$this->html .= "\n<div class=\"clear\"></div>";
			$this->html .= "\n</div>";
			$thisConfirm = new stdClass;
			$thisConfirm->message = new stdClass; 
			$thisConfirm->message->error = array();
			$thisConfirm->message->warning = array();
			$thisConfirm->meta = ModelMetaItem::create("confirm_" . $this->meta->getName())
				->setLabel(tg("Confirm") . " " . tg($this->meta->getLabel()))
				->setDescription(tg($this->meta->getDescription()))
				->setRestrictions($this->meta->getRestrictions());
			$this->html .= "\n\n<div class=\"line\" " . $this->getIdAttr('line_confirm') . ">";
			$this->html .= $this->meta->getLabel();
			$this->html .= "<input type=\"password\" onchange=\"if (this.value != $('$origFieldId').value) {this.addClassName('error');} else {this.removeClassName('error');}\" " . $this->getIdAttr('confirm') . " name=\"" . $thisConfirm->meta->getName() . "\" value=\"\" class=\"$class\" />";
		}
	}
}

class FormFieldHTML extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		switch ($this->meta->getType()) {
			case Form::FORM_HTML_BBCODE:
				Javascript::addWysiwyg($this->getIdValue(), Javascript::WYSIWYG_BBCODE);
				break;
			case Form::FORM_HTML:
				Javascript::addWysiwyg($this->getIdValue(), ($this->meta->getWysiwygType()));
				break;
			default:
				break;
		}
		$this->html = '';
		if ($this->meta->getType() == Form::FORM_HTML) {
			$this->html .= "<div class=\"clear\"></div>";
			$class = ' full'; // TODO: override method (constructor)
			$rows = 15;
		} else {
			//$this->html .= "<div class=\"clear\"></div>";
			$class = ' lite'; // TODO: override method (constructor)
			$rows = 3;
		}
		$this->html .= "<textarea class=\"$class\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" cols=\"80\" rows=\"$rows\"$style>" . $this->value . "</textarea>";
		if ($this->meta->getType() == Form::FORM_TEXTAREA) { // TODO: override method (setHTML)
			$this->html .= "\n<div class=\"clear\"></div>";
			$this->html .= "<a href=\"#\" onclick=\"elementHeightChange($('" . $this->getIdValue() . "'), 50, -40);return false;\" class=\"decrease\"><img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . '16/up.png' . "\" alt=\"" . tg("decrease") . "\" /></a>\n";
			$this->html .= "<a href=\"#\" onclick=\"elementHeightChange($('" . $this->getIdValue() . "'), 600, 40);return false;\" class=\"increase\"><img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . '16/down.png' . "\" alt=\"" . tg("increase") . "\" /></a>\n";
		}
	}
}

class FormFieldRadio extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$class = ' radio';
		if (!$this->meta->getOptionsMustBeSelected()) {
			$this->html .= "<input type=\"radio\" " . $this->getIdAttr('empty') . " name=\"" . $this->meta->getName() . "\" value=\"\"" . (!$this->value ? " checked=\"checked\"" : "") . " class=\"$class\" />";
			$this->html .= "<label for=\"" . $this->getIdValue('empty') . " class=\"radio\">" . tg('nothing') . "</label>";
		}
		$transOpt = $this->meta->getOptionsShouldBeTranslated();
		foreach ($this->meta->getOptions() as $o) {
			$this->html .= "<input type=\"radio\" " . $this->getIdAttr($o["id"]) . " name=\"" . $this->meta->getName() . "\" value=\"" . $o["id"] . "\"". (($this->value == $o["id"]) ? " checked=\"checked\"" : "") . ($o["disabled"] ? " disabled=\"disabled\"" : "") . " class=\"$class\"{$onclick}{$onchange}{$style} />";
			$this->html .= "<label for=\"" . $this->getIdValue($o["id"]) . "\" class=\"radio\">" . ($transOpt ? tg($o["value"]) : $o["value"]) . "</label>";
		}	
	}
}

class FormFieldSelect extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$this->html .= "<select " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" class=\"$class\"{$this->disabled}>";
		if (!$this->meta->getOptionsMustBeSelected()) {
			$this->html .= "<option value=\"\"". ((!$this->value) ? " selected=\"selected\"" : "") . ">" . "[not selected]" . "</option>\n";
		}
		$transOpt = $this->meta->getOptionsShouldBeTranslated();
		if ($this->optionsFromModel && property_exists($this, 'modelName')) {
			$options = MetaDataContainer::getFieldOptions($this->modelName, $this->meta->getName());
		} else {
			$options = $this->meta->getOptions();
		}
		foreach ($options as $o) {
			$this->html .= "<option value=\"" . $o["id"] . "\"". (($this->value == $o["id"]) ? " selected=\"selected\"" : ""). ((isset($o["disabled"]) && $o["disabled"]) ? " disabled=\"disabled\"" : "") .">" . ($transOpt ? tg($o["value"]) : $o["value"]) . "</option>\n";
		}
		$this->html .= "</select>";
		$this->html .= "\n<div class=\"clear\"></div>";
		if ($this->meta->getSelector()) {
			$this->html .= "<div " . $this->getIdAttr('container') . " class=\"selector\"></div>\n";
		}
		
		if ($this->meta->getLinkNewItem()) {
			// window selector
			Javascript::addFile(Request::$url['base'] . DIR_LIBS . 'windows/javascripts/window.js');
			Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'windows/themes/default.css'); 
			Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'windows/themes/lighting.css');
			$selectorWindowButton = Javascript::addSelectorWindowButton($this, tg('Add a new item'));
		} else {
			$selectorWindowButton = '';
		} 
		if ($this->meta->getSelector()) {
			$script = Javascript::addSelector($this);
			if (Config::Get('SELECTOR_IMMEDIATELY')) {
				$this->html .= "<script type=\"text/javascript\">\n";
				$this->html .= $script;
				$this->html .= "\n";
				$this->html .= "var a = new Element('a', {href: '#'})\n";
				$this->html .= "a.addClassName('add')\n";
				$this->html .= 'a.onclick=function() {' . $selectorWindowButton . '}' . "\n";
				$this->html .= "$('" . $this->getIdValue('container') . "').appendChild(a)\n";
				$this->html .= "</script>\n";
			}
		}
	}
}

class FormFieldMultiSelect extends FormFieldSelect {
	protected function adjustValue() {
	}
	
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$this->html .= "<select " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "[]\" multiple=\"multiple\" size=\"5\" class=\"$class\">";
		
		$selectorDefinition = array();
		if ($this->meta->getType() == Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE) {
			$options = $this->options;
		} else {
			$options = MetaDataContainer::getFieldOptions($this->modelName, $this->meta->getName());
		}
		foreach ($options as $o) {
			$this->html .= "<option value=\"" . $o["id"] . "\"". ((in_array($o["id"], $this->value)) ? " selected=\"selected\"" : ""). (array_key_exists("disabled", $o) && $o["disabled"] ? " disabled=\"disabled\"" : "") .">" . $o["value"] . "</option>\n";
		}
		$this->html .= "</select>";
		if ($this->meta->getSelector()) {
			$this->html .= "<div " . $this->getIdAttr('container') . " class=\"selector\"></div>\n";
		}
		$this->html .= "\n<div class=\"clear\"></div>";
		
		if ($this->meta->getLinkNewItem()) {
			// window selector
			Javascript::addWindows();
			$selectorWindowButton = Javascript::addSelectorWindowButton($this, tg('Add a new item'), 'addButtonFunction');
		} else {
			$selectorWindowButton = '';
		} 
		if ($this->meta->getSelector()) {
			$script = Javascript::addSelector($this, null, null, 'addButtonFunction');
			if (Config::Get('SELECTOR_IMMEDIATELY')) {
				$this->html .= "<script type=\"text/javascript\">\n";
				$this->html .= 'var addButtonFunction=function() {' . $selectorWindowButton . '}' . "\n";
				$this->html .= $script;
				$this->html .= "</script>\n";
			}
		} else {
			if ($this->meta->getLinkNewItem()) {
				Javascript::addWindows();
				$link = $this->meta->getLinkNewItem();
				$linkFull = Request::getLinkSimple($link['package'], $link['controller'], $link['action']);
				$linkReload = Request::getLinkSimple($link['package'], $link['controller'], $link['actionResult']);
				$this->html .= "<a href=\"#\" onclick=\"return windowPopupAjax('$linkFull', 'get', '".$this->getIdValue()."', '$linkReload')\">".tg('Add new item')."</a>\n";
			}
		}
	}
}

class FormFieldMultiSelectForeignKey extends FormFieldMultiSelect {
	protected function adjustValue() {
	}
	
}

class FormFieldMultiSelectForeignKeyInteractive extends FormFieldMultiSelect {
	protected function adjustValue() {
	}
}

class FormFieldFile extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$browserBaseUrl = DIR_PROJECT_URL_MEDIA;
		$browserType = $this->getBrowserType();
		Javascript::addFile(Request::$url['base'] . DIR_LIBS . "mediamanager/MediaManager.js");
		$this->html .= "<input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\" />";
		$this->html .= "<input type=\"button\" value=\"Choose\" class=\"button positive choose\" onclick=\"" 
			. "selectMedia(\$('" . $this->getIdValue() . "'), " 
			. "'$browserType')"
			. "\" />";
	}
	
	private function getBrowserType() {
		return 'file';
	}
}

class FormFieldImage extends FormFieldFile {
	private function getBrowserType() {
		return 'image';
	}
}

class FormFieldColorRHBHEXA extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		Javascript::addFile(Request::$url['base'] . DIR_LIBS . 'colorpicker/js/colorPicker.js');
		Javascript::addCSS(Request::$url['base'] . DIR_LIBS . 'colorpicker/css/colorPicker.css');
		$backgroundColor = (strlen($this->value) > 0) ? 'style="background-color: ' . $this->value . '" ' : '';
		$this->html .= "<input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" onclick=\"startColorPicker(this);\" onkeyup=\"maskedHex(this);\" $backgroundColor/>";
		$this->html .= "<a href=\"#\" onclick=\"\$('" . $this->getIdValue() . "').value=''; \$('" . $this->getIdValue() . "').style.background='#ffffff'; return false;\" title=\"Clear item\"><img src=\"" . DIR_ICONS_IMAGES_DIR_THUMBS_URL . "24/remove.png\" alt=\"Clear item\" /></a>\n";
	}
}

class FormFieldCaptcha extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$captchaImagePath = Request::getLinkSimple("Base", "Captcha", "actionCaptcha");
		$captchaImagePath = rtrim($captchaImagePath, '/');
		$randomString = time();
		$captchaImageId = $this->getIdValue('image');
		$this->html .= "<img src=\"$captchaImagePath?$randomString\" alt=\"Captcha\" id=\"$captchaImageId\" class=\"captcha\" style=\"width: " . CAPTCHA_WIDTH . "px;height: " . CAPTCHA_HEIGHT . "px;\" />";
		$this->html .= "<input type=\"button\" value=\"" . tg("Refresh") . "\" class=\"button positive captcha\" onclick=\"" 
			. "refreshImage('$captchaImagePath', '$captchaImageId')"
			. "\" />";
		$class .= ' captcha_text';
		$this->html .= "\n<div class=\"clear\"></div>";
		$this->html .= "<label></label><input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"\" class=\"$class\" />";
	}
}

class FormFieldRecaptcha extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$this->html .= "<div class=\"recaptcha\">";
		$this->html .= $this->value;
		$this->html .= "\n</div>";
	}

	protected function adjustValue() {
	}
}

class FormFieldLink extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		Javascript::addFile(Request::$url['base'] . DIR_LIBS . 'linkselector.js');
		Javascript::addScript("Event.observe(window, 'load', function() { LinkSelector.setup({
		container: '" . $this->getIdValue('container') . "',
		dataField: '" . $this->getIdValue() . "',
		triggerElement: '" . $this->getIdValue('button') . "'})});");
		$this->html .= "<input type=\"text\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" value=\"" . $this->value . "\" class=\"$class\" />";
		$this->html .= "<div id=\"" . $this->getIdValue('container') . "\"></div>";
	}
}


class FormFieldUploadFile extends FormField {
	public function setHTML($class, $style, $onclick, $onchange) {
		$this->html = '';
		$this->html .= "<input type=\"file\" " . $this->getIdAttr() . " name=\"" . $this->meta->getName() . "\" class=\"$class\"{$onclick}{$onchange}{$style} />";
	}
}


?>
