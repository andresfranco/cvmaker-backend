<?php
ini_set('include_path', '../src/');
include("Controllers/GeneralController.php");
use MartynBiz\Slim3Controller\Controller;
use PHPUnit\Framework\TestCase;

Class GeneralControllerTest extends TestCase
{
    protected $appStub;
    public function setUp()
    {
        // Create a stub for the Slim\App class.
        $this->appStub = $this->getMockBuilder('Slim\App')
             ->disableOriginalConstructor()
             ->getMock();
      
    }
    public function testInitialization()
    {
        $controller = new  App\Controller\GeneralController($this->appStub);
         $this->assertTrue($controller instanceof App\Controller\GeneralController);
    }
  
    public function test_validate_email()
    {
      $result = [];
      $controller = new  App\Controller\GeneralController($this->appStub);
       
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