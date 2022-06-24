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
use App\Http\Controllers\AnnualWorkController;
use App\Http\Controllers\CoteController;
use App\Http\Controllers\DeliberationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ExamScheduleController;

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
            $router->get('/works/with/{teacher_id}', ['as' => 'teacher-courses-works-with', 'uses' => 'ProfessorController@works_with']);

            $router->post('/work', ['as' => 'add-work', 'uses' => 'AnnualWorkController@store']);
            $router->post('/delete_work', ['as' => 'delete-work', 'uses' => 'AnnualWorkController@destroy']);
        });

        // COURSES
        $router->get('/courses/{promotion_id}', ['as' => 'courses', 'uses' => 'CourseController@index']);
        $router->post('/add-course', ['as' => 'store_course', 'uses' => 'CourseController@store']);
        $router->post('/delete-course', ['as' => 'delete_course', 'uses' => 'CourseController@destroy']);
        $router->post('/update-course', ['as' => 'update_course', 'uses' => 'CourseController@update']);
        $router->get('/courses/all-cotes/{course_id}-{session_id}', ['as' => 'all_course_cotes', 'uses' => 'CourseController@all_course_cotes']);

        //JURY
        $router->get('/jury/{promotion_id}', ['as' => 'jury', 'uses' => 'JuryController@index']);
        $router->get('/post-jury/{promotion_id}/{professor_id}', ['as' => 'juryPosts', 'uses' => 'JuryController@posts']);
        $router->post('/add-jury-member', ['as' => 'store_jury_member', 'uses' => 'JuryController@store']);
        $router->post('/delete-jury-member', ['as' => 'delete_jury_member', 'uses' => 'JuryController@destroy']);
        $router->post('/update-jury', ['as' => 'update_jury', 'uses' => 'JuryController@update']);

        // STUDENTS
        $router->get('/students/{promotion_id}', ['as' => 'students', 'uses' => 'StudentController@index']);
        $router->post('/add-student', ['as' => 'store_student', 'uses' => 'StudentController@store']);
        $router->post('/delete-student', ['as' => 'delete_student', 'uses' => 'StudentController@destroy']);
        $router->post('/update-student', ['as' => 'update_student', 'uses' => 'StudentController@update']);

        //COTES
        $router->get('/cotes/{promotion_id}', ['as' => 'cotes', 'uses' => 'CoteController@index']);
        $router->post('/update-cote', ['as' => 'update_cote', 'uses' => 'CoteController@update']);

        //DELIBERATION
        $router->get('/deliberations/{promotion}', ['as' => 'deliberation', 'uses' => 'DeliberationController@index']);
        $router->post('/add-deliberation', ['as' => 'add-deliberation', 'uses' => 'DeliberationController@store']);
        $router->post('/update-deliberation', ['as' => 'update_deliberation', 'uses' => 'DeliberationController@update']);
        $router->post('/delete-deliberation', ['as' => 'delete_deliberation', 'uses' => 'DeliberationController@destroy']);
        $router->post('/publish-deliberation', ['as' => 'publish_deliberation', 'uses' => 'DeliberationController@publish']);
        $router->post('/send-cotes', ['as' => 'send_cotes', 'uses' => 'StudentDeliberationController@sendCotes']);

        //SESSIONS
        $router->get('/sessions/{promotion_id}', ['as' => 'session', 'uses' => 'SessionController@index']);
        $router->post('/add-session', ['as' => 'add-session', 'uses' => 'SessionController@store']);
        $router->post('/update-session', ['as' => 'update_session', 'uses' => 'SessionController@update']);
        $router->post('/delete-session', ['as' => 'delete_session', 'uses' => 'SessionController@destroy']);

        //SCHEDULES
        $router->post('/add-schedule', ['as' => 'add-schedule', 'uses' => 'ExamScheduleController@store']);
        $router->post('/update-schedule', ['as' => 'update_schedule', 'uses' => 'ExamScheduleController@update']);
        $router->post('/delete-schedule', ['as' => 'delete_schedule', 'uses' => 'ExamScheduleController@destroy']);

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
                $router->get('me', ['as' => 'me', 'uses' => 'AuthController@me']);
            });
        });
    });
});
