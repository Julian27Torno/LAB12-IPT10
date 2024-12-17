<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {

    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    $router->get('/', '\App\Controllers\HomeController@index');
    $router->get('/examinees', '\App\Controllers\ExamController@examinees');
    $router->get('/export-exam/{attempt_id}', '\App\Controllers\ExamController@exportAttemptToPDF');
    
    $router->get('/register', '\App\Controllers\ExamController@registrationForm');
    $router->post('/register', '\App\Controllers\ExamController@register');
    $router->get('/exam', '\App\Controllers\ExamController@exam');
    $router->post('/exam', '\App\Controllers\ExamController@exam');
    $router->get('/result', '\App\Controllers\ExamController@result');
  
    $router->get('/login', '\App\Controllers\ExamController@login');
    $router->post('/login', '\App\Controllers\ExamController@login');
    $router->get('/logout', 'ExamController@logout');
    $router->get('/exam', 'ExamController@exam');
    
    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
