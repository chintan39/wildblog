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


class BlogCommentsModel extends AbstractStructuredPagesModel {

	var $package = 'Blog';
	var $icon = 'comment', $table = 'comments';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('post')
			->setLabel('Post')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlindex(ModelMetaIndex::INDEX));

		$this->addMetaData(AtributesFactory::create('author_name')
			->setLabel('Name')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(AtributesFactory::create('author_email')
			->setLabel('E-mail')
			->setRestrictions(Restriction::R_EMAIL | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(AtributesFactory::create('author_web')
			->setLabel('Web')
			->setRestrictions(Restriction::R_LINK | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(ModelMetaItem::NEVER)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->getMetaData('url')->setIsEditable(ModelMetaItem::NEVER);
		$this->getMetaData('text')->setType(Form::FORM_HTML_BBCODE)->setRestrictions(Restriction::R_NOT_EMPTY);
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('BlogPostsModel', 'post', 'id'); // define a 1:many relation to Reaction 
	}


} 

?>