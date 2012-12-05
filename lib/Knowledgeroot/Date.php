<?php

/**
 * Knowledgeroot Date class
 *
 * Helper class to handle dates with timezone
 *
 * @author fhabermann
 */
class Knowledgeroot_Date {

    /**
     *
     */
    protected $date;

    /**
     * constructor for datetime class
     *
     * @param object $date OPTIONAL DateTime object
     */
    public function __construct($date = null) {
	if ($date instanceof Zend_Date) {
	    $this->date = $date;
	} elseif($date instanceof DateTime) {
	    $d = new Zend_Date();
	    $d->set($date->getTimestamp());

	    $this->date = $d;
	} else {
	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // create date object with timezone
	    $this->date = new Zend_Date();
	    $this->date->setTimezone($config->base->timezone);
	}
    }

    /**
     *
     */
    public function __tostring() {
	return (string) $this->getSystemDate();
    }

    /**
     * return date in UTC timezone
     */
    public function getDbDate() {
	return $this->getDate('UTC')->get('yyyyMMdd HH:mm:ss');
    }

    /**
     * return date in system timezone
     */
    public function getSystemDate() {
	// get config
	$config = Knowledgeroot_Registry::get('config');

	return $this->getDate($config->base->timezone);
    }

    /**
     * return date in user timezone
     */
    public function getUserDate() {

    }

    /**
     * return date in given timezone
     */
    public function getDate($timezone) {
	$date = clone $this->date;
	$date->setTimezone($timezone);

	return $date;
    }

}

?>
