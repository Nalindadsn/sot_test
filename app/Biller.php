<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $fillable =[
        "name", "image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country", "is_active", "commission_pre","sales_target"
    ];

    public function sale()
    {
    	return $this->hasMany('App\Sale');
    }
}
