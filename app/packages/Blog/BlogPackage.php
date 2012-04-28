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
			//'link' => Request::getLinkSimple($this->name, "Posts", "actionListing"), 
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