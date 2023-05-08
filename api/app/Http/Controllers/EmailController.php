<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactForm;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\ValidateForm;


class EmailController extends Controller
{
    public function SendEmail(ValidateForm $request) {
        $name = $request->input('name');
        $email = $request->input('email');
        $content = $request->input('content');
        $subject = $request->input('subject');
        try{
            $response = Mail::to('contacto@luifereduardoo.com')->send(new ContactForm($name, $email, $content, $subject));
            if($response){
                $settled_data_dase = DB::table('information_contact')->pluck('settled');
                $settledGenerate = Str::random(16);;
                forEach($settled_data_dase as $settled){
                    if($settled === $settledGenerate){
                        $settledGenerate = Str::random(16);
                    }
                }
                DB::insert('INSERT INTO information_contact (name, email, subject, content, date, settled) VALUES (?, ?, ?, ?, ?, ?)', [$name, $email, $subject, $content, date('Y-m-d H:i:s'), $settledGenerate]);

                Mail::to($email)->send(new NotificationEmail($name, $email, $content, $subject, $settledGenerate));
                return response()->json(['message' => 'Email sent successfully'], 200);
            }
            else {
                throw new Exception('Email could not be sent');
            }
        }catch(Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}