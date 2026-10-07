<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::info('Fetching all products');
            $products = Product::all();
            return response()->json([
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Storing new product', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            $product = Product::create($validated);

            Log::info('Product created successfully with ID: ' . $product->id);

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            Log::info('Fetching product with ID: ' . $id);
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Not found'
                ], 404);
            }

            return response()->json([
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating product with ID: ' . $id, $request->all());

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            $product->update($validated);

            Log::info('Product updated successfully with ID: ' . $product->id);

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('Deleting product with ID: ' . $id);

            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'message' => 'Not found'
                ], 404);
            }

            $product->delete();

            Log::info('Product deleted successfully with ID: ' . $id);

            return response()->json([
                'message' => 'Product deleted successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
