<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['guest', 'room'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('guest', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->room_type, function ($query) use ($request) {
                $query->whereHas('room', function ($q) use ($request) {
                    $q->where('type', $request->room_type);
                });
            })
            ->when($request->from_date, function ($query) use ($request) {
                $query->whereDate('check_in_date', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($query) use ($request) {
                $query->whereDate('check_in_date', '<=', $request->to_date);
            })
            ->latest()->paginate(10);
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:pending,confirmed,checked_in',
        ]);
        $room = Room::findOrFail($request->room_id);
        $nights = strtotime($request->check_out_date) - strtotime($request->check_in_date);
        $nights = ceil($nights / (60 * 60 * 24));
        $booking = Booking::create([
            'guest_id' => $request->guest_id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => $request->status,
            'total_amount' => $nights * $room->price_per_night,
        ]);
        return response()->json([
            'message' => 'Booking created successfully.',
            'booking' => $booking->load(['guest', 'room'])
        ], 201);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);
        $nights = strtotime($request->check_out_date) - strtotime($booking->check_in_date);
        $nights = ceil($nights / (60 * 60 * 24));
        $booking->update([
            'check_out_date' => $request->check_out_date,
            'status' => $request->status,
            'total_amount' => $nights * $booking->room->price_per_night,
        ]);
        return response()->json([
            'message' => 'Booking updated successfully.',
            'booking' => $booking->load(['guest', 'room'])
        ]);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json([
            'message' => 'Booking deleted successfully.'
        ]);
    }
}
