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


class BasicMenuModel extends AbstractCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'page', $table = 'menu';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('all_pages')
			->setLabel('All pages')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'0\'')
			->setDescription('If checked, menu will be available on all pages.'));
		
    	$this->addMetaData(AtributesFactory::create('menuitems')
    		->setLabel('Menu items')
			->setDescription('add or remove items from menu')
			->setType(Form::FORM_SPECIFIC_NOT_IN_DB)
			->setRenderObject($this)
			->setForceIsInDb(false));
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();

	    $this->addCustomRelation('BasicMenuItemsModel', 'id', 'menu', 'menuItemsConnection'); // define a 1:many relation to Reaction 
    }
    
    public function getMenuItemsCollection(&$controller) {
		$menuName = str_replace('-', '_', $this->url);
		$menuTree = new ItemCollectionTree($menuName, $controller);
		$menuTree->addQualification(array('menu' => array(new ItemQualification('menu = ?', array($this->id)))));
		$menuTree->setSorting(array(new ItemSorting('rank')));
		$menuTree->setDm(new BasicMenuItemsModel());
		//$menuTree->treePull(ItemCollectionTree::treeAncestors | ItemCollectionTree::treeSiblings);
		$menuTree->loadCollection();
		return $menuTree;
    }
    
    public function renderMenuItem($items, $level=0, $menuId=false) {
    	$output = '';
   		$output .= '<div class="menuLinkWrap">'."\n";
    	foreach ($items as $item) {
    		$output .= '<div class="menuLink">'."\n";
    		$output .= str_repeat('<span class="indent"></span>'."\n", $level);
    		$output .= '<img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/'.$item->getIcon().'.png" class="menuItemIcon" alt="'.$item->id.'" title="'.tg('Item').' #'.$item->id.'" />'."\n";
    		$output .= '<div class="menuLinkTitleWrap">'."\n";
    		$output .= '<div class="menuLinkTitle"><a href="'.Request::getLinkItem('Basic', 'MenuItems', 'actionEdit', $item).'">'.$item->title."</a></div>\n";
    		$output .= '<div class="menuLinkLink"><a href="'.($item->getLink('link') ? $item->getLink('link')->getLink() : '#').'">'.($item->getLink('link') ? $item->getLink('link')->getLink() : tg('No link'))."</a></div>\n";
    		$output .= '</div> <!-- div.menuLinkTitleWrap -->'."\n";
    		$output .= '<div class="clear"></div>'."\n";
    		$output .= '<div class="menuLinkIcons">'."\n";
    		$output .= '<a href="'.Request::getLinkItem('Basic', 'MenuItems', 'actionEdit', $item).'"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/edit.png" alt="'.tg('Edit').'" title="'.tg('Edit item').'" /></a>'."\n";
    		$output .= '<a href="'.Request::getLinkItem('Basic', 'MenuItems', 'actionMoveUp', $item, array('token', Request::$tokenCurrent)).'"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/up.png" alt="'.tg('Up').'" title="'.tg('Move up').'" /></a>'."\n";
    		$output .= '<a href="'.Request::getLinkItem('Basic', 'MenuItems', 'actionMoveDown', $item, array('token', Request::$tokenCurrent)).'"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/down.png" alt="'.tg('Down').'" title="'.tg('Move down').'" /></a>'."\n";
    		$output .= '<a href="'.Request::getLinkSimple('Basic', 'MenuItems', 'actionNew', array('_pred_' => array('menu' => $item->menu, 'parent' => $item->id))).'"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/page_add.png" alt="'.tg('New Subitem').'" title="'.tg('New Subitem').'" /></a>'."\n";
    		$output .= '<a href="'.Request::getLinkItem('Basic', 'MenuItems', 'actionRemove', $item, array('token', Request::$tokenCurrent)).'" onclick="return confirm(\''.tg('Are you sure to remvoe this item?').'\');"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/remove.png" alt="'.tg('Remove').'" title="'.tg('Remove item').'" /></a>'."\n";
    		$output .= '</div> <!-- div.menuLinkIcons -->'."\n";
    		$output .= '</div> <!-- div.menuLink -->'."\n";
			if ($item->subItems)
				$output .= $this->renderMenuItem($item->subItems, $level+1);
		}
		if (!$level && $menuId) {
    		$output .= '<div class="menuLink">'."\n";
    		$output .= '<a href="'.Request::getLinkSimple('Basic', 'MenuItems', 'actionNew', array('_pred_' => array('menu' => $menuId, 'parent' => 0))).'"><img src="'.DIR_ICONS_IMAGES_DIR_THUMBS_URL.'24/add.png" alt="'.tg('New Item').'" title="'.tg('New Item').'" /></a>'."\n";
    		$output .= '</div> <!-- div.menuLink -->'."\n";
		}
   		$output .= '</div><!-- div.menuLinkWrap -->'."\n";
    	return $output;
    }
    
	public function getFormHTML($formField) {
		$meta = $formField->getMeta();
		$model = $formField->getDataModel();
		$fieldName = $meta->getName();
		$output = '';
		if ($fieldName == 'menuitems') {
			$menuItemsCollection = $model->getMenuItemsCollection(Environment::getPackage('Basic')->getController('Menu'));
			$output .= $this->renderMenuItem($menuItemsCollection->getItems(), 0, $model->id);
		}
		return $output;
	}
} 

?>