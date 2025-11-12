<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\SkCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class AddCustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index()
    {
        $customers = User::with('customerAddress')->latest()->paginate(10);
        return view('admin.addcustomer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.addcustomer.create');
    }

    /**
     * Store a newly created customer in storage.
     */
      public function store(Request $request)
    {
        // Validate the request with strict 10-digit phone validation
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // Name is required
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'], // Email is optional but must be unique
            'phone' => ['required', 'string', 'max:10', 'regex:/^[0-9]{10}$/'], // Phone must be exactly 10 digits
            'city' => ['required', 'string', 'max:255'], // City is required
        ]);

        // Set timezone to Asia/Kolkata
        date_default_timezone_set('Asia/Kolkata');

        // Create the user with phone number as password and IST timestamp
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->phone), // Use phone number as password
            'created_at' => Carbon::now('Asia/Kolkata'),
            'updated_at' => Carbon::now('Asia/Kolkata'),
        ]);

        // Create customer address with only provided fields
CustomerAddress::create([
    'user_id'    => $user->id,
    'phone'      => $request->phone,
    'city'       => $request->city,
    'district'   => 'Murshidabad', // ✅ static value
        'state'   => 'West Bengal', // ✅ static value
        'pin'   => '742133', // ✅ static value

    'created_at' => Carbon::now('Asia/Kolkata'),
    'updated_at' => Carbon::now('Asia/Kolkata'),
]);


        // Redirect to customers index with success message
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully, yaar!');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        $customer->load('customerAddress');
        return view('admin.addcustomer.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'district' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pin' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $customer->password,
        ]);

        if ($request->filled(['phone', 'district', 'landmark', 'city', 'state', 'pin', 'country'])) {
            $customer->customerAddress()->updateOrCreate(
                ['user_id' => $customer->id],
                [
                    'phone' => $request->phone,
                    'district' => $request->district,
                    'landmark' => $request->landmark,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pin' => $request->pin,
                    'country' => $request->country,
                ]
            );
        } else {
            $customer->customerAddress()->delete();
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        $customer->customerAddress()->delete();
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Show the customer account details.
     */
    public function showAccount(User $customer)
    {
        $customers = User::with('customerAddress')->latest()->paginate(10);

        $customer->load('customerAddress', 'skCredits');
        $totalDue = $customer->skCredits->where('type', 'due')->sum('amount');
        $totalReceived = $customer->skCredits->where('type', 'received')->sum('amount');
        $balanceAdvance = $totalReceived - $totalDue;

return view('admin.addcustomer.customeraccount', compact('customers', 'customer', 'totalDue', 'totalReceived', 'balanceAdvance'));
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        $customers = User::with('skCredits')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->latest()
            ->paginate(10);
        
        return response()->json(['customers' => $customers]);
    }

    /**
     * Store a new credit transaction for the specified customer.
     */
    public function storeCredit(Request $request, User $customer)
    {
        // Validate the request
        $request->validate([
            'transaction_date' => ['nullable', 'date'],
['before_or_equal:'.Carbon::now('Asia/Kolkata')->format('Y-m-d H:i')],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:received,due'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        // Set timezone to Asia/Kolkata
        date_default_timezone_set('Asia/Kolkata');

        try {
            // Use provided transaction date or default to now
            $transactionDate = $request->filled('transaction_date') && $request->transaction_date
                ? Carbon::parse($request->transaction_date, 'Asia/Kolkata')
                : Carbon::now('Asia/Kolkata');

            // Current date and time for created_at and updated_at
            $currentDateTime = Carbon::now('Asia/Kolkata');

            \Log::info('Transaction Date Parsed: ' . $transactionDate);
            \Log::info('Current DateTime: ' . $currentDateTime);

            // Create the credit transaction
            $credit = $customer->skCredits()->create([
                'user_id' => $customer->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'date' => $transactionDate,
                'created_at' => $currentDateTime,
                'updated_at' => $currentDateTime,
            ]);

            \Log::info('Stored Credit: ', $credit->toArray());

            return redirect()->route('admin.customers.account', $customer)
                ->with('success', 'Transaction recorded successfully.');
        } catch (\Exception $e) {
            \Log::error('Error storing credit: ' . $e->getMessage());
            return redirect()->back()->withErrors(['transaction_date' => 'Invalid transaction date provided.']);
        }
    }

    /**
     * Update an existing credit transaction for the specified customer.
     */
    public function updateCredit(Request $request, User $customer, SkCredit $credit)
    {
        // Validate the request
        $request->validate([
            'transaction_date' => ['nullable', 'date', 'before_or_equal:'.Carbon::now('Asia/Kolkata')->format('Y-m-d H:i')],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:received,due'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        // Set timezone to Asia/Kolkata
        date_default_timezone_set('Asia/Kolkata');

        try {
            // Use provided transaction date or default to now
            $transactionDate = $request->filled('transaction_date') && $request->transaction_date
                ? Carbon::parse($request->transaction_date, 'Asia/Kolkata')
                : Carbon::now('Asia/Kolkata');

            // Current date and time for updated_at
            $currentDateTime = Carbon::now('Asia/Kolkata');

            \Log::info('Transaction Date Parsed: ' . $transactionDate);
            \Log::info('Current DateTime: ' . $currentDateTime);

            // Update the credit transaction
            $credit->update([
                'type' => $request->type,
                'amount' => $request->amount,
                'note' => $request->note,
                'date' => $transactionDate,
                'updated_at' => $currentDateTime,
            ]);

            \Log::info('Updated Credit: ', $credit->toArray());

            return redirect()->route('admin.customers.account', $customer)
                ->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating credit: ' . $e->getMessage());
            return redirect()->back()->withErrors(['transaction_date' => 'Invalid transaction date provided.']);
        }
    }

    /**
     * Delete a credit transaction for the specified customer.
     */
    public function deleteCredit(Request $request, User $customer, SkCredit $credit)
    {
        try {
            $credit->delete();
            return redirect()->route('admin.customers.account', $customer)
                ->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting credit: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to delete transaction.']);
        }
    }
}