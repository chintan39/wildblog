<?php

class BlogPackage extends Package {
		
	var $icon="blog_post";
	
	public function setDefaultConfig() {
		Config::Set("BLOG_PACKAGE_ORDER", 2, null, Config::INT, true);
		Config::Set("BLOG_PACKAGE_LANGUAGE_SUPPORT", false, null, Config::BOOL, false);
		Config::Set("BLOG_PACKAGE_ALLOW", true, null, Config::BOOL, false);
		Config::Set("BLOG_COMMENTS", true, null, Config::BOOL, false);
		Config::Set("BLOG_POSTS_LIMIT", 6, null, Config::INT, true);
	}
	
	public function getLinksAdminMenuLeft() {
		$link = new Link(array(
			'link' => Request::getLinkSimple($this->name, "Posts", "actionListing"), 
			'label' => tg($this->name . " package"), 
			'title' => tg("blog posts, tags, comments"), 
			'image' => $this->getIcon(),
			'action' => array(
				"package" => $this->name, 
				"controller" => "Posts", 
				"action" => "actionListing")));
		$link->setOrder($this->order);
		return array($link);
	}
	
}

?>