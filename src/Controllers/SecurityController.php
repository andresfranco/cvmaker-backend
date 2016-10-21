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
      $user= User::where('email',$email)->firstOrFail();
      $user_data =["email"=>$user->email,"salt"=>$user->salt,"password"=>$user->password,"name"=>$user->name,"lastname"=>$user->lastname];
      $result=["error_code"=>$this->response->getStatusCode(),"error_message"=>"","user_data"=> $user_data ]; 
    }
    catch (ModelNotFoundException $e) 
    {
      $result =["error_code"=>"1","error_message"=>"User not found"];
    }  
    return $result;
  }

  public function validate_user_email($email)
  {  
    try
    {
      $user= User::where('email',$email)->firstOrFail();
      $result=["error_code"=>"0","error_message"=>"valid email"]; 
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
    $validate_email= $this->validate_user_email($email);

    if ($validate_email['error_code'] =="0")
    {
      $data  = $this->get_user_data($email);
      $user_data =$data['user_data'];
      $passwordhash = hash('sha256', $userdata['salt'] . hash('sha256', $password) );

      if($passwordhash != $user_data ["password"]) //incorrect password
      {
      $result =["error_code"=>"1","error_message"=>'invalid password'] ;
      }
      else //valid password
      {
      $result =["error_code"=>"0","error_message"=>"valid authentication",'username'=>$user_data ["name"].' '.$user_data ["lastname"]] ;
      }    
    }
    else
    {
      $result =["error_code"=>$validate_email['error_code'],"error_message"=>$validate_email['error_message']] ;
    }
    return json_encode($result);
    }
  
}