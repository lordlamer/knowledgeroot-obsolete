<?php

class Knowledgeroot_Page {
	protected $id = null;
	protected $parent = null;
	protected $name = null;
	protected $tooltip = null;
	protected $icon = null;
	protected $alias = null;
	protected $content_collapse = null;
	protected $content_position = null;
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

    public function insert($filename) {

    }

    public function load($id) {
	$content = new Knowledgeroot_Db_Page();
	$row = $content->find($id);

	$this->id = $row[0]['id'];
	$this->parent = $row[0]['parent'];
	$this->name = $row[0]['name'];
	$this->tooltip = $row[0]['tooltip'];
	$this->icon = $row[0]['icon'];
	$this->alias = $row[0]['alias'];
	$this->content_collapse = $row[0]['content_collapse'];
	$this->content_position = $row[0]['content_position'];
	$this->sorting = $row[0]['sorting'];
	$this->time_start = $row[0]['time_start'];
	$this->time_end = $row[0]['time_end'];
	$this->active = $row[0]['active'];
	$this->created_by = $row[0]['created_by'];
	$this->create_date = $row[0]['create_date'];
	$this->changed_by = $row[0]['changed_by'];
	$this->change_date = $row[0]['change_date'];
	$this->deleted = $row[0]['deleted'];
    }

    public function save() {
	$data = array();

	// get user session
	$session = new Zend_Session_Namespace('user');

	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->parent != null)
	    $data['parent'] = $this->parent;
	if ($this->tooltip != null)
	    $data['tooltip'] = $this->tooltip;
	if ($this->icon != null)
	    $data['icon'] = $this->icon;
	if ($this->alias != null)
	    $data['alias'] = $this->alias;
	if ($this->content_collapse != null)
	    $data['content_collapse'] = $this->content_collapse;
	if ($this->content_position != null)
	    $data['content_position'] = $this->content_position;
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

	// set create date
	if ($this->create_date == null) {
	    $this->create_date = date("Y-m-d H:i:s", time());
	    $data['create_date'] = $this->create_date;
	}

	// set last updated
	$this->change_date = date("Y-m-d H:i:s", time());
	$data['change_date'] = $this->change_date;

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

	$data = array('parent' => $pageId);
	$page->update($data, 'id = ' . $this->id);

	$this->parent = $pageId;
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
    public function getName() {
	return $this->name;
    }

    public function setName($name) {
	$this->name = $name;
    }

    /**
     * get parent page id
     */
    public function getParent() {
	return $this->parent;
    }

    public function setParent($parentId) {
	$this->parent = $parentId;
    }

    public function setTooltip($tooltip) {
	$this->tooltip = $tooltip;
    }

    public function getTooltip() {
	return $this->tooltip;
    }

    public function setIcon($icon) {
	$this->icon = $icon;
    }

    public function getIcon() {
	return $this->icon;
    }

    public function setAlias($alias) {
	$this->alias = $alias;
    }

    public function getAlias() {
	return $this->alias;
    }

    public function setContentPosition($position) {
	$this->content_position = $position;
    }

    public function getContentPosition() {
	return $this->content_position;
    }

    public function setContentCollapse($collapse) {
	$this->content_collapse = $collapse;
    }

    public function getContentCollapse() {
	return $this->content_collapse;
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
     * get all pages on this page as Knowledgeroot_Page object
     *
     * return $array
     */
    public static function getPages(Knowledgeroot_Page $parentPage = null) {
	$ret = array();

	$page = new Knowledgeroot_Db_Page();
	$select = $page->select();
	//$select->where('parent = ?', $parentPage->getId());
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$ret = $page->fetchAll($select);

	return $ret;
    }
}

?>