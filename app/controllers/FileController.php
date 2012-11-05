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
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function downloadAction()
    {
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);

        // action body
	// normal download
	// with x-sendfile
	// @see: http://codeutopia.net/blog/2009/03/06/sending-files-better-apache-mod_xsendfile-and-php/
	// @see: http://redmine.lighttpd.net/projects/1/wiki/X-LIGHTTPD-send-file
	// @see: http://wiki.nginx.org/XSendfile

	$file = new Knowledgeroot_File($this->_getParam('id'));

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







