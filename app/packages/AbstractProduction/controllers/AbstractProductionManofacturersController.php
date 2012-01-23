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


class AbstractProductionManofacturersController extends AbstractNodesController {
	
	
	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeft($this);
	}
	
	public function actionManofacturerDetail($args) {
		
		// product detail processing
		$manofacturer = $args;
		$manofacturer->addNonDbProperty("products");
		$manofacturer->products = $this->getPackageObject()->getController("Products")->getProducts(false, $manofacturer->id);

		// navigation
		$navigation = new LinkCollection();
		$home = new Link(array(
			'link' => Request::getLinkHomePage(), 
			'label' => tg('Homepage'), 
			'title' => tg('Homepage')));
		$navigation->addLink($home);
		$this->assign("navigation", $navigation->getLinks());
		
		// assign to template
		$this->assign("title", $manofacturer->title);
		$this->assign("manofacturer", $manofacturer);
		
		// show template
		//$this->display('manofacturerDetail');
	}

	/**
	 * Request handler
	 * Categories structure generation. 
	 */
	public function subactionManofacturersList($args) {
		Benchmark::log("Begin of creating ManofacturersController::subactionManofacturersList");
		$manofacturersList = new ItemCollection("manofacturersList", $this);
		$manofacturersList->setLinks("actionManofacturerDetail", "link_detail");
		$manofacturersList->loadCollection();
		$manofacturersList->addLinks();
		$this->assign($manofacturersList->getIdentifier(), $manofacturersList);
		Benchmark::log("End of creating ManofacturersController::subactionManofacturersList");
	}

	/**
	 * Request handler
	 * Categories structure generation. 
	 */
	public function actionManofacturersList($args) {
		$manofacturersList = new ItemCollection("manofacturersList", $this);
		$manofacturersList->setLinks("actionManofacturerDetail", "link_detail");
		$manofacturersList->loadCollection();
		$manofacturersList->addLinks();

		// assign to template
		$this->assign("title", "Manofacturers");
		$this->assign($manofacturersList->getIdentifier(), $manofacturersList);
		
		// show template
		//$this->display('manofacturersList');
	}
	
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionManofacturersList' => tg('List of manofacturers')), array('actionManofacturerDetail' => tg('Manofacturer')));
	}

}

?>