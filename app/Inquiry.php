<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='inquiry';
    	$this->fillable=[
    		'token',
    		'customer_id',
    		'project_name',
    		'poi',
    		'headend',
    		'status',
    		'payment_status',
    		'assigned_staff',
            'timestamp',
            'expiration_date'
    	];
    	$this->timestamps=false;
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function staff()
    {
        return $this->hasOne('App\User', 'id', 'assigned_staff');
    }

    public function remarks()
    {
        return $this->hasMany('App\Remarks', 'ticket_id', 'id');
    }

    public function incident()
    {
        return $this->hasMany('App\Incident', 'ticket_id', 'id');
    }
}
