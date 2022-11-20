<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Registration
Route::post('/Registration',[ApiController::class,'store_Registration'])->name('store.Registration');

//Goals
Route::post('/create_goal',[ApiController::class,'store'])->name('store.goals');
Route::get('/show_goals',[ApiController::class,'show'])->name('show.goals');
Route::post('/update_goals',[ApiController::class,'update'])->name('update.goals');
Route::get('/delete_goal/{id}',[ApiController::class,'destroy'])->name('delete.goals');

//Goal_images
Route::post('/insert_image',[ApiController::class,'store_image'])->name('store.Image');
Route::get('/show_all_images',[ApiController::class,'show_image'])->name('show.Image');

//Habit
Route::post('/create_habit',[ApiController::class,'store_habit'])->name('store.habit');
Route::get('/show_habit',[ApiController::class,'show_habit'])->name('show.habit');
Route::post('/update_habits',[ApiController::class,'update_habits'])->name('update.habits');
Route::post('/update_habits_status',[ApiController::class,'update_habits_status'])->name('update.habits.status');
Route::get('/destroy_habit/{id}',[ApiController::class,'destroy_habit'])->name('delete.habit');

//get all days
Route::get('/all_days',[ApiController::class,'show_days'])->name('show.days');

//get all priority
Route::get('/all_priority',[ApiController::class,'all_priority'])->name('show.priority');

//get all category_id
Route::get('/all_habit_category',[ApiController::class,'all_habit_category'])->name('show.category');


//to_do route
Route::post('/create_to_do',[ApiController::class,'store_to_do'])->name('store.todo');
Route::get('/show_to_do',[ApiController::class,'show_todo'])->name('show.todo');
Route::post('/update_to_do',[ApiController::class,'update_todo'])->name('update.todo');
Route::get('/destroy_todo/{id}',[ApiController::class,'destroy_todo'])->name('delete.todo');
Route::post('/update_to_do_status',[ApiController::class,'update_todo_status'])->name('update.todo.status');


//get calculations
Route::get('/get_calculations',[ApiController::class,'get_calculations'])->name('get.calculations');
//get  count
Route::get('/get_count',[ApiController::class,'get_count'])->name('get.count');


