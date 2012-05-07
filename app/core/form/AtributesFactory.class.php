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
 * Abstract class to collect all types of attributes. 
 * Attributes will be then added by special methods.
 * TODO: multiple keys should be defined by dictionary, ... what?
 */
abstract class AtributesFactory {

	/**
	 * Static constructor
	 */
	public static function create($name) {
		return new ModelMetaItem($name);
	}


	/**
	 * Attribute ID is default primary key.
	 */
	static public function stdId() {
		return self::create('id')
			->setLabel('ID')
			->setRestrictions(Restriction::R_PRIMARY)
			->setType(Form::FORM_ID)
			->setIsEditable(ModelMetaItem::NEVER)
			->setSqlType('int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->setSqlindex(ModelMetaIndex::PRIMARY);
	}

	/**
	 * Attribute Title (for example title of the blog post).
	 */
	static public function stdTitle() {
		return self::create('title')
			->setLabel('Title')
			->setDescription('main title of the item')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(255) NOT NULL')
			->setExtendedTable(true);
	}
		
	/**
	 * Attribute Url (for example part of the Url of the page).
	 */
	static public function stdUrl() {
		return self::create('url')
			->setLabel('Url')
			->setDescription('this value will be used as a part of URL')
			->setRestrictions(Restriction::R_URL_PART | Restriction::R_UNIQUE)
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setDefaultValue('')
			->setFormTab(Form::TAB_SEO)
			->setSqlType('varchar(255) NOT NULL')
			->setSqlindex(ModelMetaIndex::UNIQUE_LANG)
			->setIsAutoFilled(ModelMetaItem::ON_NEW)
			->setExtendedTable(true);
	}

	/**
	 * Attribute Html Text (for example text of the page).
	 */
	static public function stdText() {
		return self::create('text')
			->setLabel('Text')
			->setDescription('main content of the item to be displayed')
			->setRestrictions(Restriction::R_HTML)
			->setType(Form::FORM_HTML)
			->setDefaultValue('')
			->setSqlType('text NOT NULL')
			->setWysiwygType(Javascript::WYSIWYG_LITE)
			->setExtendedTable(true);
	}
	
	/**
	 * Attribute Description Text (for example text of the page).
	 */
	static public function stdDescription() {
		return self::create('description')
			->setLabel('Description')
			->setDescription('short description of the item')
			->setFormTab(Form::TAB_PROPERTIES)
			->setIsVisible(ModelMetaItem::NEVER)
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('varchar(255) NOT NULL')
			->setExtendedTable(true);
	}
	
	/**
	 * Attribute SEO Description Text (text of the meta tag description).
	 */
	static public function stdSEODescription() {
		return self::create('seo_description')
			->setLabel('SEO Description')
			->setDescription('this text will be displayed in html head as meta informations about page')
			->setFormTab(Form::TAB_SEO)
			->setIsVisible(ModelMetaItem::NEVER)
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('varchar(255) NOT NULL')
			->setExtendedTable(true);
	}
	
	/**
	 * Attribute SEO Keywords (text of the meta tag keywords).
	 */
	static public function stdSEOKeywords() {
		return self::create('seo_keywords')
			->setLabel('SEO Keywords')
			->setDescription('keywords separated by comma: \',\'')
			->setFormTab(Form::TAB_SEO)
			->setIsVisible(ModelMetaItem::NEVER)
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('varchar(255) NOT NULL')
			->setExtendedTable(true);
	}
	
	/**
	 * Attribute author foreign key.
	 */
	static public function stdAuthor() {
		return self::create('author')
			->setLabel('Author')
			->setDescription('who is author of the item')
			->setFormTab(Form::TAB_PROPERTIES)
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setDefaultValue(0)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL')
			->setSqlindex(ModelMetaIndex::INDEX);
	}

	/**
	 * Attribute inserted timestamp.
	 */
	static public function stdInserted() {
		return self::create('inserted')
			->setLabel('Inserted')
			->setDescription('when the item was inserted first time')
			->setRestrictions(Restriction::R_TIMESTAMP | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('')
			->setIsEditable(ModelMetaItem::NEVER)
			->setIsVisibleInForm(ModelMetaItem::NEVER)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ON_NEW);
	}
		
	/**
	 * Attribute updated timestamp.
	 */
	static public function stdUpdated() {
		return self::create('updated')
			->setLabel('Updated')
			->setDescription('when the item was updated the last time')
			->setRestrictions(Restriction::R_TIMESTAMP)
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setIsEditable(ModelMetaItem::NEVER)
			->setIsVisible(ModelMetaItem::NEVER)
			->setIsVisibleInForm(ModelMetaItem::NEVER)
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTime')
			->setIsAutoFilled(ModelMetaItem::ALWAYS);
	}
		
	/**
	 * Attribute activity (1 or 0).
	 */
	static public function stdActive() {
		return self::create('active')
			->setLabel('Active')
			->setDescription('If not checked, item will exist only in administration.')
			->setFormTab(Form::TAB_BASIC)
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setDefaultValue(1)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'1\'');
	}

