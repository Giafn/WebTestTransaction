<?php

namespace App\Http\Controllers\API;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends BaseController
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return $this->sendResponse($paymentMethods, 'Payment Methods retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paymentMethod = PaymentMethod::create([
            'name' => $request->name,
            'is_active' => $request->is_active ?? 1,
        ]);

        return $this->sendResponse($paymentMethod, 'Payment Method created successfully.');
    }

    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return response()->json(['data' => $paymentMethod]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return $this->sendResponse($paymentMethod, 'Payment Method updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);
            $paymentMethod->delete();
        } catch (\Exception $e) {
            return $this->sendError('Database Error.', $e->getMessage());
        }

        return $this->sendResponse($paymentMethod, 'Payment Method deleted successfully.');
    }
}
