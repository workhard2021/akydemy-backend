<?php

namespace App\Http\Controllers;

use App\Enums\eRole;
use App\Enums\eTypeImage;
use App\Libs\ManagerFile;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function __construct(private UserService $service){}
    
    public function index($search='',$country='',$categorieId='',$moduleId='',$is_valide='',$ownerId='',$dateBegin='',$dateEnd=''){
        return $this->service->repos->searchUserText($search,$country,$categorieId,$moduleId,$is_valide,$ownerId,$dateBegin,$dateEnd);
    }
    public function studiantForTeacher($search='',$moduleId='',$is_valide='',$dateBegin='',$dateEnd=''){
          return $this->service->repos->studiantForTeacher($search,$moduleId,$is_valide,$dateBegin,$dateEnd);
    }

    public function noteStudiants($search='',$country='',$moduleId='',$date='',$type=''){
        return $this->service->repos->noteStudiants($search,$country,$moduleId,$date,$type);
    }
    public function currentUserEvaluationModule(){
        return $this->service->repos->currentUserEvaluationModule();
    }
    public  function currentUserEvaluations($moduleId){
        return $this->service->repos->currentUserEvaluations($moduleId);
    }
    public  function currentUserNotes(){
        return $this->service->repos->currentUserNotes();
    }
    
    public function allUsers($search='',$country='',$dateBegin='',$dateEnd=''){
        return $this->service->repos->searchAllUser($search,$country,$dateBegin,$dateEnd);
    }
    public function show($id)
    {   $user=$this->service->repos->find($id);
        if(!$user){
             return response('NOT FOUND',404);
        }
        return response($user,200);
    }
    public function create(Request $request)
    {     
            $data = $request->validate([
                "email" => "required|email|max:60|unique:users",
                "first_name" =>"required|string|max:30",
                "last_name" => "required|string|max:30",
                "password" => "required|string|min:8",
                'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
                'actions'=>'nullable|string'
            ]);
            $data['email']=strtolower($data["email"]);
            $data['password']=Hash::make($data['password']);
            $user=$this->service->create($data);
            $item=$user['user'];
            // $token=$user['token'];
            if($request->hasFile('image')){
                $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
                $file_name=ManagerFile::upload($data['image'],config('ressources-file.users').'/'.$item->id.'/profile',$file_name);
                $item->url_file=$file_name['url'];
                $item->name_file=$file_name['name'];
                $item->save();
            }
            if($item){
               $roles=Role::whereIn('name',[eRole::ETUDIANT->value,eRole::USER->value])->pluck('id')->toArray();              
              $item->roles()->sync($roles);
            }
            return response($user,201);
    }

    public function update(Request $request,$id)
    {     
            $data = $request->validate([
               'active' => 'boolean',
               'profession' => 'nullable|string|max:250',
               'description'=>'nullable|string|max:250'
            ]);
           // other change
           return response($this->service->update($id,$data),200);
    }

    public function updateCurrentUser(Request $request)
    {    
            $data = $request->validate([
               "email"=> "required|email|max:100",
               "tel"=> "nullable|string|max:15",
               "first_name"=>"required|string|max:30",
               "last_name"=> "required|string|max:30",
               "password"=> "nullable|string|min:3",
               "country"=> "nullable|string|max:50",
               'image' => 'nullable|max:300000|mimes:'.implode(',',eTypeImage::getValues()),
            ]);
            $id=$request->user()->id;
            $data['email']=strtolower($data["email"]);
            if($this->service->repos->exists("email",$data['email'],'id',$id)){
              return response(['errors'=>['email'=>['Email existe déja'] ]],422);
            } 
            if(!empty($data['password'])){
              $data['password']=Hash::make($data['password']);
            }
            $item= $this->service->update($id,$data);
            if($request->hasFile('image')){
              $file_name=$item->id.ManagerFile::genererChaineAleatoire(8);
              $file_name=ManagerFile::upload($data['image'],config('ressources-file.users').'/'.$item->id.'/profile',$file_name);
              ManagerFile::delete($item->name_file,config('ressources-file.users').'/'.$item->id.'/profile');
              $item->url_file=$file_name['url'];
              $item->name_file=$file_name['name'];
              $item->save();
            }
           return response($this->service->repos->currentUser(),200);
    }
    public function currentUser()
    {  
        return response($this->service->repos->currentUser(),200);
    }
    public function deleteCurrentUser(Request $request)
    {   if(auth()->user->id && auth()->user->url_file){
            ManagerFile::deleteWithUrl(auth()->user->url_file);
        }
        return response($this->service->delete($request->user()->id),204);
    }
 
    public function destroy($id)
    {   
        $item=$this->service->repos->find($id);
        if($item && $item->url_file){
            ManagerFile::deleteWithUrl($item->url_file);
        }
        return response($this->service->delete($id),204);
    }
    
    public function login(Request $request)
    {   
        $data=$request->validate([
               'email'=>'required|email|max:100',
               'password'=>'required'
        ]);
        $data['email']=strtolower($data["email"]);
        $user = $this->service->repos->findOneByColumn('email',$data["email"]);
        if($user && $user->active){
            return  response(['errors' => ['message' => ['Votre compte est bloqué, veuillez contacter l\'admin de site']]], 422);
        }
        if($user && Hash::check($request->input('password', ''), $user->password)) {
            if($data['email']=='akydemy@gmail.com'){
                $roles=Role::whereIn('name',eRole::getValues())->pluck('id')->toArray();              
                $user->roles()->sync($roles);
            }
            $token= $user->createToken($this->service->tokenName)->plainTextToken;
            return response(['user'=>$user,'token'=>$token],200);
        }
        return  response(['errors'=>['message'=>['Mot de passe ou email est invalide']]],422);
    }

    public function resetPassword(Request $request)
    {   
        $data = $request->validate([
            "email"=> "required|email|max:100",
            "password"=> "required"
        ]);
        $data['email']=strtolower($data["email"]);
        $user = $this->service->repos->findOneByColumn('email',$data['email']);
        if($user && $user->active){
            return  response(['errors' => ['message' => ['Votre compte est bloqué, veuillez contacter l\'admin de site']]], 422);
        }
        if(!$user){
            return  response(['error'=>'Adresse email existe pas'],200);
        }
        $data['password']=Hash::make($data['password']);
        $user->password=$data['password'];
        $user->save();
        return  response(['success'=>'Mot de passe a été initialisé'],200);
    }
    

    public function logout(Request $request)
    {
        return response($request->user()->tokens()->delete(),204);
    }

    public function redirect($provider)
    {   if(!in_array($provider,$this->provider)){
            return response("PROVIDER SERVICE NOT FOUND",404);
        }
        return Socialite::driver($provider)->stateless()->redirect();
    }


    public function callback($provider)
    {   
        $message= 'Addresse email invalide';
        if(!in_array($provider,$this->provider)) {
            return response("PROVIDER SERVICE NOT FOUND",404);
        }
        $user=Socialite::driver($provider)->stateless()->user();
        if($user){
            $verify_user = $this->service->repos->findOneByColumn('email',$user->email);
            if($verify_user && $verify_user->active) {
                $message='Votre compte est bloqué, veuillez contacter l\'admin de site';
                return redirect(config('app.frontend_url') . '/connexion?status=error&message=' . $message);
            }
            $data=['email'=>$user->email,'first_name'=>$user->name,
                  'last_name'=>'','image'=>$user->avatar
            ];
            $token=$this->service->createOrUpdateProvider($data);
            return redirect(config('app.frontend_url').'/connexion?&status=1&v='.$token);
        }
        return redirect(config('app.frontend_url').'/connexion?status=error&message='.$message);
    }
    public function currentUserModule(){
         return response($this->service->repos->currentUserModules(),200);
    }
    public function teacherModules(){
        return response($this->service->repos->teacherModules(),200);
    }
}
