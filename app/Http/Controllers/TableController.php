<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    public function index()
{
    $tables = Table::all();
    return view('admin.menu.tables', compact('tables'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
    ]);

    Table::create($request->only('name', 'capacity'));

    return redirect()->route('tables.index')->with('success', 'Table created successfully!');
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
    ]);

    $table = Table::findOrFail($id);
    $table->update($request->only('name', 'capacity'));

    return redirect()->route('tables.index')->with('success', 'Table updated successfully!');
}

public function destroy($id)
{
    $table = Table::findOrFail($id);
    $table->delete();

    return redirect()->route('tables.index')->with('success', 'Table deleted successfully!');
}

}
