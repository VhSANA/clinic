<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Rules\CapacityValidation;
use App\Rules\RoomTitleValidation;
use App\Rules\TitleValidation;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::query();

        if (request('search')) {
            $keyword = request('search');

            if (strpos($keyword, 'نفر') != false) {
                $keyword = trim(substr($keyword, 0, strpos($keyword, 'نفر')));

                $rooms
                ->where('title', 'like', "%$keyword%")
                ->orWhere('personnel_capacity', 'like', "%$keyword%")
                ->orWhere('patient_capacity', 'like', "%$keyword%");
            } else {
                $rooms->where('title', 'like', "%$keyword%");
            }
        }

        $rooms = $rooms->latest()->paginate(10);

        return view('admin.rooms.all-rooms', [
            'rooms' => $rooms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rooms.create-room');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Room $room)
    {
        $request->validate([
            'title' => [new RoomTitleValidation($room)],
            'personnel_capacity' => [new CapacityValidation],
            'patient_capacity' => [new CapacityValidation],
        ]);

        // add to database
        Room::create([
            'title' => $request['title'],
            'personnel_capacity' => $request['personnel_capacity'],
            'patient_capacity' => $request['patient_capacity'],
        ]);

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('room.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('admin.rooms.room', [
            'room' => $room
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit-room', [
            'room' => $room
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'title' => [new RoomTitleValidation($room)],
            'personnel_capacity' => [new CapacityValidation],
            'patient_capacity' => [new CapacityValidation],
        ]);

        // add to database
        $room->update([
            'title' => $request['title'],
            'personnel_capacity' => $request['personnel_capacity'],
            'patient_capacity' => $request['patient_capacity'],
        ]);

        // TODO delete relations

        // json success message
        response()->json(['message' => 'done'], 200);

        return redirect(route('room.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->deleteOrFail();
        return redirect(route('room.index'));
    }
}
