<?php
/**
 * Created by PhpStorm.
 * User: Kompa
 * Date: 13-11-27
 * Time: 15:24
 */

class Todo implements JsonSerializable
{
    protected $id;
    protected $title;
    protected $description;

    public function __construct() 
    {
        $this->id = uniqid('todo_');
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setTitle($title) 
    {
        $this->title = $title;
    }
    
    public function getTitle() 
    {
        return $this->title;
    }
    
    public function setDescription($description) 
    {
        $this->description = $description;
    }
    
    public function getDescription() 
    {
        return $this->description;
    }

    public function jsonSerialize()
    {
        return array(
            "id" => $this->id,
            "title" => $this->getTitle(),
            "description" => $this->getDescription()
        );
    }
    
} 
