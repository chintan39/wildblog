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


class FAQQuestionsModel extends AbstractPagesModel {

	var $package = 'FAQ';
	var $icon = 'comment', $table = 'questions';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('author_name')
			->setLabel('Your name')
			->setDescription('Name or a nickname is compulsory')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(AtributesFactory::create('author_email')
			->setLabel('Your e-mail')
			->setDescription('E-mail is compulsory because of answer')
			->setRestrictions(Restriction::R_EMAIL | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setIsVisible(ModelMetaItem::NEVER));
		
		$this->addMetaData(AtributesFactory::create('author_web')
			->setLabel('Your web')
			->setDescription('If you have a website, you can fill it in')
			->setRestrictions(Restriction::R_LINK | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL')
			->setIsVisible(ModelMetaItem::NEVER));
		
		$this->addMetaData(AtributesFactory::create('answer')
			->setLabel('Answer')
			->setType(Form::FORM_HTML_BBCODE)
			->setSqlType('text NOT NULL')
			->setSqlindex(ModelMetaIndex::FULLTEXT)
			->setWysiwygType(Javascript::WYSIWYG_BBCODE)
			->setExtendedTable(true)
			->setIsVisible(ModelMetaItem::NEVER));
	
    	$this->removeMetaData('description');
    	
		$this->getMetaData('url')->setIsEditable(ModelMetaItem::NEVER);
		$this->getMetaData('text')
			->setType(Form::FORM_HTML_BBCODE) 
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setDescription('Type anything you want for the website owner or other visitors');
    }


} 

?>