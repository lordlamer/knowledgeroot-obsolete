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
	$this->view->subtitle = $page->getSubtitle();
	$this->view->description = $page->getDescription();
	$this->view->contentcollapse = $page->getContentCollapse();
	$this->view->showpagedescription = $page->getShowContentDescription();
	$this->view->showtableofcontent = $page->getShowTableOfContent();

	// set contents for view
	$this->view->contents = $contents;

	// set files for view
	$this->view->files = $files;
    }

    public function newAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    // acl checks
	    // check for new root page
	    if($this->_getParam('page_parent') == 0 && !Knowledgeroot_Acl::iAmAllowed('manageRootPages', 'new'))
		    $this->_redirect('');

	    // check for non root page
	    if($this->_getParam('page_parent') != 0 && !Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('page_parent'), 'new'))
		    $this->_redirect('page/' . $this->_getParam('page_parent'));

	    // close action check
	    if ($this->_getParam('button') == 'close') {
		if($this->_getParam('page_parent') == 0)
		    $this->_redirect('');
		else
		    $this->_redirect('page/' . $this->_getParam('page_parent'));
	    }

	    $page = new Knowledgeroot_Page();
	    $page->setParent($this->_getParam('page_parent'));
	    $page->setName($this->_getParam('page_title'));
	    $page->setSubtitle($this->_getParam('page_subtitle'));
	    $page->setAlias($this->_getParam('alias'));
	    $page->setTooltip($this->_getParam('tooltip'));
	    $page->setDescription($this->_getParam('page_description'));
	    $page->setAcl(json_decode($this->_getParam('acl')));

	    if($this->_getParam('contentcollapse') == 1)
		$page->setContentCollapse(true);
	    else
		$page->setContentCollapse(false);

	    if($this->_getParam('showpagedescription') == 1)
		$page->setShowContentDescription(true);
	    else
		$page->setShowContentDescription(false);

	    if($this->_getParam('showtableofcontent') == 1)
		$page->setShowTableOfContent(true);
	    else
		$page->setShowTableOfContent(false);

	    $page->setContentPosition($this->_getParam('contentposition'));
	    $page->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('page/edit/' . $page->getId());
	    } else {
		// check if parent is 0 (root page) so redirect to page itself
		if($this->_getParam('page_parent') == 0)
		    $this->_redirect('page/' . $page->getId());
		else
		    $this->_redirect('page/' . $this->_getParam('page_parent'));
	    }
	} else {
	    $this->view->action = 'new';
	    $this->view->parent = $this->_getParam('id');

	    $rte = Knowledgeroot_Registry::get('rte');
	    $rte->setName('page_description');
	    $rte->setContent('');
	    $this->view->editor = $rte;

	    $this->renderScript("page/page.phtml");
	}
    }

    public function editAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    $page = new Knowledgeroot_Page($this->_getParam('id'));

	    // acl checks
	    // check for new root page
	    if($this->_getParam('page_parent') == 0 && !Knowledgeroot_Acl::iAmAllowed('manageRootPages', 'new'))
		    $this->_redirect('');

	    // check for non root page
	    if($this->_getParam('page_parent') != 0 && !Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('page_parent'), 'new'))
		    $this->_redirect('page/' . $this->_getParam('page_parent'));

	    if ($this->_getParam('button') == 'close')
		$this->_redirect('page/' . $page->getId());

	    $page->setParent($this->_getParam('page_parent'));
	    $page->setName($this->_getParam('page_title'));
	    $page->setSubtitle($this->_getParam('page_subtitle'));
	    $page->setAlias($this->_getParam('alias'));
	    $page->setTooltip($this->_getParam('tooltip'));
	    $page->setDescription($this->_getParam('page_description'));
	    $page->setAcl(json_decode($this->_getParam('acl')));

	    if($this->_getParam('contentcollapse') == 1)
		$page->setContentCollapse(true);
	    else
		$page->setContentCollapse(false);

	    if($this->_getParam('showpagedescription') == 1)
		$page->setShowContentDescription(true);
	    else
		$page->setShowContentDescription(false);

	    if($this->_getParam('showtableofcontent') == 1)
		$page->setShowTableOfContent(true);
	    else
		$page->setShowTableOfContent(false);

	    $page->setContentPosition($this->_getParam('contentposition'));
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
	    $this->view->subtitle = $page->getSubtitle();
	    $this->view->alias = $page->getAlias();
	    $this->view->tooltip = $page->getTooltip();
	    $this->view->contentcollapse = $page->getContentCollapse();
	    $this->view->showpagedescription = $page->getShowContentDescription();
	    $this->view->showtableofcontent = $page->getShowTableOfContent();
	    $this->view->contentposition = $page->getContentPosition();

	    $this->view->created_by = $page->getCreatedBy()->getLogin();
	    $this->view->create_date = $page->getCreateDate()->getUserDate();

	    $rte = Knowledgeroot_Registry::get('rte');
	    $rte->setName('page_description');
	    $rte->setContent($page->getDescription(true));
	    $this->view->editor = $rte;

	    $this->view->versions = $page->getVersions();

	    // action body
	    $this->renderScript("page/page.phtml");
	}
    }

    public function deleteAction() {
	// check for non root page
	if(!Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('id'), 'delete'))
		$this->_redirect('page/' . $this->_getParam('id'));

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
	    foreach ($pages as $key => $page) {
		$item = array(
		    'id' => $page->getId(),
		    'parent' => $page->getParent(),
		    'url' => $config->base->base_url . 'page/' . $page->getId(),
		    'name' => $page->getName(),
		    'type' => (($page->getParent() == 0) ? 'root' : 'page'),
		    'tooltip' => $page->getTooltip(),
		    'alias' => $page->getAlias(),
		    //'symlink' => $value['symlink'],
		    'icon' => $page->getIcon()
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

    public function showAction() {
	// check acl
	if(!Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('id'), 'show'))
		$this->_redirect('');

	$this->_helper->layout()->disableLayout();

	if($this->_getParam('version') !== null) {
	    $page = new Knowledgeroot_Page($this->_getParam('id'), $this->_getParam('version'));
	} else {
	    $page = new Knowledgeroot_Page($this->_getParam('id'));
	}

	$this->view->name = $page->getName();
	$this->view->subtitle = $page->getSubtitle();
	$this->view->alias = $page->getAlias();
	$this->view->tooltip = $page->getTooltip();
	$this->view->description = $page->getDescription();
    }

}

