<?php

class FAQQuestionsModel extends AbstractPagesModel {

	var $package = 'FAQ';
	var $icon = 'comment', $table = 'questions';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('author_name')
			->setLabel('Your name')
			->setDescription('Name or a nickname is compulsory')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(ModelMetaItem::create('author_email')
			->setLabel('Your e-mail')
			->setDescription('E-mail is compulsory because of answer')
			->setRestrictions(Restriction::R_EMAIL | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setIsVisible(array('main' => false)));
		
		$this->addMetaData(ModelMetaItem::create('author_web')
			->setLabel('Your web')
			->setDescription('If you have a website, you can fill it in')
			->setRestrictions(Restriction::R_LINK | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setIsVisible(array('main' => false)));
		
		$this->addMetaData(ModelMetaItem::create('answer')
			->setLabel('Answer')
			->setType(Form::FORM_HTML_BBCODE)
			->setSqlType('text NOT NULL')
			->setSqlIndex('fulltext')
			->setWysiwygType(Javascript::WYSIWYG_BBCODE)
			->setExtendedTable(true)
			->setIsVisible(array('main' => false)));
	
    	$this->removeMetaData('description');
    	
		$this->getMetaData('url')->setIsEditable(ModelMetaItem::NEVER);
		$this->getMetaData('text')
			->setType(Form::FORM_HTML_BBCODE) 
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setDescription('Type anything you want for the website owner or other visitors');
    }


} 

?>