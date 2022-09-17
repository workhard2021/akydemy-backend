<?php

namespace App\Http\Controllers;

use App\Enums\ePageName;
use App\Services\PageService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function __construct(private PageService $service)
    {}
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string|unique:pages|'.Rule::in(ePageName::getValues()),
            'title'=>'required|string|max:80|unique:pages',
            'sub_title'=>'nullable|string|max:255',
            "description"=>'required|string',
            "is_active"=>'required|boolean'
        ]);
        $data['user_id']=auth()->user()->id;
        return $this->service->create($data);
    }

    public function showPublic($name){
        $item=$this->service->repos->showPublic($name);
        if(!$item){
            return response('NOT FOUND',404);
        }
        return response($item,200);
    }
    public function show($id){
        $item=$this->service->repos->find($id,['*']);
        if(!$item){
            return response('NOT FOUND',404);
        }
        return response($item,200);
    }
    public function update(Request $request,$id){
        $data=$request->validate([
            'name'=>'required|string|'.Rule::in(ePageName::getValues()),
            'title'=>'required|string|max:80',
            'sub_title'=>'nullable|string|max:255',
            "description"=>'required|string',
            "is_active"=>'required|boolean'
        ]);
        $errors=null;
        if($this->service->repos->exists('name',$data['name'],'id',$id)){
            $errors['name']="Ce nom existe déja";
        }
        if($this->service->repos->exists('title',$data['title'],'id',$id)){
            $errors['title']="Ce title existe déja";
        }
        if($errors){
            return  response(['errors'=>$errors],422);
        }
        $data['user_id']=auth()->user()->id;
        return $this->service->update($id,$data);
    }
    public function destroy($id){
        return $this->service->delete($id);
    }
}
