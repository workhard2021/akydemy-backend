<?php

namespace App\Http\Controllers;

use App\Enums\eStatus;
use App\Enums\eStatusAttestation;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function __construct(private UserService $service){}

    public function index($search='',$countryId='',$categorieId='',$dateBegin='',$dateEnd=''){
        return $this->service->repos->searchUserText($search,$countryId,$categorieId,$dateBegin,$dateEnd);
    }
    public function userByStatus(){
      
        return $this->service->repos->userByStatus(eStatus::TEACHER->value);
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
                "password" => "required|string|min:3",
                "country_id"=> "nullable|numeric"
            ]);
            $data['password']=Hash::make($data['password']);
            return response($this->service->create($data),201);
    }

    public function update(Request $request,$id)
    {     
           $data = $request->validate([
                'active' => 'boolean',
                'status' => 'string|'.Rule::in(eStatus::getValues())
           ]);
           // other change
           return response($this->service->update($id,$data),200);
    }

    public function updateCurrentUser(Request $request,$id)
    {    
           $data = $request->validate([
               "email"=> "required|email|max:100",
               "tel"=> "nullable|string|max:15",
               "first_name"=>"required|string|max:30",
               "last_name"=> "required|string|max:30",
               "password"=> "nullable|string|min:3",
               "country_id"=> "nullable|numeric"
           ]);
           $id=$request->user()->id;
           if($this->service->repos->exists("email",$data['email'],'id',$id)){
               return response(['errors'=>['email'=>['Email existe déja'] ]],422);
           } 
           if(!empty($data['password'])){
               $data['password']=Hash::make($data['password']);
           }
           return response($this->service->update($id,$data),200);
    }
    
    public function currentUser(Request $request,$id)
    {
        return response($this->service->repos->currentUser($request->user()->id),200);
    }

    public function deleteCurrentUser(Request $request)
    {
        return response($this->service->delete($request->user()->id),204);
    }
 
    public function destroy(Request $request,$id)
    {   
        return response($this->service->delete($request->user()->id),204);
    }
    
    public function login(Request $request)
    {   
        $user = $this->service->repos->findOneByColumn('email',$request->input('email', ''));
        if($user && $user->active){
            return  response(['errors' => ['message' => ['Votre compte est bloqué, veuillez contacter l\'admin de site']]], 422);
        }
        if($user && Hash::check($request->input('password', ''), $user->password)) {
            $token= $user->createToken($this->service->tokenName)->plainTextToken;
            return response($token,200);
        }
        return  response(['errors'=>['message'=>['Mot de passe ou email est invalide']]],422);
    }

    public function logout(Request $request,$id)
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
            return redirect(config('app.frontend_url'). '/connexion?&status=1&v='.$token);
        }
        return redirect(config('app.frontend_url') . '/connexion?status=error&message='.$message);
    }
}
