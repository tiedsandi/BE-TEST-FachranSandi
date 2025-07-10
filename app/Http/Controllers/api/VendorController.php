<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|unique:vendors,vendor_name'
        ]);

        if ($validator->fails()) {
            return new ApiResponse(
                ['errors' => $validator->errors()],
                false,
                'Validation failed'
            );
        }

        $vendor = Vendor::create([
            'vendor_name' => $request->vendor_name,
            'created_by'  => $request->user()->id,
        ]);

        return new ApiResponse(
            ['vendor' => new VendorResource($vendor)],
            true,
            'Vendor registered successfully'
        );
    }
}
