<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends BaseController
{
    public function index()
    {
        $transactions = Transaction::with(['customer', 'products', 'paymentMethods'])->get();
        return $this->sendResponse($transactions, 'Transactions retrieved successfully');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|exists:customers,id',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'products' => 'required|array', // product is array of objects with product_id and quantity
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error', $validator->errors(), 400);
            }
            
            If (!isset($request->products[0]['product_id']) && !isset($request->products[0]['quantity'])) {
                return $this->sendError('Validation Error', 'Product Data type is not valid', 400);
            }

            foreach ($request->products as $key => $product) {
                If (!isset($request->products[$key]['product_id'])) {
                    return $this->sendError('Validation Error', 'Product->Product ID is required', 400);
                }
                if (!isset($request->products[$key]['quantity'])) {
                    return $this->sendError('Validation Error', 'Product->Quantity is required', 400);
                }
            }

            $transaction = new Transaction();
            $transaction->customer_id = $request->customer_id;
            $transaction->save();


            foreach ($request->products as $product) {
                $transaction->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
            }

            $transaction->paymentMethods()->attach($request->payment_method_id);

            return $this->sendResponse($transaction, 'Transaction created successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create transaction', [], 500);
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['customer', 'products', 'paymentMethods'])->find($id);

        if (!$transaction) {
            return $this->sendError('Transaction not found', [], 404);
        }

        return $this->sendResponse($transaction, 'Transaction retrieved successfully');
    }


    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->products()->detach();

            $transaction->paymentMethods()->detach();
            $transaction->delete();


            return $this->sendResponse([], 'Transaction deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete transaction', $e->getMessage(), 500);
        }
    }
}
