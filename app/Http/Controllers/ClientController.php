<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Auth;
use Illuminate\Http\Request;

class ClientController
{
    private function ensureUserHasClient(Client $client)
    {
        abort_if($client->user_id !== Auth::user()->id, 404);
    }

    public function create(Request $request)
    {
        return to_route('app.index')->withInput([
            '_form' => "client_creation"
        ]);
    }

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
        return redirect()->intended(route('clients.show', [
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
        $this->ensureUserHasClient($client);
        return view('pages.dashboard.client.index', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $this->ensureUserHasClient($client);
        return view('pages.dashboard.client.manage', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->ensureUserHasClient($client);

        $client->delete();
        return to_route('app.index')->with([
            'message' => "{$client->name} vient d'être supprimé"
        ]);
    }
}
