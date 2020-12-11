<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inquiry;
use App\Customer;
use App\Remarks;
use App\Incident;
use App\Attachment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InquiryController extends Controller
{
    public function updateRemarks(Request $request)
    {
        $remark = new Remarks;
        $remark->ticket_id = $request->inquiry['id'];
        $remark->staff_id = $request->staff['id'];
        $remark->remark = $request->remark;
        try{
            $remark->save();
            $mail = $this->emailClient($request->inquiry);
            Log::info($mail);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function changeStatus(Request $request)
    {
        $inquiry = Inquiry::where('id', $request->inquiry['id'])->with('customer')->first();
        $inquiry->status = $request->status;
        if ($request->status == 'Done') {
            $message = 'Your request has been completed';
        }else{
            $message = 'We have started working on your request.';
        }
        try{
            $inquiry->save();
            $mail = $this->emailClient($inquiry);
            Log::info($mail);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function assignStaff(Request $request)
    {
        $inquiry = Inquiry::where('id', $request->inquiry['id'])->first();
        $inquiry->assigned_staff = $request->staff;
        try{
            $inquiry->save();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function confirmPayment(Request $request)
    {
        $inquiry = Inquiry::where('id', $request->inquiry['id'])->with('customer')->first();
        if ($inquiry->customer['payment_status']=='Full Payment') {
            $inquiry->payment_status = 'Completely Paid';
        }else{
            $inquiry->payment_status = 'Downpayment Paid';
        }
        try{
            $inquiry->save();
            $mail = $this->emailClient($inquiry);
            Log::info($mail);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    public function staffRequests(Request $request)
    {
        return Inquiry::where('assigned_staff', $request->user['id'])->with('customer')->with(['remarks'=>function($remark) use ($request){
            $remark->where('staff_id', $request->user['id'])->get();
        }])->with(['incident'=>function($incident) use ($request){
            $incident->where('staff_id', $request->user['id'])->with('attachment')->get();
        }])->get();
    }

    public function checkTicketStatus()
    {
        $tickets = Inquiry::with('customer')->get();
        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        foreach ($tickets as $ticket) {
            $notice = new \DateTime($ticket->expiration_date);
            date_sub($notice, date_interval_create_from_date_string("5 days"));
            $notice = $notice->format('Y-m-d');
            if ($ticket->expiration_date < $today) { 
                Storage::deleteDirectory('incidents/'.$ticket->token);
                $ticket->remarks()->delete();
                $ticket->customer->delete();
                $ticket->delete();
            }else if($today == $notice){
                $mail = $this->expirationMail($ticket, $ticket->customer['email']);
            }
        }
        Log::info('Run '.$today);
    }

    public function reportIncident(Request $request)
    {
        $request->validate([
            'data' => 'required'
        ]);
        $request->data = json_decode($request->data);
        $incident = new Incident;
        $incident->ticket_id = $request->data->ticket->id;
        $incident->staff_id = $request->data->staff->id;
        $incident->report = $request->data->desc;
        $incident->save();
        if ($request->file != null) {
            $data = array();
            foreach ($request->file as $file) {
                array_push($data, [
                    'incident_id'=>$incident->id,
                    'file_name'=>$file->getClientOriginalName(),
                    'file_type'=>$file->getClientMimeType(),
                ]);
                $filepath = 'public/incidents/'.$request->data->ticket->token;
                $path = $file->storeAs($filepath, $file->getClientOriginalName());
                Log::info($path);
            }
            Attachment::insert($data);
        }
        return 'success';
    }

    public function getPending(Request $request)
    {
        $pending = Remarks::where('staff_id', $request->id)->select('ticket_id')->distinct()->get();
        $ids = [];
        foreach ($pending as $id) {
            array_push($ids, $id->ticket_id);
        }
        return Inquiry::whereNotIn('id', $ids)->where('assigned_staff', $request->id)->get();
    }

    public function recentRemark(Request $request)
    {
        return Remarks::where('staff_id', $request->id)->with('inquiry')->latest('timestamp')->first();
    }

    public function resendEmail(Request $request)
    {
        $to = $request->email;
        $token = $request->token;
        $subject = 'Your Request Ticket Number';
        ob_start();
        include(storage_path().'/app/requestsent.php');
        $message = ob_get_contents();
        ob_end_clean();
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: no-reply@bobsupportgroup.com';
        if (mail($to, $subject, $message, $headers)) {
            return 'email sent';
        }else{
            return 'error';
        }
    }

    public function deleteRequest(Request $request)
    {
        $inquiry = Inquiry::where('id', $request->inquiry['id'])->first();
        try{
            Storage::deleteDirectory('incidents/'.$inquiry->token);
            $inquiry->remarks()->delete();
            // $inquiry->customer->delete();
            $inquiry->delete();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return 'success';
    }

    protected function emailClient($inquiry)
    {
        if (gettype($inquiry) == 'array'){
            $to = $inquiry['customer']['email'];
        }else if(gettype($inquiry) == 'object'){
            $customer = Customer::where('id', $inquiry->customer_id)->first();
            $to = $customer->email;
        }
        $subject = env('APP_NAME').' sent you a message!';
        $message = file_get_contents(storage_path().'/app/emailtemplate.html');
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: no-reply@bobsupportgroup.com';
        if(mail($to, $subject, $message, $headers)){
            return 'Email has been sent to '.$to;
        }else{
            return 'Failed';
        }
    }

    protected function expirationMail($ticket, $email)
    {
        $to = $email;
        $subject = $ticket->project_name.' Ticket Expiration';
        $message = 'Your ticket, '.$ticket->project_name.', is about to expire in 5 days.';
        $headers = 'From: no-reply@bobsupportgroup.com';
        if(mail($to, $subject, $message, $headers)){
            return 'Email has been sent to '.$to;
        }else{
            return 'Failed';
        }
    }
}
