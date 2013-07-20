<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Translation
 *
 * @author fhabermann
 */
class Knowledgeroot_Translation {

    /**
     * active locale
     */
    protected $locale = null;
    /**
     * array for translations
     *
     * @var $translations
     */
    protected $translations = array();

    /**
     * get array with translations
     *
     * @param $ordered return ordered list by key
     * @return array
     */
    public function getTranslations($ordered = true) {
	if($ordered)
	    ksort($this->translations);

	return $this->translations;
    }

    /**
     * load translations from path
     * @param type $path
     */
    public function loadTranslations($path) {
	if (is_dir($path)) {
	    if ($dh = opendir($path)) {
		while (($file = readdir($dh)) !== false) {
		    if($file != '.' && $file != '..' && is_dir($path . $file) && is_file($path . $file . '/knowledgeroot.mo')) {
			$this->addTranslation($file, $path . $file . '/knowledgeroot.mo');
		    }
		}
		closedir($dh);
	    }
	}
    }

    /**
     * add translation by locale and path to file
     *
     * @param type $locale
     * @param type $file
     */
    public function addTranslation($locale, $file) {
	// remove UTF8 from locale string
	$locale = str_replace('.UTF8','', $locale);

	$this->translations[$locale] = $file;
    }

    /**
     * set active locale
     *
     * @param $string $locale
     * @param bool $saveInSession
     */
    public function setLocale($locale, $saveInSession = false) {
	$this->locale = $locale;

	if($saveInSession) {
	    $session = new Zend_Session_Namespace('user');
	    $session->language = $locale;
	}
    }

    /**
     * get active locale
     */
    public function getLocale() {
	return $this->locale;
    }

    public function getLocaleFile() {
	if(isset($this->translations[$this->locale]))
	    return $this->translations[$this->locale];

	return null;
    }
}

?>
