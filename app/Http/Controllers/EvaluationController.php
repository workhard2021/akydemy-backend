<?php

namespace App\Http\Controllers;

use App\Enums\eTypeEvaluation;
use App\Enums\eTypeFile;
use App\Libs\ManagerFile;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluationController extends Controller
{
    public function __construct(private EvaluationService $service)
    {}
    public function lists(){
        return $this->service->repos->listNotPaginate();
    }
    public function index($search=''){
        return $this->service->repos->searchText($search);
    }

    public function getEvaluationForModuleId($moduleId=''){
      return response($this->service->repos->findManyByColumn($moduleId,'module_id'));
    }
    public function create(Request $request){
        $data=$request->validate([
            'module_id'=>'required|numeric',
            'title'=>'required|string|max:200',
            'type'=>'required|string|'.Rule::in(eTypeEvaluation::getValues()),
            'visibility_date_limit'=>'required|date',
            'fichier'=>'nullable|mimes:'.implode(",",eTypeFile::getValues())
        ]);
        $module=$this->service->repos->findModule($data['module_id']);
        if(!$module){
             return response(['errors'=>['error'=>'Resource not found']],422);
        }
        if($this->service->repos->existModule($data['module_id'],$data['type'],$data['visibility_date_limit'])){
             return response(['errors'=>['error'=>'Vous avez déja programmé : '.$data['type']. ' pour le ' .$data['visibility_date_limit']]],422);
        }
        // $data['title']=$module->title;
        $item= $this->service->create($data);
        if($request->hasFile('fichier')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['fichier'],
            config('ressources-file.examens-evalutions').'/'.$data['type'].'/'.$data['visibility_date_limit'],$file_name);
            $item->url_file=$file_name['url'];
            $item->name_file=$file_name['name'];
            $item->save();
        }
        return response($item,201);
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
            'published'=>'required|boolean',
            'is_closed'=>'required|boolean'
        ]);
        $item= $this->service->update($id,$data);
        return response($item,200);
    }
    
    public function destroy($id){
        $item=$this->service->repos->find($id);
        if($item && $item->url_file){
           ManagerFile::deleteWithUrl($item->url_file);
        }
        return response($this->service->delete($id),204);
    }
}
