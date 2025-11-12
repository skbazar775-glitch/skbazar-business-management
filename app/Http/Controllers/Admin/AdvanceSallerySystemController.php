<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvanceSalary;
use App\Models\AdvanceSalaryRepayment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdvanceSallerySystemController extends Controller
{
    public function index()
    {
        $advanceSalaries = AdvanceSalary::with(['staff', 'repayments'])
            ->orderBy('advance_date', 'desc')
            ->paginate(10);
        return view('admin.advancesalary.index', compact('advanceSalaries'));
    }

    public function create()
    {
        $staffs = Staff::orderBy('name')->get();
        return view('admin.advancesalary.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
            'amount' => 'required|numeric|min:0',
            'total_emi' => 'required|integer|min:1',
            'advance_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $monthly_emi = $request->amount / $request->total_emi;
            
            $advanceSalary = AdvanceSalary::create([
                'staff_id' => $request->staff_id,
                'amount' => $request->amount,
                'total_emi' => $request->total_emi,
                'monthly_emi' => round($monthly_emi, 2),
                'advance_date' => $request->advance_date,
                'note' => $request->note,
            ]);

            DB::commit();
            return redirect()->route('admin.advancesalary.index')
                ->with('success', 'Advance salary created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating advance salary');
        }
    }

    public function show($id)
    {
        $advanceSalary = AdvanceSalary::with(['staff', 'repayments'])
            ->findOrFail($id);
        return view('admin.advancesalary.show', compact('advanceSalary'));
    }

    public function edit($id)
    {
        $advanceSalary = AdvanceSalary::findOrFail($id);
        $staffs = Staff::orderBy('name')->get();
        return view('admin.advancesalary.edit', compact('advanceSalary', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required|exists:staff,id',
            'amount' => 'required|numeric|min:0',
            'total_emi' => 'required|integer|min:1',
            'advance_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $advanceSalary = AdvanceSalary::findOrFail($id);
            $monthly_emi = $request->amount / $request->total_emi;

            $advanceSalary->update([
                'staff_id' => $request->staff_id,
                'amount' => $request->amount,
                'total_emi' => $request->total_emi,
                'monthly_emi' => round($monthly_emi, 2),
                'advance_date' => $request->advance_date,
                'note' => $request->note,
            ]);

            DB::commit();
            return redirect()->route('admin.advancesalary.index')
                ->with('success', 'Advance salary updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating advance salary');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $advanceSalary = AdvanceSalary::findOrFail($id);
            $advanceSalary->repayments()->delete();
            $advanceSalary->delete();

            DB::commit();
            return redirect()->route('admin.advancesalary.index')
                ->with('success', 'Advance salary deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting advance salary');
        }
    }

    public function addRepayment(Request $request, $advance_salary_id)
    {
        $validator = Validator::make($request->all(), [
            'emi_number' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            'paid_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $advanceSalary = AdvanceSalary::findOrFail($advance_salary_id);
            
            if ($request->emi_number > $advanceSalary->total_emi) {
                return redirect()->back()->with('error', 'EMI number exceeds total EMIs');
            }

            AdvanceSalaryRepayment::create([
                'advance_salary_id' => $advance_salary_id,
                'emi_number' => $request->emi_number,
                'paid_amount' => $request->paid_amount,
                'paid_date' => $request->paid_date,
                'note' => $request->note,
            ]);

            DB::commit();
            return redirect()->route('admin.advancesalary.show', $advance_salary_id)
                ->with('success', 'Repayment added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding repayment');
        }
    }
}