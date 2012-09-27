<?php

class Knowledgeroot_Content {

    const TYPE_TEXT = 'text';
    const POSITION_FIRST = 'first';
    const POSITION_LAST = 'last';

    protected $id = null;
    protected $title = null;
    protected $content = null;
    protected $belongsTo = null;
    protected $type = null;
    protected $sort = null;
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

    public function load($id) {
	$content = new Knowledgeroot_Db_Content();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->title = $row[0]['title'];
	$this->content = $row[0]['content'];
	$this->belongsTo = $row[0]['belongs_to'];
	$this->type = $row[0]['type'];
	$this->sort = $row[0]['sorting'];
	$this->owner = $row[0]['owner'];
	$this->group = $row[0]['group'];
	$this->userRight = $row[0]['userrights'];
	$this->groupRight = $row[0]['grouprights'];
	$this->otherRight = $row[0]['otherrights'];
	$this->deleted = $row[0]['deleted'];
	$this->lastUpdatedBy = $row[0]['lastupdatedby'];
	$this->lastUpdated = $row[0]['lastupdated'];
	$this->createDate = $row[0]['createdate'];
    }

    public function save() {
	$data = array();

	if ($this->title != null)
	    $data['title'] = $this->title;
	if ($this->content != null)
	    $data['content'] = $this->content;
	if ($this->belongsTo != null)
	    $data['belongs_to'] = $this->belongsTo;
	if ($this->type != null)
	    $data['type'] = $this->type;
	if ($this->sort != null)
	    $data['sorting'] = $this->sort;
	if ($this->owner != null)
	    $data['owner'] = $this->owner;
	if ($this->group != null)
	    $data['group'] = $this->group;
	if ($this->userRight != null)
	    $data['userrights'] = $this->userRight;
	if ($this->groupRight != null)
	    $data['grouprights'] = $this->groupRight;
	if ($this->otherRight != null)
	    $data['otherrights'] = $this->otherRight;

	// set lastUpdatedBy
	if ($this->lastUpdatedBy == null)
	    $this->lastUpdatedBy = 0; // set to guest user

	$data['lastupdatedby'] = $this->lastUpdatedBy;

	// set create date
	if ($this->createDate == null) {
	    $this->createDate = date("Y-m-d H:i:s", time());
	    $data['createdate'] = $this->createDate;
	}

	// set last updated
	$this->lastUpdated = date("Y-m-d H:i:s", time());
	$data['lastupdated'] = $this->lastUpdated;

	$content = new Knowledgeroot_Db_Content();

	if ($this->id == null) {
	    $this->id = $content->insert($data);
	} else {
	    $content->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$content = new Knowledgeroot_Db_Content();

	if ($markOnly == true) {
	    $data = array('deleted' => 1);
	    $content->update($data, 'id = ' . $id);
	} else {
	    $content->delete('id = ' . $id);
	}
    }

    public function moveTo($pageId) {
	if ($this->id == null)
	    throw new Knowledgeroot_Content_Exception('Content id is empty!');

	$content = new Knowledgeroot_Db_Content();

	$data = array('belongs_to' => $pageId);
	$content->update($data, 'id = ' . $this->id);

	$this->belongsTo = $pageId;
    }

    public function moveUp() {

    }

    public function moveDown() {

    }

    public function moveAfter($contentId) {

    }

    public function moveBefore($contentId) {

    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function setContent($content) {
	$this->content = $content;
    }

    public function setBelongsTo($id) {
	$this->belongsTo = $id;
    }

    public function setType($type) {
	$this->type = $type;
    }

    public function setSort($sort) {
	$this->sort = $sort;
    }

    public function setOwner($userid) {
	$this->owner = $userid;
    }

    public function setGroup($groupid) {
	$this->group = $groupid;
    }

    public function setUserRight($right) {
	$this->userRight = $right;
    }

    public function setGroupRight($right) {
	$this->groupRight = $right;
    }

    public function setOtherRight($right) {
	$this->otherRight = $right;
    }

    public function setLastUpdatedBy($userid) {
	$this->lastUpdatedBy = $userid;
    }

    public function getId() {
	return $this->id;
    }

    public function getTitle() {
	return $this->title;
    }

    public function getContent() {
	return $this->content;
    }

    public function getBelongsTo() {
	return $this->belongsTo;
    }

    public function getType() {
	return $this->type;
    }

    public function getSort() {
	return $this->sort;
    }

    public function getOwner() {
	return $this->owner;
    }

    public function getGroup() {
	return $this->group;
    }

    public function getUserRight() {
	return $this->userRight;
    }

    public function getGroupRight() {
	return $this->groupRight;
    }

    public function getOtherRight() {
	return $this->otherRight;
    }

    public function getCreateDate() {
	return $this->createDate;
    }

    public function getLastUpdated() {
	return $this->lastUpdated;
    }

    public function getLastUpdatedBy() {
	return $this->lastUpdatedBy;
    }

    /**
     * get all contents on this page as Knowledgeroot_Content object
     *
     * return $array
     */
    public static function getContents(Knowledgeroot_Page $page) {
	$ret = array();

	$content = new Knowledgeroot_Db_Content();
	$select = $content->select();
	$select->where('belongs_to = ?', $page->getId());
	$select->where('deleted = 0');
	$ret = $content->fetchAll($select);

	return $ret;
    }

}

?>