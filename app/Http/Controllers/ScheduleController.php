<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Field;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('field')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $fields = Field::all();
        return view('admin.schedules.create', compact('fields'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'day' => 'required|string|max:10', // Validasi untuk hari
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_available' => 'required|boolean',
            'is_available' => 'required|boolean',
        ]);

        // Membuat jadwal baru
        Schedule::create([
            'field_id' => $request->field_id,
            'day' => $request->day, // Simpan hari
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => $request->is_available ? 1 : 0,
            'is_recurring' => $request->has('is_recurring') ? 1 : 0,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }


    public function show(Schedule $schedule)
    {
        $schedule->load('field'); // Load relasi field
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $fields = Field::all();
        return view('admin.schedules.edit', compact('schedule', 'fields'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'field_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'is_available' => 'required|boolean',
        ]);

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}