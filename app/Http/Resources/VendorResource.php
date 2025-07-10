<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vendor_name'      => $this->vendor_name,
            'created_by'       => $this->created_by,
            'created_by_name'  => optional($this->creator)->name,
            'created_at'       => $this->created_at->toDateTimeString(),
        ];
    }
}
