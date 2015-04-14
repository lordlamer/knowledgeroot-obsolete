<?php

namespace Knowledgeroot\Content;

interface ContentInterface
{
    public function load();
    public function save();
    public function delete();
    
    public function setName();
    public function getName();
}