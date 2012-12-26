<?php

class Knowledgeroot_Content {

    const TYPE_TEXT = 'text';
    const POSITION_FIRST = 'start';
    const POSITION_LAST = 'end';

    protected $id = null;
    protected $parent = null;
    protected $name = null;
    protected $content = null;
    protected $type = null;
    protected $sorting = null;
    protected $time_start = null;
    protected $time_end = null;
    protected $created_by = null;
    protected $create_date = null;
    protected $changed_by = null;
    protected $change_date = null;
    protected $active = null;
    protected $deleted = null;

    public function __construct($id = null) {
	if ($id != null) {
	    $this->load((int) $id);
	}
    }

    public function load($id) {
	$content = new Knowledgeroot_Db_Content();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->parent = $row[0]['parent'];
	$this->name = $row[0]['name'];
	$this->content = $row[0]['content'];
	$this->type = $row[0]['type'];
	$this->sorting = $row[0]['sorting'];
	$this->time_start = $row[0]['time_start'];
	$this->time_end = $row[0]['time_end'];
	$this->created_by = $row[0]['created_by'];
	$this->create_date = new Knowledgeroot_Date($row[0]['create_date']);
	$this->changed_by = $row[0]['changed_by'];
	$this->change_date = new Knowledgeroot_Date($row[0]['change_date']);
	$this->active = $row[0]['active'];
	$this->deleted = $row[0]['deleted'];
    }

    public function save() {
	$data = array();

	// get session
	$session = new Zend_Session_Namespace('user');

	if ($this->parent != null)
	    $data['parent'] = $this->parent;
	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->content != null)
	    $data['content'] = $this->content;
	if ($this->type != null)
	    $data['type'] = $this->type;
	if ($this->sorting != null)
	    $data['sorting'] = $this->sorting;
	if ($this->time_start != null)
	    $data['time_start'] = $this->time_start;
	if ($this->time_end != null)
	    $data['time_end'] = $this->time_end;
	if ($this->active != null)
	    $data['active'] = $this->active;

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

	$data = array('parent' => $pageId);
	$content->update($data, 'id = ' . $this->id);

	$this->parent = $pageId;
    }

    public function moveUp() {

    }

    public function moveDown() {

    }

    public function moveAfter($contentId) {

    }

    public function moveBefore($contentId) {

    }

    public function setName($name) {
	$this->name = $name;
    }

    public function setContent($content) {
	$this->content = $content;
    }

    public function setParent($id) {
	$this->parent = $id;
    }

    public function setType($type) {
	$this->type = $type;
    }

    public function setSorting($sorting) {
	$this->sorting = $sorting;
    }

    public function setActive($active) {
	$this->active = $active;
    }

    public function setTimeStart($time) {
	$this->time_start = $time;
    }

    public function setTimeEnd($time) {
	$this->time_end = $time;
    }

    public function setChangedBy($userid) {
	$this->changed_by = $userid;
    }

    public function getId() {
	return $this->id;
    }

    public function getName() {
	return $this->name;
    }

    public function getContent($raw = false) {
	if(!$raw && Knowledgeroot_Registry::isRegistered('Knowledgeroot_Content_Parser')) {
	    $parser = Knowledgeroot_Registry::get('Knowledgeroot_Content_Parser');
	    return $parser->parse($this->content);
	}

	return $this->content;
    }

    public function getParent() {
	return $this->parent;
    }

    public function getType() {
	return $this->type;
    }

    public function getSorting() {
	return $this->sorting;
    }

    public function getActive() {
	return $this->active;
    }

    public function getTimeStart() {
	return $this->time_start;
    }

    public function getTimeEnd() {
	return $this->time_end;
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

    /**
     * get all contents on this page as Knowledgeroot_Content object
     *
     * return $array
     */
    public static function getContents(Knowledgeroot_Page $page) {
	$ret = array();

	$content = new Knowledgeroot_Db_Content();
	$select = $content->select();
	$select->where('parent = ?', $page->getId());
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$rows = $content->fetchAll($select);

	foreach($rows as $value) {
	    $ret[] = new Knowledgeroot_Content($value->id);
	}

	return $ret;
    }

}

?>