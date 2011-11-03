<?php

class BlogCommentsModel extends AbstractStructuredPagesModel {

	var $package = 'Blog';
	var $icon = 'comment', $table = 'comments';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('post')
			->setLabel('Post')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('author_name')
			->setLabel('Name')
			->setRestrictions(Restriction::R_NOT_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(ModelMetaItem::create('author_email')
			->setLabel('E-mail')
			->setRestrictions(Restriction::R_EMAIL | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(array('main' => false))
			->setSqlType('varchar(255) NOT NULL'));
		
		$this->addMetaData(ModelMetaItem::create('author_web')
			->setLabel('Web')
			->setRestrictions(Restriction::R_LINK | Restriction::R_EMPTY)
			->setType(Form::FORM_INPUT_TEXT)
			->setIsVisible(array('main' => false))
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