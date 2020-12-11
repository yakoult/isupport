<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='notification';
    	$this->fillable=[
    		'from_user',
    		'to_user',
    		'message',
    		'passcode',
    		'seen_flag'
    	];
    	$this->timestamps=false;
    }

    public function from()
    {
    	return $this->belongsTo('App\User', 'from_user', 'id');
    }

    public function to()
    {
    	return $this->belongsTo('App\User', 'to_user', 'id');
    }
}
