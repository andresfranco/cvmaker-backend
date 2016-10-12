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