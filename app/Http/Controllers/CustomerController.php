<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inquiry;
use App\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function request(Request $request)
    {
        $this->validator($request->all())->validate();
        $date = new \DateTime();
        $date->add(new \DateInterval('P30D'));
        $today = new \DateTime();
        $customer = $this->createCustomer($request->all());
        Log::info($customer);
        $inquiry = new Inquiry;
        $inquiry->token = $this->generateRandomString();
        $inquiry->project_name = $request->service;
        $inquiry->customer_id = $customer->id;
        $inquiry->poi = $request->POI;
        $inquiry->headend = $request->headend;
        $inquiry->timestamp = $today->format('Y-m-d');
        $inquiry->expiration_date = $date->format('Y-m-d');
        $inquiry->payment_status = 'No Payment';
        $this->mailToClient($inquiry->token, $request->email);
        try{
            $inquiry->save();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return redirect('/?sent='.$inquiry->token);
    }

    public function getInquiries()
    {
        return Inquiry::with('customer')->with('staff')->with('remarks.staff')->with(['incident'=>function($incident){
            $incident->with('attachment')->with('inquiry:id,token');
        }])->get();
    }

    public function getTicketToken(Request $request)
    {
        return Inquiry::where('token', $request->token)->with(['remarks'=>function($remark){
            $remark->latest('timestamp');
        }])->with(['incident'=>function($incidents){
            $incidents->latest('timestamp')->with('attachment');
        }])->first();
    }

    protected function mailToClient($token, $email)
    {
        $to = $email;
        $subject = 'Your Request Ticket Number';
        ob_start();
        include(storage_path().'/app/requestsent.php');
        $message = ob_get_contents();
        ob_end_clean();
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: no-reply@bobsupportgroup.com';
        if (mail($to, $subject, $message, $headers)) {
            Log::info('Email sent: '.$to);
        }else{
            Log::info('Not Sent');
        }
    }

    private function createCustomer(array $data)
    {
        $customer = new Customer;
        $customer->name = $data['firstname'].' '.$data['middlename'].' '.$data['lastname'];
        $customer->address = $data['address'];
        $customer->company = $data['company'];
        $customer->mobile = $data['mobile'];
        $customer->email = $data['email'];
        $customer->comobile = $data['pmobile'];
        $customer->coemail = $data['pmail'];
        $customer->payment_method = $data['payment'];
        $customer->save();
        return $customer;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string'],
            'middlename' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'address' => ['required', 'string'],
            'mobile' => ['required', 'min:11', 'max:13'],
            'email' => ['required'],
            'company' => ['required', 'string'],
            'pmobile' => ['required', 'min:11', 'max:13'],
            'pmail' => ['required'],
            'POI' => ['required'],
            'headend' => ['required'],
            'service' => ['required'],
            'payment' => ['required']
        ]);
    }

    protected function generateRandomString($length = 4) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
