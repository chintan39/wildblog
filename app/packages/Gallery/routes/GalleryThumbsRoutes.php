<?php

/**
 * Handles image thumbnails creating.
 */
class GalleryThumbsRoutes extends AbstractBasicRoutes {
	
	/**
	 * This is overloaded method, to specify the definitions of the request 
	 * route mapping to the data.
	 * With other words relations between Request and Routes actions 
	 * are defined in here.
	 * @param object &$reqRoutes Request routes object, that will be updated 
	 * with new definitions.
	 */
	public function setRouter() {
		
		parent::setRouter();
		
		Router::registerAction($this, 'actionThumbnail')
			->addRuleUrl(DIR_PROJECT_URL_MEDIA_THUMBS);
		
		Router::registerAction($this, 'actionIcons')
			->addRuleUrl(DIR_ICONS_IMAGES_DIR_THUMBS_URL);
		
	}

}

?>