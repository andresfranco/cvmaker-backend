<?php
namespace App\Controller;
use MartynBiz\Slim3Controller\Controller;
use App\Models\Language as Language;
use App\Controller\GeneralController as General;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

Class LanguageController extends GeneralController
{
    
public function index()
{
    $general= new General($this->app);
    return $this->render('/Admin_Module/index.html.twig', array(
        'title'=>'Languages','base_path'=>$this->get_base_path()
    ));
}
public function get_all_languages()
  
{
 
  return json_encode( Language::all());
}
    
public function create()
{
    
  try{
    $values= $this->request->getParsedBody();
    $language = new Language;
    $language->code =$values['code']; 
    $language->language =$values['language'];
    $language->createuser ='admin';
    $language->modifyuser ='admin';
    $language->save();
    $result =["error_code"=>$this->response->getStatusCode(),"error_message"=>""];
  }
  catch(\Illuminate\Database\QueryException $e){
   $result =["error_code"=>"1","error_message"=> $e->getMessage()];
  } 
    return json_encode($result);
}
   
 public function get_language_byid($id)
 {
   try { 
    $result = Language::findOrFail($id);
  } catch (ModelNotFoundException $e) 
  {
    $result =["error_code"=>"1","error_message"=>"Item not found"];
  } 
   return json_encode($result);
 }
    
public function show($id)
{
  return $this->get_language_byid($id);
}
  
public function edit($id)
{
  return $this->get_language_byid($id);
   
}
public function update($id)
{
    try { 
    $values= $this->request->getParsedBody();  
    $language = Language::findOrFail($id);
    $language->code =$values['code']; 
    $language->language =$values['language'];
    $language->modifyuser ='admin';
    $language->save(); 
    $result=["error_code"=>$this->response->getStatusCode(),"error_message"=>""];
    } catch (ModelNotFoundException $e) 
    {
    $result =["error_code"=>"1","error_message"=>"Item not found"];
    }  
    
    return json_encode($result);
}
public function destroy($id)
{   
  try{
     $Language = Language::findOrFail($id); 
     $Language->delete();
     $result=["error_code"=>$this->response->getStatusCode(),"error_message"=>""];
    }catch(ModelNotFoundException $e)
   {
     $result=["error_code"=>"1","error_message"=>"Item not found"];
   }
   return json_encode($result);
    
}
 
}