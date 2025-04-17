<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('app.index'))->with([
                "message" => "Vous venez de vous connecter !"
            ]);
        }

        return back()->withErrors([
            "message" => "Les identifiants de connexion ne sont pas valides."
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->intended("/")->with([
            "message" => "Vous êtes désormais déconnecté !"
        ]);
    }

    public function register(Request $request)
    {
        $user_infos = $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|confirmed",
        ]);

        $created_user = User::create($user_infos);
        Auth::login($created_user);
        return redirect()->intended(route('app.index'))->with([
            "message" => "Vous venez de créer votre compte !"
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $user_new_infos = $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|confirmed",
        ]);

        $user->update($user_new_infos);

        return redirect()->intended(route('user.edit'))->with([
            "message" => "Vos informations ont bien été modifiées"
        ]);
    }

    public function delete(Request $request)
    {
        Auth::user()->delete();
        Auth::logout();
        return redirect('/')->with([
            "message" => "Votre compte a bien été supprimé"
        ]);
    }
}
