<?php
return [
  'settings' => [
    'displayErrorDetails' => true, 
    'db' => [
      // Eloquent configuration
      'driver'    => 'mysql',
      'host'      => 'localhost',
      'database'  => 'cvmaker',
      'username'  => 'dbadmin',
      'password'  => 'picoromz2509',
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => ''
    ],// set to false in production
    // Renderer settings
    'renderer' => [
      'template_path' => __DIR__ . '/../templates/',
    ],
    // Monolog settings
    'logger' => [
      'name' => 'slim-app',
      'path' => __DIR__ . '/../logs/app.log',
    ],
  ],
];