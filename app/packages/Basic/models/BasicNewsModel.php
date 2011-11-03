<?php

class BasicNewsModel extends AbstractPagesModel {
	
	var $package = 'Basic';
	var $icon = 'news', $table = 'news';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdPublished());
    	$this->addMetaData(AbstractAttributesModel::stdColorRGBHexa());
    	
		$this->addMetaData(ModelMetaItem::create('newsTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BasicTagsModel', 'BasicNewsTagsModel', 'news', 'tag', 'newsTagsConnection', 'newsTagsConnection'); // define a many:many relation to Tag through BlogTag
    }

    
	protected function sortingDefinition() {
		if (Config::Get('BASIC_NEWS_SORTABLE')) {
			$this->sorting = array(array('column' => 'rank', 'direction' => 'desc'));
		} else {
			$this->sorting = array(array('column' => 'published', 'direction' => 'desc'));
		}
	}
    
	
	public function getPreview() {
		return trim($this->description) ? $this->description : Utilities::truncate(strip_tags($this->text), 250);
	}

} 

?>