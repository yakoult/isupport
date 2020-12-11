<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('default');

Route::get('/submission', 'PagesController@customerform')->name('submission');
Route::get('/products', 'PagesController@products')->name('products');
Route::get('/reg', 'PagesController@teacher')->name('reg');
Route::post('/reg_teach', 'Auth\RegisterController@teacher');
Route::get('/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/callback', 'SocialAuthFacebookController@callback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix'=>'admin', 'middleware' => ['admin']], function() {
	Route::get('/requests', 'PagesController@adminrequest');
	Route::get('/staff', 'PagesController@adminstaff');
	Route::get('/announce', 'PagesController@adminannounce');
	Route::post('/announcement', 'AnnouncementController@announce');
});
Route::post('/custrequest', 'CustomerController@request')->name('custrequest');
Route::get('/inquiries', 'CustomerController@getInquiries');

Route::group(['prefix'=>'staff'], function(){
	Route::get('/', 'UserController@getStaff');
	Route::get('/ticket-list', 'PagesController@staffRequest')->middleware('staff');
	Route::post('/new', 'UserController@newStaff');
	Route::post('/update', 'UserController@updateStaff');
	Route::post('/delete', 'UserController@deleteStaff');
	Route::post('/changePass', 'UserController@changePass');
	Route::post('/pending', 'InquiryController@getPending');
	Route::post('/recent', 'InquiryController@recentRemark');
	Route::get('/announcements', 'PagesController@staffAnnouncements');
});

Route::group(['prefix'=>'inquiry'], function(){
	Route::post('/remarks', 'InquiryController@updateRemarks');
	Route::post('/paid', 'InquiryController@confirmPayment');
	Route::post('/status', 'InquiryController@changeStatus');
	Route::post('/assign', 'InquiryController@assignStaff');
	Route::post('/forStaff', 'InquiryController@staffRequests');
	Route::post('/incident', 'InquiryController@reportIncident');
	Route::post('/delete', 'InquiryController@deleteRequest');
	Route::post('/resend', 'InquiryController@resendEmail');
});

Route::group(['prefix'=>'notification'], function(){
	Route::post('/new', 'NotificationController@newAnnouncement');
	Route::post('/foruser', 'NotificationController@getNotifFor');
});

Route::get('imgtest', 'InquiryController@getImgs');
Route::get('imgurl', 'InquiryController@getUrl');