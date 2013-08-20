<?php

class Knowledgeroot_Group {
    protected $id = null;
    protected $name = null;
    protected $description = null;
    protected $time_start = null;
    protected $time_end = null;
    protected $created_by = null;
    protected $create_date = null;
    protected $changed_by = null;
    protected $change_date = null;
    protected $active = null;
    protected $deleted = null;

    public function __construct($id = null) {
	if (!is_null($id)) {
	    $this->load((int) $id);
	}
    }

    public function load($id) {
	$group = new Knowledgeroot_Db_Group();
	$row = $group->find($id);

	$this->id = $row[0]['id'];
	$this->name = $row[0]['name'];
	$this->description = $row[0]['description'];
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

	if ($this->name != null)
	    $data['name'] = $this->name;
	if ($this->description != null)
	    $data['description'] = $this->description;
	if ($this->time_start != null)
	    $data['time_start'] = $this->time_start;
	if ($this->time_end != null)
	    $data['time_end'] = $this->time_end;
	if (!is_null($this->active)) {
	    if($this->active)
		$data['active'] = Knowledgeroot_Db::true();
	    else
		$data['active'] = Knowledgeroot_Db::false();
	}

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

	$group = new Knowledgeroot_Db_Group();

	if (is_null($this->id)) {
	    $this->id = $group->insert($data);
	} else {
	    $group->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	    $id = $this->id;

	$group = new Knowledgeroot_Db_Group();

	if ($markOnly == true) {
	    $data = array('deleted' => Knowledgeroot_Db::true());
	    $group->update($data, 'id = ' . $id);
	} else {
	    $group->delete('id = ' . $id);
	}
    }

    public function getId() {
	return $this->id;
    }

    public function setName($name) {
	$this->name = $name;
    }

    public function getName() {
	return $this->name;
    }

    public function setDescription($description) {
	$this->description = $description;
    }

    public function getDescription() {
	return $this->description;
    }

    public function setActive($active) {
	$this->active = (bool) $active;
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

    public function isActive() {
	return $this->active;
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
     * get all groups as Knowledgeroot_Group object
     *
     * return $array
     */
    public static function getGroups() {
	$ret = array();

	$group = new Knowledgeroot_Db_Group();
	$select = $group->select();
	$select->where('deleted = '.Knowledgeroot_Db::false());
	$rows = $group->fetchAll($select);

	foreach($rows as $value) {
	    $ret[] = new Knowledgeroot_Group($value->id);
	}

	return $ret;
    }

    /**
     * remove member from all groups
     *
     * @param object $member
     */
    public static function deleteMemberFromGroups($member) {
	$type = '';
	$memberId = null;

	// check if member is a user
	if($member instanceof Knowledgeroot_User) {
	    $type = 'user';
	    $memberId = $member->getId();
	}

	// check if member is a group
	if($member instanceof Knowledgeroot_Group) {
	    $type = 'group';
	    $memberId = $member->getId();
	}

	if ($memberId !== null) {
	    $member = new Knowledgeroot_Db_GroupMember();
	    $member->delete(
			array(
			    'member_id = ?' => $memberId,
			    'member_type = ?' => $type
			)
		    );
	}
    }

    /**
     * delete members from an existing group
     *
     * @param Knowledgeroot_Group $group
     */
    public function deleteGroupMembers(Knowledgeroot_Group $group) {
	$member = new Knowledgeroot_Db_GroupMember();
	$member->delete(
		    array(
			'group_id = ?' => $group->getId(),
		    )
		);
    }

    /**
     * get group members
     *
     * @return $array
     */
    public function getMembers() {
	$ret = array();

	$member = new Knowledgeroot_Db_GroupMember();
	$select = $member->select();
	$select->where('group_id = '.$this->id);
	$rows = $member->fetchAll($select);

	foreach($rows as $value) {
	    if($value->member_type == 'user')
		$ret[] = new Knowledgeroot_User($value->id);
	    else
		$ret[] = new Knowledgeroot_Group($value->id);
	}

	return $ret;
    }

    /**
     * set members for group as array
     *
     * @param array $members
     */
    public function setMembers(array $members) {
	$member = new Knowledgeroot_Db_GroupMember();

	// first delete existing members
	$member->delete('group_id = ' . $this->id);

	foreach($members as $value) {
	    $type = '';
	    $memberId = null;

	    // check if member is a user
	    if($value instanceof Knowledgeroot_User) {
		$type = 'user';
		$memberId = $member->getId();
	    }

	    // check if member is a group
	    if($value instanceof Knowledgeroot_Group) {
		$type = 'group';
		$memberId = $member->getId();
	    }

	    if($memberId !== null) {
		$member = new Knowledgeroot_Db_GroupMember();

		$member->insert(
			    array(
			    'group_id' => $this->id,
			    'member_id' => $memberId,
			    'member_type' => $type
			    )
			);
	    }
	}
    }

    /**
     * add user or group as member of this group
     *
     * @param Knowledgeroot_User|Knowledgeroot_Group $member
     */
    public function addMember($member) {
	$type = '';
	$memberId = null;

	// check if member is a user
	if($member instanceof Knowledgeroot_User) {
	    $type = 'user';
	    $memberId = $member->getId();
	}

	// check if member is a group
	if($member instanceof Knowledgeroot_Group) {
	    $type = 'group';
	    $memberId = $member->getId();
	}

	if ($memberId !== null) {
	    $member = new Knowledgeroot_Db_GroupMember();

	    $member->insert(
			array(
			    'group_id' => $this->id,
			    'member_id' => $memberId,
			    'member_type' => $type
			)
		    );
	}
    }

    /**
     * delete user or group as member of this group
     *
     * @param Knowledgeroot_User|Knowledgeroot_Group $member
     */
    public function delMember($member) {
	$type = '';
	$memberId = null;

	// check if member is a user
	if($member instanceof Knowledgeroot_User) {
	    $type = 'user';
	    $memberId = $member->getId();
	}

	// check if member is a group
	if($member instanceof Knowledgeroot_Group) {
	    $type = 'group';
	    $memberId = $member->getId();
	}

	$member = new Knowledgeroot_Db_GroupMember();

	$member->delete(
		    array(
			'group_id = ?' => $this->id,
			'member_id = ?' => $memberId,
			'member_type = ?' => $type
		    )
		);
    }
}

?>