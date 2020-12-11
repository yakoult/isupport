<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->primaryKey='id';
		$this->table='announcement';
		$this->fillable=[
			'title',
			'message',
			'timestamp'
		];
		$this->timestamps=false;
	}
}
