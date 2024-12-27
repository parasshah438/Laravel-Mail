<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendFileMail;

class AttachmentController extends Controller
{
    public function attachment()
    {
        $email = config('app.adminEmail');
        $filePath = public_path('files/sample.pdf');

        //Check if the attachment file exists
        if (!file_exists($filePath)) {
            dd('Attachment file does not exist: ' . $filePath);
        }
        try {
            //Queue the mailable with the attachment
            Mail::to($email)->queue(new SendFileMail($filePath));
            dd('success', 'Email with attachment has been sent successfully!');
        } catch (\Exception $e) {
            dd('Failed to send email to ' . $email . ': ' . $e->getMessage());
        }
    }
}