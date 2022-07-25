<?php 
namespace App\Contracts;
abstract class ServiceBase 
{
   public function getModel()
   {
      return $this->repos->model;
   }
   public function create($data)
   {
      return $this->repos->model->create($data);
   }
   public function update($id,$data,$object=true)
   {  
      $key=$this->repos->model->getKeyName();
      $user=$this->repos->model->firstWhere($key,$id);
      if(!$object){
         return $user?->update($data);
      }
      $user?->update($data);
      return $user;
   }
   public function delete($id)
   {  
       return $this->repos->model->destroy($id);
   }
}