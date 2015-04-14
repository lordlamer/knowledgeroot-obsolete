<?php

namespace Knowledgeroot\Content\Db;

class Content extends \Knowledgeroot\Db\Table\TableAbstract implements Knowledgeroot\Db\Table\TableInterface
{
	protected $_name = 'content';
	protected $_primary = 'id';
	protected $_sequence = 'seq_content';
}