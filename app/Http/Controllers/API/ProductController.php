<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::all();
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();

            return $this->sendResponse($product, 'Product created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to create product', $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Product not found');
        }

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $product = Product::find($id);

            if (!$product) {
                return $this->sendError('Product not found');
            }

            $product->name = $request->name;
            $product->price = $request->price;
            $product->save();

            return $this->sendResponse($product, 'Product updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update product', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->sendError('Product not found');
            }

            $product->delete();

            return $this->sendResponse($product, 'Product deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete product', $e->getMessage());
        }
    }
}
