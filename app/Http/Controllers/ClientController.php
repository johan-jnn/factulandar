<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Auth;
use Gate;
use Illuminate\Http\Request;

class ClientController
{
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
            "address" => "required|min:15",
            "prefered_hours_price" => "required|decimal:0,2"
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
        $calendar = $client->nullable_calendar();
        return view('pages.dashboard.client.index', [
            'client' => $client,
            'calendar' => $calendar
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('pages.dashboard.client.edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $client_new_info = $request->validate([
            "name" => "required|max:50",
            "calendar_url" => "required|url:http,https",
            "address" => "required|min:15",
            "prefered_hours_price" => "required|decimal:0,2"
        ]);

        $client->update($client_new_info);

        return to_route('clients.edit', [
            'client' => $client
        ])
            ->with([
                'message' => "{$client['name']} a bien été modifié"
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return to_route('app.index')->with([
            'message' => "{$client->name} vient d'être supprimé"
        ]);
    }
}
