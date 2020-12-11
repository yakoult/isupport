<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='customer';
    	$this->fillable=[
    		'name',
    		'company',
    		'mobile',
    		'email',
    		'comobile',
    		'coemail',
            'address',
            'payment_method'
    	];
    	$this->timestamps=false;
    }

    public function inquiry()
    {
        return $this->hasOne('App\Inquiry', 'customer_id', 'id');
    }
}
