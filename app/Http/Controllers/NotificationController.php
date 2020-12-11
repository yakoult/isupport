<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use App\Notifications;
use App\Events\NewNotif;

class NotificationController extends Controller
{
    public function newAnnouncement(Request $request)
    {
    	if ($request->to == 'all') {
    		$data = [];
    		$users = User::where('type', 2)->get();
    		foreach ($users as $user) {
    			array_push($data, [
    				'from_user'=>$request->userid,
    				'to_user'=>$user->id,
    				'message'=>$request->notif['message'],
    				'passcode'=>$request->notif['passcode']
    			]);
    		}
    		Notifications::insert($data);
    	}else{
    		$notif = new Notifications;
    		$notif->from_user = $request->userid;
    		$notif->to_user = $request->to;
    		$notif->message = $request->notif['message'];
    		$notif->passcode = $request->notif['passcode'];
    		$notif->save();
    		event(new NewNotif($notif));
    	}
    	return 'success';
    }

    public function getNotifFor(Request $request)
    {
    	return Notifications::where('to_user', $request->id)->with('from')->get();
    }
}
