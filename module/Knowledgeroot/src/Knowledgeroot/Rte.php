<?php

class Knowledgeroot_Rte extends Knowledgeroot_Rte_Abstract {

    public function show($content = null) {
	if ($content != null)
	    $this->content = (string) $content;

	return "<textarea class=\"form-control\" name=\"".$this->getName()."\">" . $this->content . "</textarea>";
    }

}
