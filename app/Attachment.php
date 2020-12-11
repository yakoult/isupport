<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public function __construct()
    {
    	parent::__construct();
    	$this->primaryKey='id';
    	$this->table='attachments';
    	$this->fillable=[
    		'incident_id',
    		'file_name',
    		'file_type'
    	];
    	$this->timestamps=false;
    }

    public function incident()
    {
    	return $this->belongsTo('App\Incident', 'incident_id', 'id');
    }
}
