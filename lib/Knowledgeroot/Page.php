<?php

class Knowledgeroot_Page {
	protected $id = null;
	protected $parent = null;
	protected $name = null;
	protected $subtitle = null;
	protected $description = null;
	protected $tooltip = null;
	protected $icon = null;
	protected $alias = null;
	protected $content_collapse = null;
	protected $content_position = null;
	protected $show_content_description = null;
	protected $show_table_of_content = null;
	protected $sorting = null;
	protected $time_start = null;
	protected $time_end = null;
	protected $created_by = null;
	protected $create_date = null;
	protected $changed_by = null;
	protected $change_date = null;
	protected $active = null;
	protected $deleted = null;
	protected $acl = null;

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
	$this->subtitle = $row[0]['subtitle'];
	$this->description = $row[0]['description'];
	$this->tooltip = $row[0]['tooltip'];
	$this->icon = $row[0]['icon'];
	$this->alias = $row[0]['alias'];
	$this->content_collapse = $row[0]['content_collapse'];
	$this->content_position = $row[0]['content_position'];
	$this->show_content_description = $row[0]['show_content_description'];
	$this->show_table_of_content = $row[0]['show_table_of_content'];
	$this->sorting = $row[0]['sorting'];
	$this->time_start = $row[0]['time_start'];
	$this->time_end = $row[0]['time_end'];
	$this->active = $row[0]['active'];
	$this->created_by = $row[0]['created_by'];
	$this->create_date = new Knowledgeroot_Date($row[0]['create_date']);
	$this->changed_by = $row[0]['changed_by'];
	$this->change_date = new Knowledgeroot_Date($row[0]['change_date']);
	$this->deleted = $row[0]['deleted'];
    }

    public function save() {
	$data = array();

	// get user session
	$session = new Zend_Session_Namespace('user');

	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->subtitle != null)
	    $data['subtitle'] = $this->subtitle;
	if ($this->description != null)
	    $data['description'] = $this->description;
	if ($this->parent != null)
	    $data['parent'] = $this->parent;
	if ($this->tooltip != null)
	    $data['tooltip'] = $this->tooltip;
	if ($this->icon != null)
	    $data['icon'] = $this->icon;
	if ($this->alias != null)
	    $data['alias'] = $this->alias;
	if ($this->content_collapse == true)
	    $data['content_collapse'] = Knowledgeroot_Db::true();
	if ($this->content_collapse == false)
	    $data['content_collapse'] = Knowledgeroot_Db::false();
	if ($this->content_position != null)
	    $data['content_position'] = $this->content_position;
	if ($this->show_content_description == true)
	    $data['show_content_description'] = Knowledgeroot_Db::true();
	if ($this->show_content_description == false)
	    $data['show_content_description'] = Knowledgeroot_Db::false();
	if ($this->show_table_of_content == true)
	    $data['show_table_of_content'] = Knowledgeroot_Db::true();
	if ($this->show_table_of_content == false)
	    $data['show_table_of_content'] = Knowledgeroot_Db::false();
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

	$page = new Knowledgeroot_Db_Page();
	if ($this->id == null) {
	    $this->id = $page->insert($data);
	} else {
	    $page->update($data, 'id = ' . $this->id);
	}

	// get acl object
	$krAcl = Knowledgeroot_Registry::get('acl');

	// save acl
	$krAcl->saveAclForResource('page_'.$this->id, $this->acl);
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$page = new Knowledgeroot_Db_Page();

	if ($markOnly == true) {
	    $data = array('deleted' => Knowledgeroot_Db::true());
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
     * @return int
     */
    public function getId() {
	return $this->id;
    }

    /**
     * get title
     *
     * @return string
     */
    public function getName() {
	return $this->name;
    }

    /**
     * set name of page
     *
     * @param string $name
     */
    public function setName($name) {
	$this->name = $name;
    }

    /**
     * get subtitle
     *
     * @return string subtitle
     */
    public function getSubtitle() {
	return $this->subtitle;
    }

    /**
     * set page description
     *
     * @param string $description
     */
    public function setDescription($description) {
	$this->description = $description;
    }

    /**
     * get page description
     *
     * @return string
     */
    public function getDescription() {
	return $this->description;
    }

    /**
     * set subtitle
     *
     * @param string $subtitle subtitle
     */
    public function setSubtitle($subtitle) {
	$this->subtitle = $subtitle;
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
	return new Knowledgeroot_Date($this->create_date);
    }

    public function getChangeDate() {
	return new Knowledgeroot_Date($this->change_date);
    }

    public function getChangedBy() {
	return new Knowledgeroot_User($this->changed_by);
    }

    public function getCreatedBy() {
	return new Knowledgeroot_User($this->created_by);
    }

    /**
     * set show content description
     *
     * @param bool $show show content description
     */
    public function setShowContentDescription($show) {
	$this->show_content_description = $show;
    }

    /**
     * get show content description
     *
     * @return bool
     */
    public function getShowContentDescription() {
	return $this->show_content_description;
    }

    /**
     * set show table of content
     *
     * @param bool $show
     */
    public function setShowTableOfContent($show) {
	$this->show_table_of_content = $show;
    }

    /**
     * get show table of content
     *
     * @return bool
     */
    public function getShowTableOfContent() {
	return $this->show_table_of_content;
    }

    /**
     * get all pages on this page as Knowledgeroot_Page object
     *
     * return $array
     */
    public static function getPages(Knowledgeroot_Page $parentPage = null) {
	$ret = array();

	// get acl
	$acl = Knowledgeroot_Registry::get('acl');

	$page = new Knowledgeroot_Db_Page();
	$select = $page->select();
	//$select->where('parent = ?', $parentPage->getId());
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$rows = $page->fetchAll($select);

	foreach($rows as $value) {
	    if($acl->iAmAllowed('page_' . $value->id, 'show'))
		$ret[] = new Knowledgeroot_Page($value->id);
	}

	return $ret;
    }

    public function setAcl($acl) {
	$this->acl = Knowledgeroot_Util::objectToArray($acl);
    }

    public function getAcl() {

    }
}

?>