<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class ApiAddAddress extends Controller
{

    public function index(Request $request)
{
    $user = Auth::user();

    Log::info('📦 Fetching user addresses', [
        'user_id' => $user->id,
        'ip' => $request->ip(),
    ]);
    

    $addresses = Address::where('user_id', $user->id)->latest()->get();

    return response()->json([
        'status' => true,
        'message' => 'Addresses fetched successfully.',
        'data' => $addresses,
    ]);
}

    public function store(Request $request)
    {
        // Step 1: Initial Log - API hit
        Log::info('🚀 Address API Hit', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'input_data' => $request->all()
        ]);

        // Step 2: Validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:15',
            'pin_code'  => 'required|string|max:10',
            'district'  => 'required|string|max:100',
            'city'      => 'required|string|max:100',
            'area'      => 'required|string|max:255',
            'landmark'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            // Step 3: Log validation error
            Log::warning('⚠️ Validation Failed in Address API', [
                'errors' => $validator->errors()
            ]);

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Step 4: Save Address (add user_id manually)
        $address = Address::create([
            'user_id'   => Auth::id(),
            'name'      => $request->name,
            'phone'     => $request->phone,
            'pin_code'  => $request->pin_code,
            'district'  => $request->district,
            'city'      => $request->city,
            'area'      => $request->area,
            'landmark'  => $request->landmark,
        ]);

        // Step 5: Log successful response
        Log::info('✅ Address Saved Successfully', [
            'address_id' => $address->id,
            'data' => $address->toArray()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Address added successfully.',
            'data' => $address,
        ], 201);
    }



    public function update(Request $request, $id)
{
    Log::info('✏️ Address Update API Hit', [
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'input_data' => $request->all(),
        'address_id' => $id
    ]);

    $validator = Validator::make($request->all(), [
        'name'      => 'required|string|max:255',
        'phone'     => 'required|string|max:15',
        'pin_code'  => 'required|string|max:10',
        'district'  => 'required|string|max:100',
        'city'      => 'required|string|max:100',
        'area'      => 'required|string|max:255',
        'landmark'  => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        Log::warning('⚠️ Validation Failed in Address Update', [
            'errors' => $validator->errors()
        ]);

        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    $address = Address::where('user_id', Auth::id())->findOrFail($id);

    $address->update([
        'name'      => $request->name,
        'phone'     => $request->phone,
        'pin_code'  => $request->pin_code,
        'district'  => $request->district,
        'city'      => $request->city,
        'area'      => $request->area,
        'landmark'  => $request->landmark,
    ]);

    Log::info('✅ Address Updated Successfully', [
        'address_id' => $address->id,
        'data' => $address->toArray()
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Address updated successfully.',
        'data' => $address,
    ]);
}
public function destroy($id)
{
    $address = Address::where('user_id', Auth::id())->findOrFail($id);

    Log::info('🗑️ Deleting Address', [
        'user_id' => Auth::id(),
        'address_id' => $address->id,
    ]);

    $address->delete();

    return response()->json([
        'status' => true,
        'message' => 'Address deleted successfully.'
    ]);
}

}
