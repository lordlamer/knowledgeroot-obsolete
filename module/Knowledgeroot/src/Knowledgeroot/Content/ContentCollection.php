<?php

namespace Knowledgeroot\Content;

class ContentCollection {
    protected $db = null;
    
public function __construct(\Knowledgeroot\Db\DBAL\DBAL $db) {
        $this->db = $db->getInstance();
    }

    /**
     * get all contents on this page as Knowledgeroot_Content object
     *
     * @param object $page Knowledgeroot_Page object
     * @param string $sorting column to sort by also with ASC|DESC
     * return $array
     */
    public function getCollection(\Knowledgeroot\Page\Page $page, $sorting = 'sorting') {
        $ret = array();

        $content = new \Knowledgeroot\Content\Db\Content();
        $rows = $content->fetchAll(array(
            'parent' => $page->getId(),
            'deleted' => 'FALSE'
        ), $sorting);

        foreach ($rows as $value) {
            $ret[] = new \Knowledgeroot\Content\Content($value->id);
        }

        return $ret;
    }

}
