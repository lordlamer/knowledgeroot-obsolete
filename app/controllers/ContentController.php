<?php

class ContentController extends Zend_Controller_Action {

    public function init() {
	/* Initialize action controller here */
    }

    public function indexAction() {
	// action body
    }

    public function newAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('page/' . $this->_getParam('content_page'));

	    $content = new Knowledgeroot_Content();
	    $content->setTitle($this->_getParam('content_title'));
	    $content->setContent($this->_getParam('content'));
	    $content->setBelongsTo($this->_getParam('content_page'));
	    $content->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('content/edit/' . $content->getId());
	    } else {
		$this->_redirect('page/' . $this->_getParam('content_page'));
	    }
	} else {
	    $this->view->action = 'new';

	    $rte = Knowledgeroot_Registry::get('rte');
	    $this->view->editor = $rte->show("");

	    $this->view->page = $this->_getParam('id');

	    $this->renderScript("content.phtml");
	}
    }

    public function editAction() {
	if ($this->getRequest()->getMethod() == 'POST') {
	    if ($this->_getParam('button') == 'close')
		$this->_redirect('page/' . $this->_getParam('content_page'));

	    $content = new Knowledgeroot_Content($this->_getParam('id'));
	    $content->setTitle($this->_getParam('content_title'));
	    $content->setContent($this->_getParam('content'));
	    $content->setBelongsTo($this->_getParam('content_page'));
	    $content->save();

	    if ($this->_getParam('button') == 'save') {
		$this->_redirect('content/edit/' . $content->getId());
	    } else {
		$this->_redirect('page/' . $this->_getParam('content_page'));
	    }
	} else {
	    $this->view->action = 'edit';
	    $this->view->id = $this->_getParam('id');

	    $content = new Knowledgeroot_Content($this->_getParam('id'));

	    $rte = Knowledgeroot_Registry::get('rte');
	    $this->view->editor = $rte->show($content->getContent());

	    $this->view->title = $content->getTitle();

	    $this->view->page = $content->getBelongsTo();

	    $this->renderScript("content.phtml");
	}
    }

    public function deleteAction() {
	$content = new Knowledgeroot_Content($this->_getParam('id'));
	$parent = $content->getBelongsTo();
	$content->delete();

	$this->_redirect('page/' . $parent);
    }

    public function moveAction() {
	// action body
    }

    public function moveDownAction() {
	// action body
    }

    public function moveUpAction() {
	// action body
    }

    public function printAction() {
	$this->_helper->layout()->disableLayout();

	$content = new Knowledgeroot_Content($this->_getParam('id'));
	$this->view->title = $content->getTitle();
	$this->view->content = $content->getContent();
    }

}

