<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $products = Product::whereIn('vendor_id', $user->vendors->pluck('id'))->latest()->get();

        return new ApiResponse(
            ['products' => ProductResource::collection($products)],
            true,
            'Product list retrieved successfully'
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $vendors = $user->vendors;

        if ($vendors->isEmpty()) {
            return new ApiResponse(null, false, 'User has no registered vendor');
        }

        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
        ]);

        if ($validator->fails()) {
            return new ApiResponse(
                ['errors' => $validator->errors()],
                false,
                'Validation failed'
            );
        }

        $vendor = $vendors->where('id', $request->vendor_id)->first();
        if (!$vendor) {
            return new ApiResponse(null, false, 'Vendor not found or does not belong to user');
        }

        $product = $vendor->products()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        return new ApiResponse(
            ['product' => new ProductResource($product)],
            true,
            'Product registered successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();

        $product = Product::where('id', $id)
            ->whereIn('vendor_id', $user->vendors->pluck('id'))
            ->first();

        if (!$product) {
            return new ApiResponse(null, false, 'Product not found or unauthorized');
        }

        return new ApiResponse(
            ['product' => new ProductResource($product)],
            true,
            'Product retrieved successfully'
        );
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $request->user();

        $product = Product::where('id', $id)
            ->whereIn('vendor_id', $user->vendors->pluck('id'))
            ->first();

        if (!$product) {
            return new ApiResponse(null, false, 'Product not found or unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
        ]);

        if ($validator->fails()) {
            return new ApiResponse(
                ['errors' => $validator->errors()],
                false,
                'Validation failed'
            );
        }

        $product->update($validator->validated());

        return new ApiResponse(
            ['product' => new ProductResource($product)],
            true,
            'Product updated successfully'
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $request->user();

        $product = Product::where('id', $id)
            ->whereIn('vendor_id', $user->vendors->pluck('id'))
            ->first();

        if (!$product) {
            return new ApiResponse(null, false, 'Product not found or unauthorized');
        }

        $product->delete();

        return new ApiResponse(null, true, 'Product deleted successfully');
    }
}
