<?php
namespace App\Controller;
use MartynBiz\Slim3Controller\Controller;
use App\Models\User as User;
use App\Controller\GeneralController as GeneralController;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

Class SecurityController extends GeneralController
{
  function create_salt()
  {
    $string = md5(uniqid(rand(), true));
    return substr($string, 0, 3);
  }

  function set_hash_password($salt,$password)
  {
    $hash = hash('sha256', $password);  
    $hashpassword = hash('sha256',$salt.$hash);  
    return $hashpassword;
  }
  
  function get_user_data($email)
  {
    try
    {
      $user= User::where('email',$email)->orWhere('user_name',$email)->firstOrFail();
      $user_data =["email"=>$user->email,"salt"=>$user->salt,"password"=>$user->password,"username"=>$user->user_name,"name"=>$user->name,"lastname"=>$user->lastname];
      $result=["error_code"=>"0","error_message"=>"","user_data"=> $user_data ]; 
    }
    catch (ModelNotFoundException $e) 
    {
      $result =["error_code"=>"1","error_message"=>"User not found"];
    }
    return $result;
  }


  
  public function validate_password($email,$password)
  {   
    $values= $this->request->getParsedBody();
    $email =$values['email'];
    $password =$values['password'];
    $data  = $this->get_user_data($email);

    if ($data['error_code'] =="0")
    {
      $user_data =$data['user_data'];
      $passwordhash = hash('sha256', $userdata['salt'] . hash('sha256', $password) );

      if($passwordhash != $user_data ["password"]) //incorrect password
      {
        $result =["error_code"=>"1","error_message"=>"invalid password"] ;
      }
      else //valid password
      {
        $result =["error_code"=>"0","error_message"=>"valid authentication","user_data"=>["username"=>$user_data["username"],"name"=>$user_data["name"],"lastname"=>$user_data["lastname"],"email"=>$user_data["email"]]];
      }    
    }
    else
    {
      $result =["error_code"=>$data['error_code'],"error_message"=>$data['error_message']] ;
    }
    return json_encode($result);
  }
  
  public function get_current_user(array $user_data)
  {
    $this->user_data =$user_data;
    return $this->user_data;
  }

}