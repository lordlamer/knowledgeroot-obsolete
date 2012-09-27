<?php

class Knowledgeroot_Rte implements Knowledgeroot_Rte_Interface {
	public function __construct() {

	}

	public function show($content) {
		return "<textarea name=\"content\">" . $content . "</textarea>";
	}
}