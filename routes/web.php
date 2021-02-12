<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('student');
});

Route::post('students_list',[
    'as' => 'students_list.index',
    'uses' => 'App\Http\Controllers\StudentController@index'
]);
Route::resource('students','App\Http\Controllers\StudentController')->middleware('csrf.enable', ['except' => [
                'index','update']]);
