<?php
namespace App\Contracts;
interface Ibase{
    // public function all();
    // public function find($id);
    // public function findByColumn($id,$culumn);
    public function create($data);
    public function update($id,$data);
    public function delete($id);
    
}