<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\SocialFacebookAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateStaff(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        try{
            $user->save();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function deleteStaff(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->delete();
        return 'success';
    }

    public function get()
    {
        return User::get();
    }

    public function changePass(Request $request)
    {
        $id = $request->id;
        $password = $request->pass;
        $user = User::where('id', $id)->first();
        $user->password = Hash::make($password);
        try{
            $user->save();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function getStaff()
    {
        return User::where('type', '2')->get();
    }

    public function newStaff(Request $request)
    {
        $this->formCheck($request->all())->validate();
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        try{
            $user->save();
            event(new Registered($user));
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function formCheck(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
        ]);
    }
}
