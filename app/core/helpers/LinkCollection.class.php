<?php

/**
 * by Jan Horak
 * class LinkCollection
 * Collects links to organize the collection and simply creating the list of links.
 * We can sort links, links can be depended on each other (classic menu).
 * Links can be displayed in many ways (all braches, only active branch), active links are marked.
 * In the future some filters can be implemented.
 */
class LinkCollection {

	var $links;
	var $ignorePermissionDenied = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->links = array();
	}
	
	
	/**
	 * Adds a link to the collection.
	 */
	public function addLink($link) {
		$this->links[] = $link;
	}
	

	/**
	 * Returns links from the collection.
	 */
	public function getLinks() {
		return $this->links;
	}
	

	/**
	 * Set the flag, denied links will be/won't be ignored.
	 */
	public function setIgnorePermissionDenied($ignorePermissionDenied) {
		$this->ignorePermissionDenied = $ignorePermissionDenied;
	}
	

	/**
	 * Gets content from controller using special method
	 * Name of the method is specified by the caller, it has allways a prefix getLinks
	 */
	public function getContentFromControllers($collectionName) {
		$methodName = "getLinks" . $collectionName;
		foreach (Environment::getPackages() as $package) {
			// Try to get links from the Package (typicly one)
			if (method_exists($package, $methodName)) {
				$packageLinks = $package->$methodName();
				$this->eraseDeniedLinks($packageLinks);
			} else {
				$packageLinks = false;
			}
			
			// Try to get links from all controllers in the package
			$packageSubLinks = array();
			$package->loadControllers();
			foreach ($package->getControllers() as $controller) {
				if (method_exists($controller, $methodName)) {
					$newLinks = $controller->$methodName();
					$this->eraseDeniedLinks($newLinks);
					$this->markLinks($newLinks);
					$packageSubLinks = array_merge($packageSubLinks, $newLinks);
				}
			}
			
			// add package with subLinks if there is a link in package itself
			if ($packageLinks) {
				$packageLinks[0]->addSubLinks($packageSubLinks);
				$this->markLinks($packageLinks);
				$this->links = array_merge($this->links, $packageLinks);
			} elseif ($packageLinks === false) {
				// else add all subLinks
				$this->links = array_merge($this->links, $packageSubLinks);
			} else {
				// package links were removed becouse of permission
			}
		}
	}
	

	/**
	 * Removes not access-able links from array.
	 */
	private function eraseDeniedLinks(&$links) {
		if (!$this->ignorePermissionDenied) {
			foreach ($links as $key => $l) {
				if (isset($l->action['package'])
					&& isset($l->action['controller'])
					&& isset($l->action['action'])
					&& !Request::checkPermission($l->action['package'], $l->action['controller'], $l->action['action'])) {
					unset($links[$key]);
				}
			}
		}
	}
	

	/**
	 * This will sort the array using the attribute specified by $attr.
	 */
	public function sort($attr='label') {
		global $linkSortAttribute;
		$linkSortAttribute=$attr;
		if (!function_exists('tmpsort')) { 
			function tmpsort($a, $b) {
				global $linkSortAttribute;
				return strcmp($a->$linkSortAttribute, $b->$linkSortAttribute); 
			}
		}
		usort($this->links, 'tmpsort');
	}

	/**
	 * Marks Links array ACTIVE, PASIVE or SUPERIOR_ACTIVE (current link is not active 
	 */
	public function markAllLinks() {
		$this->markLinks($this->links);
	}
	
	/**
	 * Marks Links array ACTIVE, PASIVE or SUPERIOR_ACTIVE (current link is not active 
	 * but some secondary link is active).
	 * @param $links array of links (object Link) - passed by reference
	 * @return array $links with updated activity attribute.
	 */
	private function markLinks(&$links) {

		// we get link to actual action
		$actualLink = Request::getRequestActionLink();
		$actualAction = Request::getRequestAction();
		
		// if one of the links is active, all above will be "superiorActive"
		$superiorActive = false;
		
		foreach ($links as $k => $link) {
			
			// add superior active actions automaticly
			if ($link->subLinks) {
				foreach ($link->subLinks as $subItem) {
					if ($subItem->action) {
						$link->addSuperiorActiveActions($subItem->action["package"], $subItem->action["controller"], $subItem->action["action"]);
					}
				}
			}
			
			// we only set the activity and if the link is active, we mark superior links as SUPERIOR_ACTIVE
			if ($link->link == $actualLink) {
				$links[$k]->setActivity(Link::ACTIVE);
				$superiorActive = true;
			} else {
				$links[$k]->setActivity(Link::PASSIVE);
			}
			
			// try to mark sublinks too and if 
			if (count($link->getSubLinks())) {
				if (!$superiorActive) {
					$links[$k]->setActivity(Link::SUPERIOR_PASSIVE);
				}
				$subLinks = $link->getSubLinks();
				$subSuperiorActive = $this->markLinks($subLinks);
				// in case that some secondary link is active, this link will be SUPERIOR_ACTIVE
				if ($subSuperiorActive && !$superiorActive) {
					$links[$k]->setActivity(Link::SUPERIOR_ACTIVE);
					$superiorActive = $subSuperiorActive;
				}
			}
			
			// mark link as SUPERIOR_ACTIVE when it is not and should be
			if ($links[$k]->getActivity() == Link::PASSIVE && $links[$k]->checkSuperiorActiveActions($actualAction)) {
				//$links[$k]->setActivity(Link::SUPERIOR_ACTIVE);
				$superiorActive = true;
			}
		}
		
		return $superiorActive;
	}
	
}

?>
