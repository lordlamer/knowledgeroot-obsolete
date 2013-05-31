<?php

class PageController extends Zend_Controller_Action {

    public function init() {
	$this->getPagePath();
    }

    public function __call($name, $params) {
	$this->_forward('index');
    }

    protected function getPagePath() {
	if ($this->_getParam('id') != "") {
	    $pagePath = Knowledgeroot_Page_Path::getPath($this->_getParam('id'));
	    Zend_Layout::getMvcInstance()->assign('pathNavi', $pagePath);
	}
    }

    public function indexAction() {
	$this->_forward('list');
    }

    public function listAction() {
	// load contents for page
	$page = new Knowledgeroot_Page($this->_getParam('id'));
	$contents = Knowledgeroot_Content::getContents($page);
	$files = array();

	// get files for each content
	foreach($contents as $value) {
	    $files[$value->getId()] = Knowledgeroot_File::getFiles(new Knowledgeroot_Content($value->getId()));
	}

	// set page for view
	$this->view->id = $page->getId();
	$this->view->title = $page->getName();

	// set contents for view
	$this->view->contents = $contents;

	// set files for view
	$this->view->files = $files;
    }

    public function newAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('page/' . $this->_getParam('page_parent'));

	    $page = new Knowledgeroot_Page();
	    $page->setParent($this->_getParam('page_parent'));
	    $page->setName($this->_getParam('page_title'));
	    $page->setAcl(json_decode($this->_getParam('acl')));
	    $page->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('page/edit/' . $page->getId());
	    } else {
		$this->_redirect('page/' . $this->_getParam('page_parent'));
	    }
	} else {
	    $this->view->action = 'new';
	    $this->view->parent = $this->_getParam('id');

	    $this->renderScript("page.phtml");
	}
    }

    public function editAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    $page = new Knowledgeroot_Page($this->_getParam('id'));

	    if ($this->_getParam('button') == 'close')
		$this->_redirect('page/' . $page->getId());

	    $page->setParent($this->_getParam('page_parent'));
	    $page->setName($this->_getParam('page_title'));
	    $page->setAcl(json_decode($this->_getParam('acl')));
	    $page->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('page/edit/' . $page->getId());
	    } else {
		$this->_redirect('page/' . $page->getId());
	    }
	} else {
	    $id = $this->_getParam('id');
	    $page = new Knowledgeroot_Page($id);

	    $this->view->action = 'edit';
	    $this->view->id = $id;
	    $this->view->parent = $page->getParent();
	    $this->view->title = $page->getName();

	    // action body
	    $this->renderScript("page.phtml");
	}
    }

    public function deleteAction() {
	$page = new Knowledgeroot_Page($this->_getParam('id'));
	$parent = $page->getParent();
	$page->delete();

	$this->_redirect('page/' . $parent);
    }

    public function moveAction() {
	// action body
    }

    public function jsontreeAction() {
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);

	// get config
	$config = Knowledgeroot_Registry::get('config');

	// get all pages
	$pages = Knowledgeroot_Page::getPages();

	// prepare dojo json store
	$out = array(
	    'identifier' => 'id',
	    'label' => 'name',
	    'items' => array()
	);

	if (count($pages) > 0) {
	    foreach ($pages as $key => $value) {
		$item = array(
		    'id' => $value['id'],
		    'parent' => $value['parent'],
		    'url' => $config->base->base_url . 'page/' . $value['id'],
		    'name' => $value['name'],
		    'type' => (($value['parent'] == 0) ? 'root' : 'page'),
		    'tooltip' => $value['tooltip'],
		    'alias' => $value['alias'],
		    //'symlink' => $value['symlink'],
		    'icon' => $value['icon']
		);

		$out['items'][] = $item;
	    }
	}

	// build reference now
	foreach ($out['items'] as $key => $value) {
	    $childs = $this->getJsonChildReference($value, $out['items']);
	    if (count($childs) > 0)
		$out['items'][$key]['children'] = $childs;
	}

	echo json_encode($out);
    }

    /**
     * get children reference for json
     * @param array $item
     * @param array $items
     * @return array
     */
    protected function getJsonChildReference($item, $items) {
	$ret = array();

	foreach ($items as $value) {
	    if ($item['id'] == $value['parent']) {
		$ret[] = array('_reference' => $value['id']);
	    }
	}

	return $ret;
    }

}

