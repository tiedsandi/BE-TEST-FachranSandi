<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['vendor_id', 'name', 'description', 'price'];

    protected $appends = ['vendor_name'];

    public function getVendorNameAttribute()
    {
        return $this->vendor ? $this->vendor->vendor_name : null;
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
