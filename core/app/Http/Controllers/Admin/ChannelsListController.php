<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;

class ChannelsListController extends Controller
{
    public function index(Request $request)
    {
        $query = Channel::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('country', 'like', "%$search%")
                ->orWhere('region', 'like', "%$search%");
        }

        $perPage = $request->input('perPage', 10);

        $channels = $query->latest()->paginate($perPage)->appends($request->all());
        $countries = json_decode(file_get_contents(storage_path('app/private/countries.json')), true);

        return view('admin.channels-list', compact('channels', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|in:Europe,Americas,Arabic,Africa,Australia,Asia',
            'country' => 'required|string|size:2|alpha',
            'name' => 'required|string|max:255',
        ]);

        Channel::create($request->only('region', 'country', 'name'));

        return back()->with('success', 'Channel added successfully.');
    }

    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'region' => 'required|string|in:Europe,Americas,Arabic,Africa,Australia,Asia',
            'country' => 'required|string|size:2|alpha',
            'name' => 'required|string|max:255',
        ]);

        $channel->update($request->only('region', 'country', 'name'));

        return back()->with('success', 'Channel updated successfully.');
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();

        return back()->with('success', 'Channel deleted successfully.');
    }
}
