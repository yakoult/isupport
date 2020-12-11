<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='incident';
    	$this->fillable=[
    		'ticket_id',
    		'staff_id',
    		'report'
    	];
    	$this->timestamps=false;
    }

    public function attachment()
    {
    	return $this->hasMany('App\Attachment', 'incident_id', 'id');
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
