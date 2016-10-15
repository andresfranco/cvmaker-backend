<?php
namespace App\Controller;
use MartynBiz\Slim3Controller\Controller;
use App\Models\User as User;
use App\Controller\SecurityController as SecurityController;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

Class UserController extends SecurityController
{
  public function get_all_users()
  {
   return json_encode( User::all());
  }

  public function create()
  {
  $values= $this->request->getParsedBody();
  $valid_email =$this->validate_email($values['email']);
  if ($valid_email['error_code']==0)
  {
    try{
    $password_hash =$this->set_hash_password($salt,$values['password']);
    $salt= $this->create_salt();
    $user = new User;
    $user->email =$values['email']; 
    $user->password =$password_hash;
    $user->name =$values['name'];
    $user->lastname =$values['lastname'];
    $user->salt =$salt;
    $user->createuser ='admin';
    $user->modifyuser ='admin';
    $user->save();
    $result =["error_code"=>$this->response->getStatusCode(),"error_message"=>""];
    }
    catch(\Illuminate\Database\QueryException $e){
    $result =["error_code"=>"1","error_message"=> $e->getMessage()];
    } 
  } 
  else
  {
    $result =["error_code"=>"1","error_message"=> $valid_email['error_message']];
  } 
  return json_encode($result);   
  }  
  
}