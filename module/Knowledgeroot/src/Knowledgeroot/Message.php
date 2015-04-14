<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 *
 * @author fhabermann
 */
class Knowledgeroot_Message {
    public static function addMessage($type, $title, $message) {
	// get existing messages
	$session = new Zend_Session_Namespace('Knowledgeroot_Message');

	// init message array
	if($session->messages == null)
		$session->messages = array();

	// create new message array
	$session->messages[] = array(
	    'type' => $type,
	    'title' => $title,
	    'message' => $message
	);
    }

    public static function info($title, $message) {
	Knowledgeroot_Message::addMessage('info', $title, $message);
    }

    public static function warn($title, $message) {
	Knowledgeroot_Message::addMessage('warn', $title, $message);
    }

    public static function error($title, $message) {
	Knowledgeroot_Message::addMessage('error', $title, $message);
    }

    public static function success($title, $message) {
	Knowledgeroot_Message::addMessage('success', $title, $message);
    }

    public static function getMessages() {
	// get messages
	$session = new Zend_Session_Namespace('Knowledgeroot_Message');

	// init message array
	if($session->messages == null)
		$session->messages = array();

	return $session->messages;
    }

    public static function delMessage($id) {
	// get messages
	$session = new Zend_Session_Namespace('Knowledgeroot_Message');

	// init message array
	if($session->messages == null)
		$session->messages = array();

	// delete key
	unset($session->messages[$id]);
    }
}

?>
