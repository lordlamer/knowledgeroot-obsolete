<?php
/**
 * knowledgeroot file manager that handle file contents
 */
class Knowledgeroot_FileManager {
    /**
     * constructor
     */
    public function __construct() {
	$this->initDatastore();
    }

    /**
     * init datastore
     */
    private function initDatastore() {
	// config
	$config = Knowledgeroot_Registry::get('config');

	// break if datastore/0/0 exists
	if(is_dir($config->files->datastore . "/0/0"))
		return;

	// elements
	$el = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f');

	// create datastore
	foreach($el as $value) {
	    foreach($el as $val) {
		// create folder
		mkdir($config->files->datastore . "/" . $value . "/" . $val, 0777, true);
	    }
	}
    }

    /**
     * get content from file
     *
     * @param type $filename
     * @return string return file hash
     */
    public function saveContentFromFile($filename, $delete = true) {
	// create hash from file
	$hash = md5_file($filename);

	// get filename
	$dsFilename = $this->getFilename($hash);

	// if file exists in datastor exit
	if(is_file($dsFilename))
	    return $hash;

	// get content and save
	file_put_contents($dsFilename, file_get_contents($filename));

	// delete file ?
	if($delete)
	    unlink($filename);

	return $hash;
    }

    /**
     *
     * @param type $content
     * @return string return file hash
     */
    public function saveContent($content) {
	// create hash from content
	$hash = md5($content);

	// get filename
	$filename = $this->getFilename($hash);

	// save content to datastore
	if(!is_file($filename))
	    file_put_contents($filename, $content);

	// return hash
	return $hash;
    }

    /**
     * create absolut filename from hash
     *
     * @param string $hash
     * @return string filename
     */
    public function getFilename($hash) {
	// config
	$config = Knowledgeroot_Registry::get('config');

	// get filename
	$filename = $config->files->datastore."/".$hash[0]."/".$hash[1]."/".$hash;

	// return filename
	return $filename;
    }

    /**
     * get content from file with given hash
     *
     * @param string $fileHash
     * @return string file content
     */
    public function getContent($fileHash) {
	return file_get_contents($this->getFilename($fileHash));
    }

    /**
     * delete file with hash
     *
     * @param string $fileHash
     * @return bool
     */
    public function deleteContent($fileHash) {
	return unlink($this->getFilename($fileHash));
    }
}
