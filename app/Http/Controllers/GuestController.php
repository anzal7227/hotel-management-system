<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $guests = Guest::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('nationality', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(10);
        return response()->json($guests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:guests',
            'phone' => 'nullable',
            'nationality' => 'nullable',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('guests', 'public');
        }
        $guest = Guest::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nationality' => $request->nationality,
            'profile_image' => $imagePath,
        ]);
        return response()->json([
            'message' => 'Guest created successfully.',
            'guest' => $guest
        ], 201);
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:guests,email,' . $guest->id,
        ]);
        $guest->update($request->only([
            'name','email','phone','nationality',
        ]));
        return response()->json([
            'message' => 'Guest updated successfully.',
            'guest' => $guest
        ]);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json([
            'message' => 'Guest deleted successfully.'
        ]);
    }
}
