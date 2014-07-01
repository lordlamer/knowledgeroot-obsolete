<?php

class Knowledgeroot_Page_Path {

    /**
     *
     * @param type $pageId
     */
    public static function getPath($pageId, $path = null) {
	$page = new Knowledgeroot_Page($pageId);
	$parent = $page->getParent();

	if ($path == null) {
	    $path = array(
		0 => $page,
	    );
	}

	if ($parent == 0) {
	    // reverse sort by key
	    krsort($path);

	    return $path;
	} else {
	    $path[] = new Knowledgeroot_Page($parent);
	    return Knowledgeroot_Page_Path::getPath($parent, $path);
	}
    }

	/**
	 * check if page is accessable
	 */
	public static function isAccessable($pageId) {
		$path = Knowledgeroot_Page_Path::getPath($pageId);

		foreach($path as $node) {
			if($node->isDeleted())
				return false;
		}

		return true;
	}

}

?>