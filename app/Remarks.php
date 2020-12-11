<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='remarks';
    	$this->fillable=[
    		'ticket_id',
    		'staff_id',
            'remark'
    	];
    	$this->timestamps=false;
    }

    public function inquiry()
    {
        return $this->belongsTo('App\Inquiry', 'ticket_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo('App\User', 'staff_id', 'id');
    }
}
