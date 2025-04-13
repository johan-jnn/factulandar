<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class SocietyController
{
    private function ensureUserHasSociety(Society $society)
    {
        if ($society->owner_id !== Auth::user()->id)
            throw back()->withErrors([
                'message' => 'Société introuvable.'
            ]);
    }

    public function index(Request $request)
    {
        $societies = Auth::user()->societies();
        return view('pages.account.societies', [
            'societies' => $societies
        ]);
    }

    public function store(Request $request)
    {
        $society_fillables = $request->validate([
            "name" => "required|max:50",
            "address" => "required|min:15"
        ]);

        $society_fillables["owner_id"] = Auth::user()->id;
        Society::create($society_fillables);

        return redirect()->intended(route('account'))
            ->with([
                'message' => "{$society_fillables['name']} vient d'être créée"
            ]);
    }

    public function update(Request $request, Society $society)
    {
        $this->ensureUserHasSociety($society);

        $updated_society_info = $request->validate([
            "new_name" => "required|max:50",
            "new_address" => "required|min:15"
        ]);
        $society->update([
            "name" => $updated_society_info["new_name"],
            "address" => $updated_society_info["new_address"],
        ]);

        return to_route('account')->with([
            'message' => "{$society->name} a bien été modifiée"
        ]);
    }

    public function destroy(Request $request, Society $society)
    {
        $this->ensureUserHasSociety($society);

        $society->delete();

        return to_route('account')->with([
            'message' => "{$society->name} vient d'être supprimée"
        ]);
    }
}
