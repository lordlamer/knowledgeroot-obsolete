<?php

class Knowledgeroot_Content {

    const TYPE_TEXT = 'text';
    const POSITION_FIRST = 'start';
    const POSITION_LAST = 'end';

    protected $readOnly = false;

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
    protected $acl = null;

    public function __construct($id = null, $version = null) {
	if ($id != null) {
	    if($version !== null)
		$this->load((int) $id, (int) $version);
	    else
		$this->load((int) $id);
	}
    }

    public function load($id, $version = null) {
	if($version !== null) {
	    $history = new Knowledgeroot_Db_Content_History();

	    $select = $history->select();
	    $select->where('content_id = ?', $id);
	    $select->where('version = ?', $version);

	    $row = $history->fetchAll($select);

	    $this->readOnly = true;
	} else {
	    $content = new Knowledgeroot_Db_Content();
	    $row = $content->find($id);
	}

	$this->id = $id;
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
	if($this->readOnly)
	    return;

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

	// check if acl is changed
	if($this->acl !== null) {
		// get acl object
		$krAcl = Knowledgeroot_Registry::get('acl');

		// save acl
		$krAcl->saveAclForResource('content_'.$this->id, $this->acl);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if($this->readOnly)
	    return;

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
	if($this->readOnly)
	    return;

	if ($this->id == null)
	    throw new Knowledgeroot_Content_Exception('Content id is empty!');

	$content = new Knowledgeroot_Db_Content();

	$data = array('parent' => $pageId);
	$content->update($data, 'id = ' . $this->id);

	$this->parent = $pageId;
    }

    /**
     * move content up
     *
     * @return type
     */
    public function moveUp() {
	if($this->readOnly)
	    return;

	// check page rights
	// TODO

	$db = Knowledgeroot_Registry::get('db');

	if($this->sorting == 0 || $this->sorting == null) {
	    $res = $db->query("UPDATE content SET sorting=sorting+1 WHERE parent=? AND id<>? AND deleted=".Knowledgeroot_Db::false(), array($this->parent, $this->id));
	} else {
	    $res = $db->query("SELECT id, max(sorting) as sorting
				FROM content
				WHERE parent=? AND sorting<=? AND id<>? AND deleted=?
				GROUP BY id
				ORDER BY sorting DESC
				LIMIT 1",
			    array($this->parent, $this->sorting, $this->id, Knowledgeroot_Db::false()));
	    $row = $res->fetchAll();
	    $cnt = count($row);

	    if($cnt == 1) {
		if($this->sorting == $row[0]['sorting']) {
		    $db->query("UPDATE content SET sorting=sorting+1 WHERE parent=? AND id<>? AND sorting>=? AND deleted=?", array($this->parent, $this->id, $this->sorting, Knowledgeroot_Db::false()));
		} else {
		    $db->query("UPDATE content SET sorting=? WHERE id=?", array($this->sorting, $row[0]['id']));
		    $db->query("UPDATE content SET sorting=? WHERE id=?", array($row[0]['sorting'], $this->id));
		}
	    }
	}
    }

    /**
     * move content down
     *
     * @return type
     */
    public function moveDown() {
	if($this->readOnly)
	    return;

	// check page rights
	// TODO

	$db = Knowledgeroot_Registry::get('db');

	if($this->sorting == 0 || $this->sorting == null) {
	    $res = $db->query("UPDATE content SET sorting=sorting+1 WHERE parent=? AND id<>? AND deleted=".Knowledgeroot_Db::false(), array($this->parent, $this->id));
	} else {
	    $res = $db->query("SELECT id, min(sorting) as sorting
				FROM content
				WHERE parent=? AND sorting>=? AND id<>? AND deleted=?
				GROUP BY id
				ORDER BY sorting DESC
				LIMIT 1",
			    array($this->parent, $this->sorting, $this->id, Knowledgeroot_Db::false()));
	    $row = $res->fetchAll();
	    $cnt = count($row);

	    if($cnt == 1) {
		if($this->sorting == $row[0]['sorting']) {
		    $db->query("UPDATE content SET sorting=sorting+1 WHERE parent=? AND id<>? AND sorting>=? AND deleted=?", array($this->parent, $this->id, $this->sorting, Knowledgeroot_Db::false()));
		} else {
		    $db->query("UPDATE content SET sorting=? WHERE id=?", array($this->sorting, $row[0]['id']));
		    $db->query("UPDATE content SET sorting=? WHERE id=?", array($row[0]['sorting'], $this->id));
		}
	    }
	}
    }

    public function moveAfter($contentId) {
	if($this->readOnly)
	    return;

    }

    public function moveBefore($contentId) {
	if($this->readOnly)
	    return;

    }

    public function setName($name) {
	if($this->readOnly)
	    return;

	$this->name = $name;
    }

    public function setContent($content) {
	if($this->readOnly)
	    return;

	$this->content = $content;
    }

    public function setParent($id) {
	if($this->readOnly)
	    return;

	$this->parent = $id;
    }

    public function setType($type) {
	if($this->readOnly)
	    return;

	$this->type = $type;
    }

    public function setSorting($sorting) {
	if($this->readOnly)
	    return;

	$this->sorting = $sorting;
    }

    public function setActive($active) {
	if($this->readOnly)
	    return;

	$this->active = $active;
    }

    public function setTimeStart($time) {
	if($this->readOnly)
	    return;

	$this->time_start = $time;
    }

    public function setTimeEnd($time) {
	if($this->readOnly)
	    return;

	$this->time_end = $time;
    }

    public function setChangedBy($userid) {
	if($this->readOnly)
	    return;

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
     * set acl for content
     *
     * @param array $acl
     */
    public function setAcl($acl) {
	if($this->readOnly)
	    return;

	$this->acl = Knowledgeroot_Util::objectToArray($acl);
    }

    /**
     * return acl for content
     *
     * @return array
     */
    public function getAcl() {

    }

    /**
     * get all contents on this page as Knowledgeroot_Content object
     *
     * @param object $page Knowledgeroot_Page object
     * @param string $sorting column to sort by also with ASC|DESC
     * return $array
     */
    public static function getContents(Knowledgeroot_Page $page, $sorting = 'sorting') {
	$ret = array();

	// get acl
	$acl = Knowledgeroot_Registry::get('acl');

	$content = new Knowledgeroot_Db_Content();
	$select = $content->select();
	$select->where('parent = ?', $page->getId());
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$select->order($sorting);
	$rows = $content->fetchAll($select);

	foreach($rows as $value) {
	    if($acl->iAmAllowed('content_' . $value->id, 'show'))
		$ret[] = new Knowledgeroot_Content($value->id);
	}

	return $ret;
    }

    /**
     * return array with existing versions of content
     *
     * return array with integer values of existing versions of content
     * @return array
     */
    public function getVersions() {
	$ret = array();

	$history = new Knowledgeroot_Db_Content_History();

	$select = $history->select();
	$select->where('content_id = ?', $this->id);
	$select->order('version DESC');
	$rows = $history->fetchAll($select);

	foreach($rows as $value) {
	    $ret[] = array(
		'version' => $value['version'],
		'user' => new Knowledgeroot_User($value['changed_by']),
		'date' => new Knowledgeroot_Date($value['change_date'])
		);
	}

	return $ret;
    }

    /**
     * add tag to content
     *
     * @param Knowledgeroot_Tag $tag
     */
    public function addTag(Knowledgeroot_Tag $tag) {
	$tagMember = new Knowledgeroot_Db_Tag_Content();

	// check if relation already exists
	$select = $tagMember->select();
	$select->where('tag_id = ?', $tag->getId());
	$select->where('content_id = ?', $this->id);

	$found = $tagMember->fetchAll($select);

	if(count($found) < 1) {
	    $data = array();
	    $data['tag_id'] = $tag->getId();
	    $data['content_id'] = $this->id;

	    $tagMember->insert($data);
	}
    }

    /**
     * get all tags for content
     *
     * @return array array of Knowledgeroot_Tag
     */
    public function getTags() {
	$ret = array();

	$tagMember = new Knowledgeroot_Db_Tag_Content();

	$select = $tagMember->select();
	$select->where('content_id = ?', $this->id);

	$tags = $tagMember->fetchAll($select);

	foreach($tags as $tag) {
	    $ret[] = new Knowledgeroot_Tag($tag['tag_id']);
	}

	return $ret;
    }

    /**
     * set array with tags for content
     *
     * @param array $tags array of Knowledgeroot_Tag
     */
    public function setTags(array $tags) {
	// delete existing tags
	$tagMember = new Knowledgeroot_Db_Tag_Content();
	$tagMember->delete('content_id = ' . $this->id);

	// set new tags
	foreach($tags as $tag) {
	    $this->addTag($tag);
	}
    }

    /**
     * delete tag from content
     *
     * @param Knowledgeroot_Tag $tag
     */
    public function deleteTag(Knowledgeroot_Tag $tag) {
	$tagMember = new Knowledgeroot_Db_Tag_Content();
	$tagMember->delete(array('tag_id' => $tag->getId(), 'content_id' => $this->id));
    }

    /**
     * delete all tags from content
     *
     *
     */
    public function deleteTags() {
	$tagMember = new Knowledgeroot_Db_Tag_Content();
	$tagMember->delete('content_id = ' . $this->id);
    }
}

?>