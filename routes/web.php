<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\LoginAccessController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JuryController;
use App\Http\Controllers\StudentController;

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

    $router->group(['prefix' => 'api'], function () use ($router) {

        $router->get('/', function () use ($router) {
            return $router->app->version();
        });

        $router->get('/send', ['as' => 'testemail', 'uses' => 'MailController@teste']);

        $router->post('/generate', ['as' => 'generate', 'uses' => 'LoginAccessController@generate']);

        $router->post('/check', ['as' => 'check', 'uses' => 'LoginAccessController@check']);

        $router->post('/checklink', ['as' => 'checklink', 'uses' => 'LoginAccessController@checklink']);

        $router->post('/edit-credentials', ['as' => 'editcredentials', 'uses' => 'UserController@edit_credentials']);

        $router->post('/login', ['as' => 'login', 'uses' => 'UserController@login']);

        $router->post('/add-professor', ['as' => 'store_professor', 'uses' => 'ProfessorController@store']);

        $router->post('/delete-professor', ['as' => 'delete_professor', 'uses' => 'ProfessorController@destroy']);

        $router->post('/update-professor', ['as' => 'update_professor', 'uses' => 'ProfessorController@update']);

        $router->get('/professor', ['as' => 'all_professors', 'uses' => 'ProfessorController@index']);

        $router->get('/user', ['as' => 'all_users', 'uses' => 'UserController@index']);

        $router->get('/department', ['as' => 'get_depatments', 'uses' => 'DepartmentController@index']);

        $router->post('/admin-pass-verification', ['as' => 'verify_admin_pass', 'uses' => 'AdminController@verify_pass']);

        $router->group(['prefix' => 'teacher'], function () use ($router) {
            $router->get('/courses/{teacher_id}', ['as' => 'teacher-courses', 'uses' => 'ProfessorController@courses']);
            $router->get('/works/{teacher_id}', ['as' => 'teacher-courses-works', 'uses' => 'ProfessorController@works']);

            $router->post('/work', ['as' => 'add-work', 'uses' => 'AnnualWorkController@store']);
            $router->delete('/work', ['as' => 'delete-work', 'uses' => 'AnnualWorkController@destroy']);
        });

        // COURSES
        $router->get('/courses/{promotion_id}', ['as' => 'courses', 'uses' => 'CourseController@index']);
        $router->post('/add-course', ['as' => 'store_course', 'uses' => 'CourseController@store']);
        $router->post('/delete-course', ['as' => 'delete_course', 'uses' => 'CourseController@destroy']);
        $router->post('/update-course', ['as' => 'update_course', 'uses' => 'CourseController@update']);

        //JURY
        $router->get('/jury/{promotion_id}', ['as' => 'jury', 'uses' => 'JuryController@index']);
        $router->post('/add-jury-member', ['as' => 'store_jury_member', 'uses' => 'JuryController@store']);
        $router->post('/delete-jury-member', ['as' => 'delete_jury_member', 'uses' => 'JuryController@destroy']);
        $router->post('/update-jury', ['as' => 'update_jury', 'uses' => 'JuryController@update']);

        // STUDENTS
        $router->get('/students/{promotion_id}', ['as' => 'students', 'uses' => 'StudentController@index']);
        $router->post('/add-student', ['as' => 'store_student', 'uses' => 'StudentController@store']);
        $router->post('/delete-student', ['as' => 'delete_student', 'uses' => 'StudentController@destroy']);
        $router->post('/update-student', ['as' => 'update_student', 'uses' => 'StudentController@update']);


        // AUTH
        $router->group([
            'prefix' => 'auth',
            'namespace' => 'Auth'
        ], function () use ($router) {
            $router->post('login', ['as' => 'login', 'uses' => 'AuthController@login']);

            $router->group([
                'middleware' => 'auth:api',
            ], function () use ($router) {
                $router->post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
                $router->post('me', ['as' => 'me', 'uses' => 'AuthController@me']);
            });
        });
    });
});
