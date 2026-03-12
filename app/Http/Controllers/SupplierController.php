<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $activeSuppliers = Supplier::whereNull('deleted_at')->orderBy('supplier_id')->get();
        $deletedSuppliers = Supplier::onlyTrashed()->orderBy('supplier_id')->get();
        return view('admin.suppliers.index', compact('activeSuppliers', 'deletedSuppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'lead_time' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        Supplier::create($request->only('name', 'contact_email', 'contact_phone', 'lead_time', 'website'));

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added successfully!');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'lead_time' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->only('name', 'contact_email', 'contact_phone', 'lead_time', 'website'));

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted.');
    }

    public function restore($id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->restore();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier restored.');
    }
}
