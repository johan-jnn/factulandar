<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureClientIsFromUser;
use App\Http\Middleware\EnsureUserHasSociety;
use Illuminate\Support\Facades\Route;

Route::view("/", 'pages.index');

Route::middleware("auth")->group(function () {
  // Account settings
  Route::view("/me", 'pages.account.index')->name('user.edit');
  Route::put("/me", [UserController::class, 'update'])->name('user.update');
  Route::get("/me/logout", [UserController::class, "logout"])->name("user.do_logout");
  Route::delete("/me", [UserController::class, "delete"])->name('user.destroy');

  // Application
  Route::view("/app", "pages.dashboard.index")->name("app.index");

  // Societies
  Route::resource('/me/societies', SocietyController::class)->only(['index', 'store', 'update', 'destroy']);

  // Clients
  Route::resource('/app/clients', ClientController::class)->except(['index']);

  // Invoices
  Route::resource('/app/clients/{client}/invoices', InvoiceController::class);
});

Route::middleware("guest")->group(function () {
  // Account
  Route::view("/login", 'pages.account.login')->name("user.login");
  Route::view("/register", 'pages.account.register')->name("user.register");
  Route::post("/me/login", [UserController::class, "login"])->name("user.do_login");
  Route::post("/me/register", [UserController::class, "register"])->name("user.do_register");
});
