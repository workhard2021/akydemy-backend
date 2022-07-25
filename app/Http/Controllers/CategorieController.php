<?php

namespace App\Http\Controllers;

use App\Services\CategorieService;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function __construct(private CategorieService $service)
    {}
    public function lists(){
        return $this->service->repos->listNotPaginate();
    }
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }
    public function create(Request $request){
        $data=$request->validate([
            'name'=>'required|string',
            'title'=>'nullable|string|max:250',
        ]);
        return $this->service->create($data);
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
               'name'=>'required|string',
               'title'=>'nullable|string|max:250',
         ]);
        return $this->service->update($id,$data);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
