<?php

namespace App\Contracts;

abstract class RepositoryBase
{
   public $nbr = 5;
   public $nbr_premium = 14;
   public $nbr_publicite = 8;

   public function all(){
      return $this->model->orderBy('created_at','desc')->paginate($this->nbr);
   }
   public function listNotPaginate(){
      return $this->model->orderBy('created_at','desc')->get();
   }
   public function find($id){
      return $this->model->find($id);
   }
   public function findOneByColumn($culumn,$id){
       $key=$this->model->getKeyName($id);
       return $this->model->firstWhere($culumn?$culumn:$key,$id);
   } 
   public function findManyByColumn($id,$culumn){
       $key=$culumn?$culumn:$this->model->getKeyName($id);
       return $this->model->where($key,$id)->paginate($this->nbr);
   }  
   public function exists($column, $value, $columnDif, $valueDiff)
   {
      return $this->model->where($column, $value)->where($columnDif, '!=', $valueDiff)->exists();
   }
   public function searchText($search=''){
      return $this->model->search($search)->paginate($this->nbr);
   }
}