<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendContactEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function submitForm(Request $request)
    {
        $adminEmail = config('app.adminEmail');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:15',
            'message' => 'required|string',
        ]);

        //Dispatch using job
        //SendContactEmail::dispatch($request->all());

        //Direct mail
        Mail::to($adminEmail)->queue(new ContactMail($request->all()));
        
        return back()->with('success', 'Your message has been sent successfully!');
    }
}
