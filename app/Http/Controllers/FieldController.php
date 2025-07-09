<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::all();
        return view('admin.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'price_per_hour' => 'required|numeric',
        ]);

        $field = new Field();
        $field->name = $request->name;
        $field->location = $request->location;
        $field->description = $request->description;

        // Menangani upload foto
        if ($request->hasFile('photo')) {
            $field->photo = $request->file('photo')->store('fields', 'public');
        }

        $field->price_per_hour = $request->price_per_hour;
        $field->save();

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $field = Field::findOrFail($id); // Ambil data lapangan berdasarkan ID
        return view('admin.fields.show', compact('field')); // Pastikan view dan data dikirim ke halaman
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $field = Field::findOrFail($id);
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'price_per_hour' => 'required|numeric',
        ]);

        $field = Field::findOrFail($id);
        $field->name = $request->name;
        $field->location = $request->location;
        $field->description = $request->description;

        // Menangani upload foto
        if ($request->hasFile('photo')) {
            $field->photo = $request->file('photo')->store('fields', 'public');
        }

        $field->price_per_hour = $request->price_per_hour;
        $field->save();

        return redirect()->route('admin.fields.index')->with('success', 'Data lapangan berhasil diupdate.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $field = Field::findOrFail($id);
        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }

    public function indexForUser()
    {
        $fields = Field::all(); // Mengambil semua data lapangan
        if ($fields->isEmpty()) {
            // Jika tidak ada data, bisa mengembalikan pesan
            return view('index')->with('message', 'Tidak ada lapangan yang tersedia.');
        }
        return view('index', compact('fields')); // Mengembalikan view untuk landing page dengan data lapangan
    }
}
