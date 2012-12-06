<?php

abstract class Knowledgeroot_Rte_Abstract implements Knowledgeroot_Rte_Interface {

    /**
     * @var string $name html name for editor
     */
    protected $name;

    /**
     * @var string $content content for editor
     */
    protected $content;

    /**
     * contructor for editor
     *
     * @param string $name editorname
     */
    public function __consruct($name = null) {
	if ($name != null)
	    $this->name = (string) $name;
    }

    /**
     * show editor with content
     */
    public function __toString() {
	return $this->show();
    }

    /**
     * set editor name
     *
     * @param string $name
     */
    public function setName($name) {
	$this->name = (string) $name;
    }

    /**
     * get editor name
     *
     * @return string
     */
    public function getName() {
	return $this->name;
    }

    /**
     * set editor content
     *
     * @param string $content
     */
    public function setContent($content) {
	$this->content = (string) $content;
    }

    /**
     * get editor content
     *
     * @return string
     */
    public function getContent() {
	return $this->content;
    }

}