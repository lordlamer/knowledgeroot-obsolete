<?php

namespace Knowledgeroot\Db\Table;

/**
 *
 */
abstract class TableAbstract implements TableInterface {

    public function __construct(\Knowledgeroot\Db\DBAL\DBALInterface $db) {
        
    }

    /**
     * Fetches all rows.
     *
     * @param string|array                      $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return result
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null) {
        
    }

}

?>