<?php

class GalleryImagesRoutes extends AbstractPagesRoutes {
	
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

		Router::registerAction($this, 'actionClearHashes')
			->addRuleUrl('admin/gallery/images/clear-hashes/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('Base|default')
			->setPermission(Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManager')
			->addRuleUrl('admin/gallery/images/manager/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('manager')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManagerNewFile')
			->addRuleUrl('admin/gallery/images/manager/new-file/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('managerUpload')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManagerEditFile') 
			->addRuleUrl('admin/gallery/images/manager/edit-file/$')
			->addRuleGet(array('file'=>'[image]'))
			->setBranch(Themes::BACK_END)
			->setTemplate('managerUpload')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManagerNewDir')
			->addRuleUrl('admin/gallery/images/manager/new-dir/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('managerUpload')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManagerEditDir')
			->addRuleUrl('admin/gallery/images/manager/edit-dir/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('managerUpload')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionImageManagerDel')
			->addRuleUrl('admin/gallery/images/manager/del/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('managerUpload')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionJumploaderUploadHandler')
			->addRuleUrl('admin/gallery/images/manager/advanced-upload-handle/$')
			->setBranch(Themes::BACK_END)
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
		Router::registerAction($this, 'actionJumploaderUploadWindow')
			->addRuleUrl('admin/gallery/images/manager/advanced-upload-show/$')
			->setBranch(Themes::BACK_END)
			->setTemplate('multipleUploader')
			->setPermission(Permission::$CONTENT_ADMIN | Permission::$ADMIN);
		
	}
	
	
}

?>