<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knowledgeroot\Db\DBAL;

/**
 * Description of DBAL
 *
 * @author fhabermann
 */
class DBAL implements DBALInterface {
    protected $dbal = null;
    
    public function setInstance($dbal) {
        $this->dbal = $dbal;
    }
    
    public function getInstance() {
        return $this->dbal;
    }
}
