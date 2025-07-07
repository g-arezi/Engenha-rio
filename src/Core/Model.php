<?php

namespace App\Core;

abstract class Model
{
    protected $dataFile;
    protected $data = [];
    
    public function __construct()
    {
        $this->dataFile = $this->getDataFile();
        $this->loadData();
    }
    
    abstract protected function getDataFile();
    
    protected function loadData()
    {
        if (file_exists($this->dataFile)) {
            $content = file_get_contents($this->dataFile);
            $this->data = json_decode($content, true) ?? [];
        }
    }
    
    protected function saveData()
    {
        $directory = dirname($this->dataFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($this->dataFile, json_encode($this->data, JSON_PRETTY_PRINT));
    }
    
    public function all()
    {
        return array_values($this->data);
    }
    
    public function find($id)
    {
        return $this->data[$id] ?? null;
    }
    
    public function create($attributes)
    {
        $id = $this->generateId();
        $attributes['id'] = $id;
        $this->data[$id] = $attributes;
        $this->saveData();
        return $id;
    }
    
    public function update($id, $attributes)
    {
        if (isset($this->data[$id])) {
            $this->data[$id] = array_merge($this->data[$id], $attributes);
            $this->saveData();
            return true;
        }
        return false;
    }
    
    public function delete($id)
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);
            $this->saveData();
            return true;
        }
        return false;
    }
    
    protected function generateId()
    {
        return uniqid();
    }
    
    public function where($field, $value)
    {
        return array_filter($this->data, function($item) use ($field, $value) {
            return isset($item[$field]) && $item[$field] === $value;
        });
    }
    
    public function first($field, $value)
    {
        $results = $this->where($field, $value);
        return !empty($results) ? reset($results) : null;
    }
    
    public function count()
    {
        return count($this->data);
    }
    
    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $items = array_slice($this->data, $offset, $perPage);
        
        return [
            'data' => array_values($items),
            'total' => count($this->data),
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil(count($this->data) / $perPage)
        ];
    }
}
