<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureClientIsFromUser;
use Illuminate\Support\Facades\Route;

Route::view("/", "index");

Route::middleware("auth")->group(function () {
  Route::view("/app", "pages.dashboard.index")->name("dashboard");
  Route::post("/me/logout", [UserController::class, "logout"])->name("perform_logout");
  Route::view("/me", 'pages.account')->name('edit_user');
  Route::put("/me", [UserController::class, 'update'])->name('perform_user_edition');

  Route::post("/app/client", [ClientController::class, "store"])->name("insert_client");

  Route::middleware(EnsureClientIsFromUser::class)->group(function () {
    Route::get("/app/client/{client}", [ClientController::class, "show"])->name("client");
    Route::delete("/app/client/{client}", [ClientController::class, "destroy"])->name("delete_client");
  });
});

Route::middleware("guest")->group(function () {
  Route::view("/login", "pages.login")->name("login");
  Route::view("/register", "pages.register")->name("register");

  Route::post("/me/login", [UserController::class, "login"])->name("perform_login");
  Route::post("/me/register", [UserController::class, "register"])->name("perform_register");
});
