<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\PHPMailerController;

Route::get('/', function () {
    return view('welcome');
});

//location
Route::get('location', function () {
    return view('location');
});

Auth::routes(['verify' => true]);

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])
->middleware(['auth', 'verified'])
->name('home');

//Mail for contact us
Route::get('contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('contact', [ContactController::class, 'submitForm'])->name('contact.submit');

//Mail send to all users
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::post('users/send-mail', [UserController::class, 'sendMail'])->name('users.sendMail');

//Mail with static attachment send to user
Route::get('attachment', [AttachmentController::class, 'attachment'])->name('users.attachment');

//Mail with attachment send to user
Route::get('upload', [UserController::class, 'showUploadForm'])->name('upload.form');
Route::post('upload', [UserController::class, 'uploadFile'])->name('upload.file');

//Send Mail using PHPMailer
Route::get('send-email',[PHPMailerController::class, 'index'])->name('send.email');
Route::post('send-email',[PHPMailerController::class, 'store'])->name('send.email.post');