<?php

namespace App\Http\Controllers;

use App\Enums\eRole;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private RoleService $service)
    {}
    public function index(){
        return response($this->service->repos->all(),200);
    }
    public function show($id){
        return response($this->service->repos->find($id),200);
    }
    public function store(Request $request){
        $data = $request->validate([
          'name' =>'required|unique:Roles|max:100',
        ]);
        return response($this->service->create($data),201);
    }
    public function update($id,Request $request){
        $data = $request->validate([
          'name' =>'required|max:100',
        ]);
        if($this->service->repos->exists("name",$data['name'],"id",$id)){
            return "name a déjà été pris.";
        }
        return response($this->service->update($id,$data),200);
    }

    public function destroy($id){
        return response($this->service->delete($id),204);
    }

    public function getUserRoleProf(){
        return response($this->service->repos->getUserRoleProf(),200);
    }
}
