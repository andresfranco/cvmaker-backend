<?php
namespace App\Controller;
use MartynBiz\Slim3Controller\Controller;
class GeneralController extends  Controller
{
  public function get_base_path()
  {
    return $this->request->getUri()->withPath('');
  }
  
  public function validate_email($email)
  {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
    {
      $error_code=0;
      $error_message="valid email";
    } 
    else 
    {
      $error_code=1;
      $error_message="invalid email";
    }
    $result =['error_code'=>$error_code,'error_message'=>$error_message];
    return $result;
  }
    
}