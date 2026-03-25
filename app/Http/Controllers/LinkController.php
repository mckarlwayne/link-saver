<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    //Write code function for displaying all links & Title from the database
    public function index(Request $request)
    {
        $query = Auth::user()->links();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
        }

        $links = $query->latest()->get();

        return view('links.index', compact('links'));
    }

        //Write code function for storing links and title in the database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required|url'
        ]);

        $link = Auth::user()->links()->create($request->only(['title', 'url']));

        return redirect('/links');
    }

    //Write code function for editing a link
    public function edit($id)
    {
        $link = Auth::user()->links()->findOrFail($id);
        $links = Auth::user()->links;
        return view('links.index', ['links' => $links, 'editingLink' => $link]);
    }

    //Write code function for updating a link in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'url' => 'required|url'
        ]);

        $link = Auth::user()->links()->findOrFail($id);
        $link->update($request->only(['title', 'url']));

        return redirect('/links');
    }

    //Write a code function for destroying a link from the database
    public function destroy($id)
    {
        $link = Auth::user()->links()->findOrFail($id);
        $link->delete();

        return redirect('/links');
    }

    // Visit link and increment visits count
    public function visit($id)
    {
        $link = Auth::user()->links()->findOrFail($id);
        $link->increment('visits');
        return redirect($link->url);
    }
}
