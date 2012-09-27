<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author fhabermann
 */
class Knowledgeroot_Form_Login extends Zend_Form {

    public function init() {
	$this->clearDecorators();

	$this->setMethod("post");

	// user
	$user = new Zend_Form_Element_Text('user');
	$user->setLabel('User');
	$user->setRequired(true);
	//$name->addValidator('alnum');
	$user->addDecorators(array(
	    'ViewHelper',
	    'Errors',
	    array('HtmlTag', array('tag' => 'td')),
	    array('Label', array('tag' => 'td')),
	));
	$this->addElement($user);

	// password
	$password = new Zend_Form_Element_Password('password');
	$password->setLabel('Password');
	$password->setRequired(true);
	//$name->addValidator('alnum');
	$password->addDecorators(array(
	    'ViewHelper',
	    'Errors',
	    array('HtmlTag', array('tag' => 'td')),
	    array('Label', array('tag' => 'td')),
	));
	$this->addElement($password);

    }

}

?>
