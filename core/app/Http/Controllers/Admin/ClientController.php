<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = User::select('id', 'full_name', 'email', 'country')->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }


    public function create()
    {
        $countries = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);

        return view('admin.clients.create', compact('countries'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'country' => 'required|string|max:2',
            'ip' => 'nullable|ip',
        ]);

        $countries = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);

        $countryCodes = array_column($countries, 'code');

        if (!in_array(strtoupper($validated['country']), $countryCodes)) {
            return redirect()->back()->withErrors(['country' => 'The selected country is invalid.']);
        }


        User::create($validated);

        return redirect()->back()->with('success', 'Client created successfully.');
    }

    public function show(Request $request, $id)
    {
        $client = User::findOrFail($id);
        $subscriptions = $client->subscriptions()->with(['plan', 'paymentMethod'])
            ->paginate($request->perPage ?? 10);

        return view('admin.clients.show', compact('client', 'subscriptions'));
    }



    public function edit(User $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, User $client)
    {
        $request->validate([
            'email' => 'required|email|unique:clients,email,' . $client->id,
        ]);

        $client->update($request->all());

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(User $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }
}