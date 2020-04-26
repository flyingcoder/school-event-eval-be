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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Auth\RegisterController@create');


Route::group([

    'middleware' => 'api'

], function ($router) {

  //Auth Routes
  Route::group(['prefix' => 'auth'], function () {
      Route::post('login', 'Auth\AuthController@login');
      Route::post('logout', 'Auth\AuthController@logout');
      Route::post('refresh', 'Auth\AuthController@refresh');
      Route::post('me', 'Auth\AuthController@me');
  });

});

Route::group([
    'middleware' => 'jwt.auth'
], function ($router) {

  //Search API routes
  Route::group(['prefix' => 'search'], function () {
    Route::get('events', 'SearchController@events');
    Route::get('users', 'SearchController@users');
    Route::get('evaluations', 'SearchController@evaluations');
  });

  //Users API routes
  Route::group(['prefix' => 'users'], function () {
    Route::get('all', 'Users\UsersController@index');
    Route::get('delete/{id}', 'Users\UsersController@destroy');
    Route::get('all/{id}', 'Users\UsersController@getByType');
    Route::get('show/{id}', 'Users\UsersController@show');
    Route::post('update/{id}', 'Users\UsersController@edit');
    Route::post('import', 'Users\UsersController@import');
  });

  //College API routes
  Route::group(['prefix' => 'college'], function () {
    Route::post('create', 'Academics\CollegeController@store');
    Route::get('show/{id}', 'Academics\CollegeController@show');
    Route::get('all', 'Academics\CollegeController@index');
    Route::post('update/{id}', 'Academics\CollegeController@edit');
    Route::get('delete/{id}', 'Academics\CollegeController@destroy');
  });

  //Department API routes
  Route::group(['prefix' => 'department'], function () {
    Route::post('create', 'Academics\DepartmentController@store');
    Route::get('show/{id}', 'Academics\DepartmentController@show');
    Route::get('all/college/{id}', 'Academics\DepartmentController@filterByCollege');
    Route::get('all', 'Academics\DepartmentController@index');
    Route::post('update/{id}', 'Academics\DepartmentController@edit');
    Route::get('delete/{id}', 'Academics\DepartmentController@destroy');
  });

  //Courses API routes
  Route::group(['prefix' => 'course'], function () {
    Route::post('create', 'Academics\CourseController@store');
    Route::get('show/{id}', 'Academics\CourseController@show');
    Route::get('all', 'Academics\CourseController@index');
    Route::get('all/department/{id}', 'Academics\CourseController@filterByDept');
    Route::post('update/{id}', 'Academics\CourseController@edit');
    Route::get('delete/{id}', 'Academics\CourseController@destroy');
  });

  //Evaluation API routes
  Route::group(['prefix' => 'evaluation'], function () {
    Route::post('create', 'Evaluation\EvaluationController@store');
    Route::get('show/{id}', 'Evaluation\EvaluationController@show');
    Route::get('all', 'Evaluation\EvaluationController@index');
    Route::get('all/{id}', 'Evaluation\EvaluationController@getEvaluationsByUser');
    Route::post('update/{id}', 'Evaluation\EvaluationController@edit');
    Route::get('delete/{id}', 'Evaluation\EvaluationController@destroy');
  });

  //Criteria API routes
  Route::group(['prefix' => 'criteria'], function () {
    Route::post('create', 'Evaluation\CriteriaController@store');
    Route::get('show/{id}', 'Evaluation\CriteriaController@show');
    Route::post('update/{id}', 'Evaluation\CriteriaController@edit');
    Route::get('delete/{id}', 'Evaluation\CriteriaController@destroy');
  });

  //Subcriteria API routes
  Route::group(['prefix' => 'subcriteria'], function () {
    Route::post('create', 'Evaluation\SubcriteriaController@store');
    Route::get('show/{id}', 'Evaluation\SubcriteriaController@show');
    Route::post('update/{id}', 'Evaluation\SubcriteriaController@edit');
    Route::get('delete/{id}', 'Evaluation\SubcriteriaController@destroy');
  });

  //Events API routes
  Route::group(['prefix' => 'event'], function () {
    Route::post('create', 'Events\EventController@store');
    Route::get('show/{id}', 'Events\EventController@show');
    Route::get('all', 'Events\EventController@index');
    Route::post('update/{id}', 'Events\EventController@edit');
    Route::get('all/{id}', 'Events\EventController@getEventsByUser');
    Route::get('delete/{id}', 'Events\EventController@destroy');
    Route::get('send/{id}', 'Events\EventController@sendEvaluation');
  });

  //Answers API routes
  Route::group(['prefix' => 'answer'], function () {
    Route::post('create', 'Answers\AnswersController@store');
    Route::get('event/{id}', 'Answers\AnswersController@showEventAnswers');
    Route::get('{id}/{event}', 'Answers\AnswersController@checkIfSubmitted');
  });
});


//College API routes
Route::group(['prefix' => 'college'], function () {
  Route::get('all', 'Academics\CollegeController@index');
});

//Department API routes
Route::group(['prefix' => 'department'], function () {
  Route::get('all/college/{id}', 'Academics\DepartmentController@filterByCollege');
});

//Courses API routes
Route::group(['prefix' => 'course'], function () {
  Route::get('all/department/{id}', 'Academics\CourseController@filterByDept');
});
