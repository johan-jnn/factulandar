<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Auth;
use Illuminate\Http\Request;

class ClientController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client_fillables = $request->validate([
            "name" => "required|max:50",
            "calendar_url" => "required|url:http,https",
            "address" => "required|min:15"
        ]);

        $client_fillables["user_id"] = Auth::user()->id;

        $created_client = Client::create($client_fillables);
        return redirect()->intended(route('client', [
            'client' => $created_client
        ]))->with([
                    'message' => "{$client_fillables['name']} vient d'être créée"
                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('pages.dashboard.client', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return to_route('dashboard')->with([
            'message' => "{$client->name} vient d'être supprimé"
        ]);
    }
}
