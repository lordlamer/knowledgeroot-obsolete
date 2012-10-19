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
	if ($date instanceof DateTime) {
	    $this->date = $date;
	} else {
	    // get config
	    $config = Knowledgeroot_Registry::get('config');

	    // create timezone object
	    $tz = new DateTimeZone($config->base->timezone);

	    // create datetime with now and timezone
	    $this->date = new DateTime('now', $tz);
	}
    }

    /**
     * return date in UTC timezone
     */
    public function getDbDate() {
	return $this->getDate('UTC');
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
	$tz = new DateTimeZone($timezone);

	$date = clone $this->date;
	$date->setTimezone($tz);

	return $date;
    }

}

?>
