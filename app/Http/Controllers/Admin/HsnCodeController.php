<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HsnCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HsnCodeController extends Controller
{
    public function index()
    {
        $hsnCodes = HsnCode::all();
        return view('admin.hsn.index', compact('hsnCodes'));
    }

    public function create()
    {
        return view('admin.hsn.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:hsn_codes,code|string|max:20',
            'description' => 'required|string',
            'gst_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        HsnCode::create($request->all());
        return redirect()->route('admin.invoice.hsn.index')->with('success', 'HSN Code created successfully.');
    }

    public function edit($id)
    {
        $hsnCode = HsnCode::findOrFail($id);
        return view('admin.hsn.edit', compact('hsnCode'));
    }

    public function update(Request $request, $id)
    {
        $hsnCode = HsnCode::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:hsn_codes,code,' . $id,
            'description' => 'required|string',
            'gst_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hsnCode->update($request->all());
        return redirect()->route('admin.invoice.hsn.index')->with('success', 'HSN Code updated successfully.');
    }
}
?>