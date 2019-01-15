<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'API'], function () {
    Route::get('heatmap', 'UserController@heatmap')->name("api.heatmap");

    Route::post('app', 'UserController@app');
    Route::post('login', 'UserController@login');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'UserController@logout');

        Route::post('notice', 'UserController@getNotice');
        Route::post('assignments', 'UserController@getAssignments');

        Route::group(['prefix' => 'assignment/{assignment}'], function () {
            Route::post('finish', 'UserController@finishAssignment');
            Route::post('reset', 'UserController@resetAssignment');
        });

        Route::group(['namespace' => 'Forum'], function () {
            Route::group(['prefix' => 'course/{course}'], function () {
                Route::post('check/student/{user?}', 'CourseController@checkStudent')->name('api.forum.course.check.student');
                Route::post('check/admin/{user?}', 'CourseController@checkAdmin')->name('api.forum.course.check.admin');
                Route::post('add/student', 'CourseController@addStudent')->name('api.forum.course.add.student.myself');
                Route::post('delete/user', 'CourseController@deleteUser')->name('api.forum.course.delete.user.myself');
                Route::group(['middleware' => 'admin'], function () {
                    // Only admin can add admin, and control other users.
                    Route::post('add/student/{user?}', 'CourseController@addStudent')->name('api.forum.course.add.student');
                    Route::post('add/admin/{user?}', 'CourseController@addAdmin')->name('api.forum.course.add.admin');
                    Route::post('delete/user/{user?}', 'CourseController@deleteUser')->name('api.forum.course.delete.user');
                });
            });
        });
    });
});
