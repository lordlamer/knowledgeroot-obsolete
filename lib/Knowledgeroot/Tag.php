<?php

class Knowledgeroot_Tag {
    protected $id = null;

    protected $name = null;

    public function __construct($id = null) {
	if (!is_null($id)) {
	    $this->load((int) $id);
	}
    }

    public function load($id) {
	$tag = new Knowledgeroot_Db_Tag();
	$row = $tag->find($id);

	$this->id = $row[0]['id'];
	$this->name = $row[0]['name'];
    }

    public function save() {
	$data = array();

	if ($this->name != null)
	    $data['name'] = $this->name;

	$tag = new Knowledgeroot_Db_Tag();

	// check if tag already exists
	$select = $tag->select();
	$select->where('name = ?', $this->name);

	$found = $tag->fetchAll($select);
	if(count($found) > 0) {
	    // get id from first element
	    $this->id = $found[0]['id'];
	    return;
	}

	// tag does not exists so save or update it
	if (is_null($this->id)) {
	    $this->id = $tag->insert($data);
	} else {
	    $tag->update($data, 'id = ' . $this->id);
	}
    }

    public function delete($id = null, $markOnly = true) {
	if ($id == null)
	$id = $this->id;

	$user = new Knowledgeroot_Db_Tag();

	if ($markOnly == true) {
	    $data = array('deleted' => Knowledgeroot_Db::true());
	    $user->update($data, 'id = ' . $id);
	} else {
	    $user->delete('id = ' . $id);
	}
    }

    public function getId() {
	return $this->id;
    }

    public function getName() {
	return $this->name;
    }

    public function setName($name) {
	$this->name = $name;
    }
}