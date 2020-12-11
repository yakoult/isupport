<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function customerform(Request $request)
    {
        $service = $request->service;
        return view('submission', ['service'=>$service]);
    }

    public function products(Request $request)
    {
    	return view('products.product');
    }

    public function adminrequest(Request $request)
    {
        return view('admin.requests');
    }

    public function adminstaff(Request $request)
    {
        return view('admin.staff');
    }

    public function adminannounce(Request $request)
    {
        return view('admin.announce');
    }

    public function staffRequest(Request $request)
    {
        return view('staff.ticketlist');
    }

    public function staffAnnouncements(Request $request)
    {
        return view('staff.announcements');
    }
}
