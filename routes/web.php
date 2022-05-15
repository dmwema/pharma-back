<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LoginAccessController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware' => 'cors'], function () use ($router) {

    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->get('/generate', ['as' => 'generate', 'uses' => 'LoginAccessController@generate']);
    
    $router->post('/check', ['as' => 'check', 'uses' => 'LoginAccessController@check']);
    
    $router->post('/checklink', ['as' => 'checklink', 'uses' => 'LoginAccessController@checklink']);
    
    $router->post('/edit-credentials', ['as' => 'editcredentials', 'uses' => 'UserController@edit_credentials']);
    
    $router->post('/login', ['as' => 'login', 'uses' => 'UserController@login']);

    $router->group(['prefix' => 'teacher'], function () use ($router) {
        $router->get('/courses/{teacher_id}', ['as' => 'teacher-courses', 'uses' => 'TeacherController@courses']);
        $router->get('/works/{teacher_id}', ['as' => 'teacher-courses-works', 'uses' => 'TeacherController@works']);
        
        $router->post('/work', ['as' => 'add-work', 'uses' => 'AnnualWorkController@store']);
        $router->delete('/work', ['as' => 'delete-work', 'uses' => 'AnnualWorkController@destroy']);
    });

});

