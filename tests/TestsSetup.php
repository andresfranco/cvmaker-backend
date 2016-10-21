<?php
use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
Class TestsSetup extends TestCase
 
{ 
  public function setUp()
  {
    $settings = require __DIR__ . '/../src/settings.php';
    $app = new \Slim\App($settings);
    // Get container
    $container = $app->getContainer();
    //Configure Eloquent
    $capsule = new Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container->get('settings')['db']);
    $capsule->bootEloquent();

    require __DIR__ . '/../src/routes.php';    
    $this->app =$app;

    $this->invalid_route_code =405;
    $this->valid_route_code=200;


  }
  
  protected function dispatch($path, $method='GET', $data=array())
  {
    // Prepare a mock environment
    $env = \Slim\Http\Environment::mock(array(
    'REQUEST_URI' => $path,
    'REQUEST_METHOD' => $method,
    'CONTENT_TYPE' => 'application/json',
    ));

    // Prepare request and response objects
    $uri = Uri::createFromEnvironment($env);
    $headers = Headers::createFromEnvironment($env);
    $cookies = [];
    $serverParams = $env->all();
    $body = new RequestBody();
    if (!empty($data)) 
    {
     $body->write(json_encode($data));
    }

    // create request, and set params
    $req = new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    $res = new Response();

    $this->headers = $headers;
    $this->request = $req;
    $this->response = call_user_func_array($this->app, array($req, $res));
  }
  
  public function validate_route_code(array $not_permited_methods,string $route,int $http_code )
  {
    foreach ($not_permited_methods as $method)
    {
      $this->dispatch($route,$method);   
      $this->assertEquals($http_code,$this->response->getStatusCode());
    } 
  }  
   
}