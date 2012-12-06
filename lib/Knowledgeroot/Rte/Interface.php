<?php

interface Knowledgeroot_Rte_Interface {
	/**
	 * contructor for editor
	 *
	 * @param string $name editorname
	 */
	public function __consruct($name = null);

	/**
	 * show editor with content
	 */
	public function __toString();

	/**
	 * show editor with content
	 *
	 * @param string $content
	 * @return string return editor code
	 */
	public function show($content = null);

	/**
	 * set editor name
	 *
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * get editor name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * set editor content
	 *
	 * @param string $content
	 */
	public function setContent($content);

	/**
	 * get editor content
	 *
	 * @return string
	 */
	public function getContent();
}