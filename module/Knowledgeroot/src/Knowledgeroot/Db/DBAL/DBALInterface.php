<?php

namespace Knowledgeroot\Db\DBAL;

interface DBALInterface
{
    public function getInstance();
    public function setInstance($dbal);
}