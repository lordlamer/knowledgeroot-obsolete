<?php

class FileController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function newAction()
    {
        if ($this->getRequest()->getMethod() == 'POST') {
	    if($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name']) && is_file($_FILES['file']['tmp_name'])) {
		$file = new Knowledgeroot_File();
		$file->setParent($this->_getParam('parent'));
		$file->loadFile($_FILES['file']['tmp_name'], true);
		$file->setName($_FILES['file']['name']);
		$file->save();

		// get content
		$content = new Knowledgeroot_Content($this->_getParam('parent'));

		// redirect to page
		$this->redirect('./page/'.$content->getParent() . '#content' . $content->getId());
	    }
	} else {
	    $this->redirect('./');
	}
    }

    public function deleteAction()
    {
        $file = new Knowledgeroot_File($this->_getParam('id'));

	$content = new Knowledgeroot_Content($file->getParent());

	$file->delete();

	$this->redirect('./page/'.$content->getParent() . '#content' . $content->getId());
    }

    public function downloadAction()
    {
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);

	$config = Knowledgeroot_Registry::get('config');

        // action body
	// normal download
	// with x-sendfile
	// @see: http://codeutopia.net/blog/2009/03/06/sending-files-better-apache-mod_xsendfile-and-php/
	// @see: http://redmine.lighttpd.net/projects/1/wiki/X-LIGHTTPD-send-file
	// @see: http://wiki.nginx.org/XSendfile

	$file = new Knowledgeroot_File($this->_getParam('id'));

	// check for sendfile option
	if($config->files->xsendfile->enable) {
	    header("Content-Disposition: attachment; filename=\"".$file->getName()."\";");
	    header($config->files->xsendfile->name.": ".$file->getDatastorePath());
	} else {
	    header("Content-Type: " . $file->getType() . "; name=\"".$file->getName()."\"");
	    header("Content-Disposition: attachment; filename=\"".$file->getName()."\";");
	    header("Pragma: private");
	    header("Expires: 0");
	    header("Cache-Control: private, must-revalidate, post-check=0, pre-check=0");
	    header("Content-Transfer-Encoding: binary");

	    // put file content to screen
	    echo $file->getContent();
	}
    }


}







