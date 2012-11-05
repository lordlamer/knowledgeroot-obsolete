<?php

class Knowledgeroot_File {
    protected $hash = null;
    protected $id = null;
    protected $parent = null;
    protected $name = null;
    protected $size = null;
    protected $type = 'application/octet-stream';
    protected $downloads = null;
    protected $created_by = null;
    protected $create_date = null;
    protected $changed_by = null;
    protected $change_date = null;
    protected $deleted = null;


    public function __construct($id = null) {
	if ($id != null) {
	    // load file by id
	    if(is_int($id))
		$this->load((int) $id);

	    // load file from disk
	    if(is_file($id))
		$this->loadFile($id);
	}
    }

    public function loadFile($filename) {
	$this->name = basename($filename);
	$this->size = filesize($filename);
	$this->type = $this->detectMimeType($filename);
	$this->downloads = 0;
    }

    public function load($id = null) {
	if ($id != null) {
	    $this->load((int) $id);
	}
    }

    public function save() {
	$data = array();

	// get session
	$session = new Zend_Session_Namespace('user');

	if ($this->parent != null)
	    $data['parent'] = $this->parent;
	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->type != null)
	    $data['type'] = $this->type;
	if ($this->size != null)
	    $data['size'] = $this->size;
	if ($this->downloads != null)
	    $data['downloads'] = $this->downloads;
	if ($this->hash != null)
	    $data['hash'] = $this->hash;

	// set changed_by
	if ($this->created_by === null)
	    $this->created_by = (($session->id !== null) ? $session->id : 0); // set to guest user

	// set changed_by
	if ($this->changed_by === null)
	    $this->changed_by = (($session->id !== null) ? $session->id : 0); // set to guest user

	if ($this->created_by !== null)
	    $data['created_by'] = $this->created_by;
	if ($this->changed_by !== null)
	    $data['changed_by'] = $this->changed_by;

	// create date object
	$date = new Knowledgeroot_Date();

	// set create date
	if ($this->create_date == null) {
	    $this->create_date = $date->getDbDate();
	    $data['create_date'] = $this->create_date;
	}

	// set last updated
	$this->change_date = $date->getDbDate();
	$data['change_date'] = $this->change_date;

	$file = new Knowledgeroot_Db_File();

	if ($this->id == null) {
	    $this->id = $file->insert($data);
	} else {
	    $file->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$file = new Knowledgeroot_Db_File();

	if ($markOnly == true) {
	    $data = array('deleted' => 1);
	    $file->update($data, 'id = ' . $id);
	} else {
	    $file->delete('id = ' . $id);
	}
    }

    public function moveTo($contentId) {
	if ($this->id == null)
	    throw new Knowledgeroot_File_Exception('File id is empty!');

	$file = new Knowledgeroot_Db_File();

	$data = array('parent' => $contentId);
	$file->update($data, 'id = ' . $this->id);

	$this->parent = $pageId;
    }

    public function setName($name) {
	$this->name = $name;
    }

    public function setSize($size) {
	$this->size = $size;
    }

    public function setType($type) {
	$this->type = $type;
    }

    public function getId() {
	return $this->id;
    }

    public function getName() {
	return $this->name;
    }

    public function getSize() {
	return $this->size;
    }

    public function getType() {
	return $this->type;
    }

    public function getParent() {
	return $this->parent;
    }

    public function getCreateDate() {
	return $this->create_date;
    }

    public function getChangeDate() {
	return $this->change_date;
    }

    public function getChangedBy() {
	return $this->changed_by;
    }

    public function setChangedBy($userid) {
	$this->changed_by = $userid;
    }

    public function setParent($parentId) {
	$this->parent = $parentId;
    }

    private function saveToDatastore() {

    }

    public function setContent() {

    }

    public function getContent() {

    }

    private function detectMimeType($filename) {
	$mime = null;
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $filename);
	finfo_close($finfo);

	return $mime;
    }

    public static function getFiles(Knowledgeroot_Content $content = null) {
	$ret = array();

	$file = new Knowledgeroot_Db_File();
	$select = $file->select();
	$select->where('parent = ?', $content->getId());
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$ret = $file->fetchAll($select);

	return $ret;
    }
}

?>