<?php
/**
 * Created by PhpStorm.
 * User: Kompa
 * Date: 13-11-29
 * Time: 18:13
 */

class DataStorage extends Notebook 
{
    protected  $notebooks;

    public function __construct() 
    {
        $this->notebooks = array();
    }
    
    public function getNotebook($id) 
    {
        if(isset($this->notebooks[$id])) {
            return $this->notebooks[$id];
        }
        return 0;
    }
    
    public function getNotebooks()
    {
        return array_values($this->notebooks);
    }
    
    public function addNotebook(Notebook $notebook) 
    {
        $this->notebooks[$notebook->id] = $notebook;
    }
    
    public function deleteNotebook($id) 
    {
        unset($this->notebooks[$id]);
        $this->save();
    }
    
    public function save() 
    {
        $s = serialize($this);
        file_put_contents('DataStorage', $s);
    }
    
    public static function load() 
    {
        if(file_exists('DataStorage')) {
            $s = file_get_contents('DataStorage');
            $object = unserialize($s);
            return $object;
        } else {
            return new DataStorage();
        }
    }
} 
