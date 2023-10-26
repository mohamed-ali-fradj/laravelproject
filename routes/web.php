<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[UserController::class,"showCorrectHomePage"])->name('login');


Route::post('/register',[UserController::class,'register'])->middleware('guest');
Route::post('/login',[UserController::class,'login'])->middleware('guest');
Route::post('/logout',[UserController::class,'logout'])->middleware('mustBeLoggedIn');
Route::get('/create-post',[postController::class,'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post',[postController::class,'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}',[postController::class,'viewSinglePost']);   

//Profile Related Routes
Route::get('/profile/{Tempuser:username}',[UserController::class,'profile']);
Route::delete('/post/{post}',[postController::class,'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit',[postController::class,'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}',[postController::class,'actuallyupdate'])->middleware('can:update,post');

Route::get('/manage-avatar',[UserController::class,'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar',[UserController::class,'storeAvatar'])->middleware('mustBeLoggedIn');