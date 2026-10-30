<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::query()
            ->when($request->search, function ($query) use ($request) {
                $query->where('room_number', 'like', '%' . $request->search . '%');
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()->paginate(10);
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms',
            'type' => 'required|in:standard,deluxe,suite',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked',
        ]);
        $room = Room::create($request->only([
            'room_number','type','price_per_night','status',
        ]));
        return response()->json([
            'message' => 'Room created successfully.',
            'room' => $room
        ], 201);
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'type' => 'required|in:standard,deluxe,suite',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked',
        ]);
        $room->update($request->only([
            'room_number','type','price_per_night','status',
        ]));
        return response()->json([
            'message' => 'Room updated successfully.',
            'room' => $room
        ]);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json([
            'message' => 'Room deleted successfully.'
        ]);
    }
}
