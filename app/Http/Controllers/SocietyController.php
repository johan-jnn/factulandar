<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Auth;
use Illuminate\Http\Request;

class SocietyController
{
    public static function ensureUserHasSociety(Society $society)
    {
        abort_if(
            $society->owner_id !== Auth::user()->id,
            404
        );
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
            "address" => "required|min:15",
            "paiement_terms" => "required|min:10",
            "invoices_no_format" => "nullable|string"
        ]);

        $society_fillables["owner_id"] = Auth::user()->id;
        Society::create($society_fillables);

        return redirect()->intended(route('societies.index'))
            ->with([
                'message' => "{$society_fillables['name']} vient d'être créée"
            ]);
    }

    public function update(Request $request, Society $society)
    {
        SocietyController::ensureUserHasSociety($society);

        $updated_society_info = $request->validate([
            "new_name" => "required|max:50",
            "new_address" => "required|min:15",
            "new_paiement_terms" => "required|min:10",
            "new_invoices_no_format" => "nullable|string"
        ]);
        $society->update([
            "name" => $updated_society_info["new_name"],
            "address" => $updated_society_info["new_address"],
            "paiement_terms" => $updated_society_info["new_paiement_terms"],
            "invoices_no_format" => $updated_society_info["new_invoices_no_format"],
        ]);

        return to_route('societies.index')->with([
            'message' => "{$society->name} a bien été modifiée"
        ]);
    }

    public function destroy(Request $request, Society $society)
    {
        SocietyController::ensureUserHasSociety($society);

        $society->delete();

        return to_route('societies.index')->with([
            'message' => "{$society->name} vient d'être supprimée"
        ]);
    }
}
