<?php
//Import Models
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

Class GeneralControllerTest extends TestsSetup
{
   
  public function testInitialization()
  {
    $controller = new  App\Controller\GeneralController($this->app);
    $this->assertTrue($controller instanceof App\Controller\GeneralController);
  }
  
  public function test_validate_email()
  {
    $result = [];
    $controller = new  App\Controller\GeneralController($this->app);

    // error_code = 0 is a valid email  , error_code=1 invalid email.

    $email ='koloinotzente@gmail.com'; //valid email
    $result =$controller->validate_email($email);
    $this->assertEquals(0,$result['error_code']);

    $email ='koloinotzentegmailcom';  //Email without .

    $result =$controller->validate_email($email);
    $this->assertEquals(1,$result['error_code']);

    $email ='koloinotzentegmail.com';  //Email without @

    $result =$controller->validate_email($email);
    $this->assertEquals(1,$result['error_code']);

    $email ='kolo.inotzente@gmailcom';  //Email without . after @

    $result =$controller->validate_email($email);
    $this->assertEquals(1,$result['error_code']);


  } 
      
  
}