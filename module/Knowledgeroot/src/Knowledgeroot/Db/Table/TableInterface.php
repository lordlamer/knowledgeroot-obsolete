<?php

namespace Knowledgeroot\Db\Table;

interface TableInterface
{
    public function __construct(\Knowledgeroot\Db\DBAL\DBALInterface $db);
    public function find();
    public function findAll($where = null, $order = null, $count = null, $offset = null);
    public function insert();
    public function update();
    public function delete();
}