	/**
	 * Attribute Agreement (1 or 0).
	 */
	static public function stdAgreement() {
		return self::create('agreement')
			->setLabel('Agreement')
			->setDescription('Agreement with the personal data processing.')
			->setFormTab(Form::TAB_BASIC)
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setDefaultValue(0)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'1\'');
	}

	/**
	 * Attribute parrent - foreign key to the same table.
	 */
	static public function stdParent() {
		return self::create('parent')
			->setLabel('Parent')
			->setDescription('parent of this item in the tree')
			->setFormTab(Form::TAB_PROPERTIES)
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setDefaultValue(0)
			->setOptionsMethod('listSelectTree')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	/**
	 * Attribute Email for logging.
	 */
	static public function stdLoginEmail() {
		return self::create('email')
			->setLabel('E-mail')
			->setDescription('write your e-mail')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setAdjustMethod('ToLower');
	}
	
	/**
	 * Attribute password for ligging.
	 */
	static public function stdLoginPassword() {
		return self::create('password')
			->setLabel('Password')
			->setDescription('write your private password')
			->setType(Form::FORM_INPUT_PASSWORD)
			->setDefaultValue('');
	}

	/**
	 * Attribute for account - email.
	 */
	static public function stdAccountEmail() {
		return self::create('email')
			->setLabel('E-mail')
			->setDescription('e-mail to login')
			->setRestrictions(Restriction::R_EMAIL | Restriction::R_UNIQUE)
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(64) NOT NULL')
			->setSqlindex(ModelMetaIndex::UNIQUE)
			->setAdjustMethod('ToLower');
	}

	/**
	 * Attribute simple email.
	 */
	static public function stdEmail() {
		return self::create('email')
			->setLabel('E-mail')
			->setDescription('e-mail to contact')
			->setIsVisible(ModelMetaItem::NEVER)
			->setRestrictions(Restriction::R_EMAIL)
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(64) NOT NULL')
			->setAdjustMethod('ToLower');
	}

	/**
	 * Attribute Firstname
	 */
	static public function stdFirstname() {
		return self::create('firstname')
			->setLabel('Firstname')
			->setDescription('first name of the person')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(64) NOT NULL');
	}
	
	/**
	 * Attribute Surname
	 */
	static public function stdSurname() {
		return self::create('surname')
			->setLabel('Surname')
			->setDescription('surname of the person')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(64) NOT NULL');
	}

	/**
	 * Attribute City
	 */
	static public function stdCity() {
		return self::create('city')
			->setLabel('City')
			->setDescription('city where a person lives')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(64) NOT NULL');
	}
	
	/**
	 * Attribute for account - email.
	 */
	static public function stdAccountpassword() {
		return self::create('password')
			->setLabel('Password')
			->setDescription('secret password')
			->setRestrictions(Restriction::R_CONFIRM_DOUBLE | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_PASSWORD)
			->setDefaultValue('')
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(32) NOT NULL')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	/**
	 * Attribute for Permissions - OR value of constants
	 */
	static public function stdAccountPermissions() {
		$options = array(
			array('id' => Permission::$ADMIN, 'value' => 'Administrator'),
			array('id' => Permission::$CONTENT_ADMIN, 'value' => 'Content administrator'),
			array('id' => Permission::$VISITOR, 'value' => 'Visitor'),
			array('id' => Permission::$REGISTERED_VISITOR, 'value' => 'Registred visitor'),
			);
		
		return self::create('permissions')
			->setLabel('Permissions')
			->setDescription('every user must be assigned to one role')
			->setType(Form::FORM_SELECT)
			->setDefaultValue(0)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('int(11) NOT NULL')
			->setOptions($options)
			->setOptionsMustBeSelected(true)
			->setOptionsShouldBeTranslated(true);
	}
	
	/**
	 * Attribute last logged.
	 */
	static public function stdLastLogged() {
		return self::create('last_logged')
			->setLabel('Last Logged')
			->setDescription('when a user was logged the last time')
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setFormTab(Form::TAB_PROPERTIES)
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'');
	}
	
	/**
	 * Attribute Published - timestamp
	 */
	static public function stdPublished() {
		return self::create('published')
			->setLabel('Published')
			->setDescription('when should be the topic published')
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ON_NEW);
	}
		
	/**
	 * Attribute Date/Time from - timestamp
	 */
	static public function stdDateTimeFrom() {
		return self::create('datetime_from')
			->setLabel('Date/Time from')
			->setDescription('write or select a time using an icon')
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ALWAYS);
	}
		
	/**
	 * Attribute Date/Time to - timestamp
	 */
	static public function stdDateTimeTo() {
		return self::create('datetime_to')
			->setLabel('Date/Time to')
			->setDescription('write or select a time using an icon')
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setSqlType('timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ALWAYS);
	}
		
	/**
	 * Attribute Date/Time from - timestamp
	 */
	static public function stdDateFrom() {
		return self::create('date_from')
			->setLabel('Date from')
			->setDescription('write or select a date using an icon')
			->setType(Form::FORM_INPUT_DATE)
			->setDefaultValue('0000-00-00')
			->setSqlType('date NOT NULL DEFAULT \'0000-00-00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ALWAYS);
	}
		
	/**
	 * Attribute Date/Time to - timestamp
	 */
	static public function stdDateTo() {
		return self::create('date_to')
			->setLabel('Date to')
			->setDescription('write or select a date using an icon')
			->setType(Form::FORM_INPUT_DATE)
			->setDefaultValue('0000-00-00')
			->setSqlType('date NOT NULL DEFAULT \'0000-00-00\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('CurrentDateTimeOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ALWAYS);
	}
		
	/**
	 * Attribute Link - URL
	 */
	static public function stdLink() {
		return self::create('link')
			->setLabel('Link')
			->setDescription('write or select a link')
			->setType(Form::FORM_LINK)
			->setDefaultValue('')
			->setSqlType('varchar(255) NOT NULL');
	}
				
	/**
	 * Attribute Image
	 */
	static public function stdImage() {
		return self::create('image')
			->setLabel('Image')
			->setDescription('type a path of image or select it using an icon')
			->setType(Form::FORM_INPUT_IMAGE)
			->setDefaultValue('')
			->setSqlType('varchar(255) NOT NULL');
	}
	
	/**
	 * Attribute rank (integer, smaller more important (soon in the list)).
	 */
	static public function stdRank() {
		return self::create('rank')
			->setLabel('Rank')
			->setDescription('rank of the item to define an order')
			->setRestrictions(Restriction::R_UNIQUE | Restriction::R_NO_EDIT_ON_EMPTY)
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(0)
			->setIsEditable(ModelMetaItem::NEVER)
			->setIsVisibleInForm(ModelMetaItem::NEVER)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setSqlindex(ModelMetaIndex::UNIQUE_LANG)
			->setAdjustMethod('NewMaxOnEmpty')
			->setIsAutoFilled(ModelMetaItem::ON_NEW);
	}

	
	/**
	 * Attribute price (number, smaller more important (soon in the list)).
	 * @param bool|array $overload if array, some metadata will be overloaded
	 */
	static public function stdPrice() {
		return self::create('price')
			->setLabel('Price')
			->setDescription('price of the item')
			->setRestrictions(Restriction::R_PRICE)
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(0)
			->setSqlType('decimal(12,4) NULL DEFAULT NULL')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('Number');
	}
	
	
	/**
	 * Attribute Property name.
	 */
	static public function stdPropertyValueName() {
		return self::create('value_name')
			->setLabel('Name')
			->setDescription('any value')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(255) NOT NULL DEFAULT \'\'')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	
	/**
	 * Attribute Property Value type.
	 */
	static public function stdPropertyValueType() {
		return self::create('value_type')
			->setLabel('Value Type')
			->setDescription('type of the property value')
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(0)
			->setSqlType('int(11) NOT NULL DEFAULT 0')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	
	/**
	 * Attribute Property Value Number.
	 */
	static public function stdPropertyValueNumber() {
		return self::create('value_number')
			->setLabel('Value Number')
			->setDescription('number value')
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(0)
			->setSqlType('decimal(12,4) NULL DEFAULT NULL')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	
	/**
	 * Attribute Property Value String.
	 */
	static public function stdPropertyValueString() {
		return self::create('value_string')
			->setLabel('Value String')
			->setDescription('string value')
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue('')
			->setSqlType('TEXT NULL DEFAULT NULL');
	}
	
	
	/**
	 * Attribute Property Value DateTime.
	 */
	static public function stdPropertyValueDateTime() {
		return self::create('value_datetime')
			->setLabel('Value DateTime')
			->setDescription('write date and time or select it using an icon')
			->setType(Form::FORM_INPUT_DATETIME)
			->setDefaultValue('0000-00-00 00:00:00')
			->setSqlType('datetime NULL DEFAULT NULL')
			->setSqlindex(ModelMetaIndex::INDEX);
	}
	
	
	static public function stdRatio() {
		return self::create('ratio')
			->setLabel('Ratio')
			->setDescription('')
			->setRestrictions(Restriction::R_PRICE)
			->setType(Form::FORM_INPUT_NUMBER)
			->setDefaultValue(0)
			->setSqlType('decimal(12,4) NULL DEFAULT NULL')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setAdjustMethod('Number');
	}
	
	
	static public function stdToken() {
		return self::create('token')
			->setLabel('Token')
			->setDescription('token is used to identify various data')
			->setRestrictions(Restriction::R_SHA1)
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(40) NULL DEFAULT \'\'')
			->setSqlindex(ModelMetaIndex::INDEX)
			->setIsVisible(ModelMetaItem::NEVER)
			->setIsEditable(ModelMetaItem::NEVER)
			->setAdjustBeforeSavingMethod('NewTokenOnNew');
	}
	
	static public function stdColorRGBHexa() {
		return self::create('color')
			->setLabel('Color')
			->setRestrictions(Restriction::R_COLOR_RGBHEXA)
			->setType(Form::FORM_COLOR_RGBHEXA)
			->setSqlType('varchar(7) NULL DEFAULT \'\'');
	}

			
	/**
	 * Attribute for Permissions - OR value of constants
	 */
	static public function stdYesNoRadio() {
		$options = array(
			array('id' => 0, 'value' => 'No'),
			array('id' => 1, 'value' => 'Yes'),
			);
		
		return self::create('yes_no')
			->setLabel('Yes / No')
			->setType(Form::FORM_RADIO)
			->setDefaultValue(0)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('tinyint(2) NOT NULL')
			->setOptions($options)
			->setOptionsMustBeSelected(true)
			->setOptionsShouldBeTranslated(true);
	}
	
	
	static public function stdUploadFile() {
		return self::create('upload_file')
			->setLabel('Upload file')
			->setType(Form::FORM_UPLOAD_FILE)
			->setDefaultValue(0)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('tinyint(2) NOT NULL')
			->setUploadDir('tmp')
			->setRestrictions(Restriction::R_NO_EDIT_ON_EMPTY);
	}
	
	/**
	 * Attribute IP address, longer for IPv6
	 */
	static public function stdIP() {
		return self::create('ip')
			->setLabel('IP')
			->setDescription('IP address')
			->setType(Form::FORM_INPUT_TEXT)
			->setDefaultValue('')
			->setSqlType('varchar(255) NOT NULL');
	}
}

?>
