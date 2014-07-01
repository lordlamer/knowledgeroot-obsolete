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
	    $page->setIcon($this->_getParam('icon'));
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
	    $parent = new Knowledgeroot_Page($this->_getParam('id'));
	    $this->view->parentname = $parent->getName();

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
	    $page->setIcon($this->_getParam('icon'));
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
	    $parent = new Knowledgeroot_Page($page->getParent());
	    $this->view->parentname = $parent->getName();
	    $this->view->title = $page->getName();
	    $this->view->subtitle = $page->getSubtitle();
	    $this->view->alias = $page->getAlias();
	    $this->view->tooltip = $page->getTooltip();
	    $this->view->icon = $page->getIcon();
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
	// acl checks
	if(!Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('id'), 'edit'))
		$this->_redirect('page/' . $this->_getParam('id'));

	// using blank layout
	$this->_helper->layout->setLayout('blank');

	if($this->_getParam('target') !== null) {
	    // acl checks
	    // check for new root page
	    if($this->_getParam('target') == 0 && !Knowledgeroot_Acl::iAmAllowed('manageRootPages', 'new'))
		    $this->_redirect('');

	    // check if user has page new rights on target
	    if(!Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('target'), 'new'))
		    $this->_redirect('page/' . $this->_getParam('id'));

	    // check if page note the page itself
	    if($this->_getParam('target') == $this->_getParam('id'))
		$this->_redirect('');

	    $page = new Knowledgeroot_Page($this->_getParam('id'));
	    $page->setParent($this->_getParam('target'));
	    $page->save();

	    $this->view->pageid = $this->_getParam('id');
	    $this->view->target = $this->_getParam('target');
	} else {
	    $this->view->pageid = $this->_getParam('id');
	}
    }

    public function selectAction() {
	// using blank layout
	$this->_helper->layout->setLayout('blank');
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
		// check for broken parentId to itself
		if($page->getId() == $page->getParent())
			continue;

		// check if page is accessable
		if(!Knowledgeroot_Page_Path::isAccessable($page->getId()))
			continue;

		$item = array(
		    'id' => $page->getId(),
		    'parent' => (($page->getParent() != 0) ? $page->getParent() : '#'),
		    'url' => $config->base->base_url . 'page/' . $page->getId(),
		    'text' => $page->getName(),
		    'type' => (($page->getParent() == 0) ? 'root' : 'page'),
		    'tooltip' => $page->getTooltip(),
		    'alias' => (($config->alias->enable && $page->getAlias() != "") ? $config->base->base_url . $config->alias->prefix . "/" . $page->getAlias() : ""),
		    //'symlink' => $value['symlink'],
		    'sort' => $page->getSorting(),
		    'icon' => $page->getIcon()
		);

		$out['items'][] = $item;
	    }
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

	$translate = Knowledgeroot_Registry::get('Zend_Translate');

	// using blank layout
	$this->_helper->layout->setLayout('blank');

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
	$this->view->title = $translate->translate('Show version of page');
    }

    public function aliasAction() {
	// get page by alias
	$page = Knowledgeroot_Page::findBy("alias", $this->_getParam('alias'));

	// alias not found -> redirect to homepage
	if($page === null)
	    $this->_redirect('');

	// forward to listing
	$this->_forward('list', 'page', null, array('id' => $page->getId()));
    }

    public function restoreAction() {
	// acl checks
	if(!Knowledgeroot_Acl::iAmAllowed('page_'.$this->_getParam('id'), 'edit'))
		$this->_redirect('');

	// get page and restore version
	$page = new Knowledgeroot_Page($this->_getParam('id'), $this->_getParam('version'));
	$page->restore();

	// show success message
	Knowledgeroot_Message::success("Page restored","Page was restored to version " . $this->_getParam('version'));

	// redirect to page
	$this->_redirect('page/' . $page->getId());
    }
}

