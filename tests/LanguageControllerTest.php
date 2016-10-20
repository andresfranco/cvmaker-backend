<?php
//Import Models
require __DIR__ . '/../vendor/autoload.php';
foreach (scandir(__DIR__ . '/../src/Models') as $filename) 
{
  $path =__DIR__ . '/../src/Models' . '/' . $filename;
  if (is_file($path)){ require $path; }
}
//Import Controllers
foreach (scandir(__DIR__ . '/../src/Controllers') as $filename) 
{
  $path =__DIR__ . '/../src/Controllers' . '/' . $filename;
  if (is_file($path)){ require $path; }
}
//Import Main class for tests
require __DIR__ . '/TestsSetup.php';
//Import Slim3 Controllers
use MartynBiz\Slim3Controller\Controller;
//Import PHP unit classs
use PHPUnit\Framework\TestCase;

Class LanguageControllerTest extends TestsSetup
 
{   
  public $main_route ='/languages';
  public $test_id =['exist_id'=>1 ,'not_exist_id'=>9999,'update_id'=>1,'delete_id'=>10];
   
  public function testInitialization()
  {
    $controller = new App\Controller\LanguageController($this->app);
    $this->assertTrue($controller instanceof App\Controller\LanguageController);
  }

  public function test_get_all_languages()
  {  
    //Test not null values on query 
    $controller = new App\Controller\LanguageController($this->app);
    $result =json_decode($controller->get_all_languages());
    $this->assertNotNull($result[0]);
  }
  
  public function test_create_action()
  {
    //Dont create a language null values.
    $this->dispatch('/languages/create_language','POST',['code'=>null,'language'=>null]);
    $this->assertEquals(200,$this->response->getStatusCode());    


    /*WARNING: this test create  data form data base usign the $this->test_id['update_id'] value
    Create language test 
    ------------------Uncomment this for test insert data -----------------------------------------------
    //$this->dispatch('/languages/create_language','POST',['code'=>'unit_test','language'=>'unit_test']);
    //$this->assertEquals(200,$this->response->getStatusCode()); 
    -----------------------------------------------------------------------------------------------------
    */

  }
  
  public function test_get_language_byid_action()
  {
    // Language found
    $controller = new App\Controller\LanguageController($this->app);
    $result =json_decode($controller->get_language_byid($this->test_id['exist_id']),true);
    $this->assertNotNull($result['code']);

    //Language not found
    $result =json_decode($controller->get_language_byid($this->test_id['not_exist_id']),true);
    $this->assertEquals("1",$result['error_code']);

  }  
 
  
  public function test_show_action()
  {

    // Language found
    $controller = new App\Controller\LanguageController($this->app);
    $result =json_decode($controller->show($this->test_id['exist_id']),true);
    $this->assertNotNull($result['code']);

    //Language not found
    $result =json_decode($controller->show($this->test_id['not_exist_id']),true);
    $this->assertEquals("1",$result['error_code']);

  }  
 
  public function test_update_action()
  {

  //Update empty values 
    try{
      $this->dispatch('/languages/update/'.$this->test_id['not_exist_id'] ,'PUT',['code'=>null,'language'=>null]);
    } catch (PDOException $e){
      $this->assertNotNull($e->getCode());
    }
  /*WARNING: this test update data form data base usign the $this->test_id['update_id'] value
  ------------------Uncomment this for test update data -----------------------------------------------
  //$this->dispatch('/languages/update/'.$this->test_id['update_id'] ,'PUT',['code'=>'upd_test','language'=>'upd_test']);
  //$this->assertEquals(200,$this->response->getStatusCode());
  ------------------------------------------------------------------------------------------------------
  */


  } 
  
  public function test_delete_action()
  {
    //Delete with not exist id.
    $controller = new App\Controller\LanguageController($this->app);
    $result =json_decode($controller->destroy($this->test_id['not_exist_id']),true);
    $this->assertEquals("1",$result['error_code']);

    /*WARNING: this test update data form data base usign the $this->test_id['update_id'] value
    ------------------Uncomment this for test delete data -----------------------------------------------
    Delete record 
    $controller = new App\Controller\LanguageController($this->app);
    $result =json_decode($controller->destroy($this->test_id['delete_id']),true);
    $this->assertEquals("0",$result['error_code']);
    -------------------------------------------------------------------------------------------------------
    */
  } 
  
  public function test_invalid_routes()
  {
    //Validate  not allowwed methods invalid_route_code = 405
    $this->validate_route_code( ['POST','PUT','DELETE'],$this->main_route.'/get_all_languages',$this->invalid_route_code );

    $this->validate_route_code( ['GET','PUT','DELETE'],$this->main_route.'/create_language',$this->invalid_route_code );

    $this->validate_route_code( ['POST','PUT','DELETE'],$this->main_route.'/show/{id}',$this->invalid_route_code );

    $this->validate_route_code( ['POST','PUT','DELETE'],$this->main_route.'/edit/{id}',$this->invalid_route_code );

    $this->validate_route_code( ['POST','GET','DELETE'],$this->main_route.'/update/{id}',$this->invalid_route_code );

    $this->validate_route_code( ['POST','GET','PUT'],$this->main_route.'/destroy/{id}',$this->invalid_route_code );

  } 
  
  public function test_valid_routes()
  {
    //Validate  not allowwed methods valid_route_code = 200
    $this->validate_route_code( ['GET'],$this->main_route.'/get_all_languages',$this->valid_route_code );

    $this->validate_route_code( ['POST'],$this->main_route.'/create_language',$this->valid_route_code);

    $this->validate_route_code( ['GET'],$this->main_route.'/show/{id}',$this->valid_route_code );

    $this->validate_route_code( ['GET'],$this->main_route.'/edit/{id}',$this->valid_route_code );

    $this->validate_route_code( ['PUT'],$this->main_route.'/update/{id}',$this->valid_route_code );

    $this->validate_route_code( ['DELETE'],$this->main_route.'/destroy/{id}',$this->valid_route_code);


  } 
  
   
}