<?php

namespace App\Http\Controllers;

use App\Libs\ManagerFile;
use App\Services\NoteStudiantService;
use Illuminate\Http\Request;

class NoteStudiantController extends Controller
{
    public function __construct(private NoteStudiantService $service)
    {}
    
    public function noteStudiantWithInfo($search='',$dateBegin='default',$dateEnd='default'){
         return $this->service->repos->noteStudiantWithInfo($search,$dateBegin,$dateEnd);
    }

    public function index(){
        return response($this->service->repos->all(),200);
    }
    
    public function currentUserNote($moduleId){
        return response($this->service->repos->currentUserNote($moduleId),200);
    }
    
    public function create(Request $request){
        $data=$request->validate([
            'evaluation_id'=>'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_closed'=>'nullable|boolean'
        ]);

        $data['user_id']=auth()->user()->id;
        $evaluation=$this->service->repos->findEvaluation($data['evaluation_id']);
        $module=$evaluation?->module;
        
        if(!$module || !$evaluation){
            return response(['errors'=>['error'=>'Resource not found']],422);
        }
        $note_etudiant=$this->service->repos->exist($data['evaluation_id'],$data['user_id']);
        $data['evaluation_id']=$evaluation->id;
        $data['type']=$evaluation->type;
        $data['module_id']=$module->id;
        $data['title']=$module->title;
        $data['teacher_id']=$module->owner_id;
        if($note_etudiant && $evaluation->is_closed){
            return response(['errors'=>['error'=>'Vous avez déja fait le teste pour ce module']],422);
        }else if($note_etudiant){
            $item=$this->service->update($note_etudiant->id,$data);
        }else{
            $item= $this->service->create($data);
        } 
        if($request->hasFile('image')){
            $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
            $file_name=ManagerFile::upload($data['image'],config('ressources-file.users').'/'.$data['user_id'].'/'.$module->id.'/'.$evaluation->type.'/'.$evaluation->visibility_date_limit,$file_name);
            ManagerFile::delete($item->name_file,config('ressources-file.users').'/'.$data['user_id'].'/'.$module->id.'/'.$evaluation->type.'/'.$evaluation->visibility_date_limit);
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
            'note_teacher'=>'required|numeric',
            'evaluation_id'=>'required|numeric',
            'user_id'=>'required|numeric',
            'is_closed'=>'nullable|boolean'
        ]);
        return response($this->service->updateNote($id,$data),200);
    }
    public function destroy($id){
        return response($this->service->delete($id),204);
    }
}
