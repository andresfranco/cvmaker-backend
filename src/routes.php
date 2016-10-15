<?php
// Routes
$app->group('/languages', function () use ($app) {
  $controller = new App\Controller\LanguageController($app);
  $app->get('/list', $controller('index'));
  $app->get('/get_all_languages', $controller('get_all_languages'));
  $app->post('/create_language', $controller('create'));
  $app->get('/show/{id}', $controller('show'));
  $app->get('/edit/{id}', $controller('edit'));
  $app->put('/update/{id}', $controller('update'));
  $app->delete('/destroy/{id}', $controller('destroy'));
});
$app->group('/users', function () use ($app) {
  $controller = new App\Controller\UserController($app);
  $app->get('/get_all_users', $controller('get_all_users'));
  $app->post('/create_user', $controller('create'));
});

$app->group('/security', function () use ($app) {
  $controller = new App\Controller\SecurityController($app);
  $app->post('/validate_user_email', $controller('validate_user_email'));
  $app->post('/validate_password', $controller('validate_password'));
});