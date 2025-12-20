<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pincode;
use App\Imports\PincodesImport;
use Maatwebsite\Excel\Facades\Excel;

class PincodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pincodes = Pincode::latest()->paginate(15);
        return view('admin.pincode.index', compact('pincodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pincode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:pincodes,code',
            'status' => 'required|in:active,inactive',
        ], [
            'code.unique' => 'This pincode already exists in the system.',
        ]);

        Pincode::create([
            'code' => $request->code,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pincodes.index')
            ->with('success', 'Pincode added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pincode = Pincode::findOrFail($id);
        return view('admin.pincode.edit', compact('pincode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pincode = Pincode::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:10|unique:pincodes,code,' . $id,
            'status' => 'required|in:active,inactive',
        ], [
            'code.unique' => 'This pincode already exists in the system.',
        ]);

        $pincode->update([
            'code' => $request->code,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pincodes.index')
            ->with('success', 'Pincode updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pincode = Pincode::findOrFail($id);
        $pincode->delete();

        return redirect()->route('admin.pincodes.index')
            ->with('success', 'Pincode deleted successfully.');
    }

    /**
     * Import pincodes from excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new PincodesImport, $request->file('file'));
            return redirect()->route('admin.pincodes.index')
                ->with('success', 'Pincodes imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pincodes.index')
                ->with('error', 'Error importing pincodes: ' . $e->getMessage());
        }
    }
}
