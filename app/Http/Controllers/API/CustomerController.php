<?php

namespace App\Http\Controllers\API;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends BaseController
{
    public function index()
    {
        $customers = Customer::all();
        return $this->sendResponse($customers, 'Customers retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'costumer_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customer = Customer::create([
            'costumer_name' => $request->costumer_name,
        ]);

        return $this->sendResponse($customer, 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return $this->sendResponse($customer, 'Customer retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'costumer_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customer = Customer::findOrFail($id);
        $customer->update([
            'costumer_name' => $request->costumer_name,
        ]);

        return $this->sendResponse($customer, 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return $this->sendResponse($customer, 'Customer deleted successfully.');
    }
}
