<?php
if (PHP_SAPI == 'cli-server') {
  // To help the built-in PHP dev server, check if the request was actually for
  // something which should probably be served as a static file
  $file = __DIR__ . $_SERVER['REQUEST_URI'];
  if (is_file($file)) { return false; }
}
require __DIR__ . '/../vendor/autoload.php';
session_start();
// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);
// Get container
$container = $app->getContainer();
//Configure Eloquent
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['db']);
$capsule->bootEloquent();
// Register component on container - Configure Twig
$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig('../src/Views', ['cache' => '../cache']);
  $view->addExtension(new \Slim\Views\TwigExtension(
    $container['router'],
    $container['request']->getUri()
  ));
  return $view;
};
// Set up dependencies
require __DIR__ . '/../src/dependencies.php';
// Register middleware
require __DIR__ . '/../src/middleware.php';
//Register Models
foreach (scandir(__DIR__ . '/../src/Models') as $filename) {
  $path =__DIR__ . '/../src/Models' . '/' . $filename;
  if (is_file($path)){ require $path; }
}
//Register Controllers
foreach (scandir(__DIR__ . '/../src/Controllers') as $filename) {
  $path =__DIR__ . '/../src/Controllers' . '/' . $filename;
  if (is_file($path)){ require $path; }
}
// Register routes
require __DIR__ . '/../src/routes.php';
// Run app
$app->run();