<?php

class Notebook implements JsonSerializable
{
    protected $id;
    protected $title;
    protected $todos;
    
    public function __construct() 
    {
        $this->todos = array();
        $this->id = uniqid('notebook_');
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getTitle() 
    {
        return $this->title;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function addTodo(Todo $todo) 
    {
        $this->todos[$todo->getId()] = $todo;
    }
    
    public function deleteTodo($id) 
    {
        unset($this->todos[$id]);
    }
    
    public function getTodo($id) 
    {
        if(isset($this->todos[$id])) {
            return $this->todos[$id];
        }
        return 0;
    }
    
    public function getTodos()
    {
        return array_values($this->todos);
    }
    
    public function jsonSerialize()
    {
        return array(
            "id" => $this->id,
            "title" => $this->getTitle()
        );
    }
} 

