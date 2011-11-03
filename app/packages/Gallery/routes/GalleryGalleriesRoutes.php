<?php

class GalleryGalleriesRoutes extends AbstractPagesRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {

		AbstractAdminRoutes::setRouter($this);

		Router::registerAction($this, 'actionGalleriesList')
			->addRuleUrl('galleries/$')
			->setTemplate('galleriesList');
		
		Router::registerAction($this, 'actionGalleryDetail')
			->addRuleUrl('gallery/[url]/$')
			->setTemplate('galleryDetail');
		
		Router::registerSubaction($this, 'subactionGalleriesList') 
			->setTemplate('part.galleriesList');

	}
	
	
}

?>
