<?php

class Knowledgeroot_Page {
	protected $id = null;
	protected $title = null;
	protected $belongsTo = null;
	protected $owner = null;
	protected $group = null;
	protected $userRight = null;
	protected $groupRight = null;
	protected $otherRight = null;
	protected $deleted = null;
	protected $lastUpdatedBy = null;
	protected $lastUpdated = null;
	protected $createDate = null;

    public function __construct($id = null) {
	if ($id != null) {
	    $this->load((int) $id);
	}
    }

    public function insert($filename) {

    }

    public function load($id) {
	$content = new Knowledgeroot_Db_Page();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->title = $row[0]['title'];

	$this->belongsTo = $row[0]['belongs_to'];


	$this->owner = $row[0]['owner'];
	$this->group = $row[0]['group'];
	$this->userRight = $row[0]['userrights'];
	$this->groupRight = $row[0]['grouprights'];
	$this->otherRight = $row[0]['otherrights'];
	$this->deleted = $row[0]['deleted'];
	//$this->lastUpdatedBy = $row[0]['lastupdatedby'];
	//$this->lastUpdated = $row[0]['lastupdated'];
	//$this->createDate = $row[0]['createdate'];
    }

    public function save() {
	$data = array();

	if ($this->title != null)
	    $data['title'] = $this->title;
	if ($this->belongsTo != null)
	    $data['belongs_to'] = $this->belongsTo;

	// set lastUpdatedBy
	//if ($this->lastUpdatedBy == null)
	//    $this->lastUpdatedBy = 0; // set to guest user

	//$data['lastupdatedby'] = $this->lastUpdatedBy;

	// set create date
	//if ($this->createDate == null) {
	//    $this->createDate = date("Y-m-d H:i:s", time());
	//    $data['createdate'] = $this->createDate;
	//}

	// set last updated
	//$this->lastUpdated = date("Y-m-d H:i:s", time());
	//$data['lastupdated'] = $this->lastUpdated;

	$page = new Knowledgeroot_Db_Page();

	if ($this->id == null) {
	    $this->id = $page->insert($data);
	} else {
	    $page->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$page = new Knowledgeroot_Db_Page();

	if ($markOnly == true) {
	    $data = array('deleted' => 1);
	    $page->update($data, 'id = ' . $id);
	} else {
	    $page->delete('id = ' . $id);
	}
    }

   public function moveTo($pageId) {
	if ($this->id == null)
	    throw new Knowledgeroot_Page_Exception('Page id is empty!');

	$page = new Knowledgeroot_Db_Page();

	$data = array('belongs_to' => $pageId);
	$page->update($data, 'id = ' . $this->id);

	$this->belongsTo = $pageId;
    }

    /**
     * get id
     *
     * @return type
     */
    public function getId() {
	return $this->id;
    }

    /**
     * get title
     */
    public function getTitle() {
	return $this->title;
    }

    /**
     * get parent page id
     */
    public function getParent() {
	return $this->belongsTo;
    }

    public function setParent($parentId) {
	$this->belongsTo = $parentId;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    /**
     * get all pages on this page as Knowledgeroot_Page object
     *
     * return $array
     */
    public static function getPages(Knowledgeroot_Page $parentPage = null) {
	$ret = array();

	$page = new Knowledgeroot_Db_Page();
	$select = $page->select();
	//$select->where('belongs_to = ?', $parentPage->getId());
	$select->where('deleted = 0');
	$ret = $page->fetchAll($select);

	return $ret;
    }
}

?>