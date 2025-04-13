<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureClientIsFromUser;
use App\Http\Middleware\EnsureUserHasSociety;
use Illuminate\Support\Facades\Route;

Route::view("/", "index");

Route::middleware("auth")->group(function () {
  // Account settings
  Route::view("/me", 'pages.account.index')->name('account');
  Route::put("/me", [UserController::class, 'update'])->name('perform_user_edition');
  Route::post("/me/logout", [UserController::class, "logout"])->name("perform_logout");
  Route::post("/me/delete", [UserController::class, "delete"])->name('perform_user_delete');

  // Societies
  Route::get('/me/societies', [SocietyController::class, 'index'])->name('account_societies');
  Route::post('/society', [SocietyController::class, 'store'])->name('perform_society_creation');
  Route::put('/society/{society}', [SocietyController::class, 'update'])->name('perform_society_edition');
  Route::delete('/society/{society}', [SocietyController::class, 'destroy'])->name('perform_society_deletion');

  // Application
  Route::view("/app", "pages.dashboard.index")->name("dashboard");

  // Clients
  Route::post("/app/client", [ClientController::class, "store"])->name("insert_client");
  Route::get("/app/client/{client}", [ClientController::class, "show"])->name("client");
  Route::get("/app/client/{client}/edit", [ClientController::class, "edit"])->name("manage_client");
  Route::put("/app/client/{client}", [ClientController::class, "update"])->name("update_client");
  Route::delete("/app/client/{client}", [ClientController::class, "destroy"])->name("delete_client");
});

Route::middleware("guest")->group(function () {
  // Account
  Route::view("/login", "pages.login")->name("login");
  Route::view("/register", "pages.register")->name("register");
  Route::post("/me/login", [UserController::class, "login"])->name("perform_login");
  Route::post("/me/register", [UserController::class, "register"])->name("perform_register");
});
