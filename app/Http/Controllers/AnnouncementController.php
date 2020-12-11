<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Announcement;

class AnnouncementController extends Controller
{
    public function announce(Request $request)
    {
    	$announcement = new Announcement;
    	$announcement->title = $request->title;
    	$announcement->message = $request->message;
    	$announcement->save();
    	return 'success';
    }

    public function getAnnouncement()
    {
    	return Announcement::latest('timestamp')->first();
    }
}
