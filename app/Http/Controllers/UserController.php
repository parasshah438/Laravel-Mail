<?php

namespace App\Http\Controllers;

use App\Mail\CustomMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendFileUploadedMail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    public function sendMail(Request $request)
    {
        //Validate the request
        $validatedData = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            // Fetch users based on selected IDs
            $users = User::whereIn('id', $validatedData['user_ids'])->get();

            foreach ($users as $user) {
                try {
                    // Queue email for each user
                    Mail::to($user->email)->queue(new CustomMail($user));
                } catch (\Exception $e) {
                    // Log errors if email fails
                    Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            // Notify success
            notify()->success('Emails have been sent successfully!');
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error during email sending: ' . $e->getMessage());

            // Notify error
            notify()->error('An unexpected error occurred while sending emails.');
        }

        // Redirect back to the form
        return back();
    }


    public function attachment(Request $request)
    {
        //Validate the request
        $validatedData = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            // Fetch users based on selected IDs
            $users = User::whereIn('id', $validatedData['user_ids'])->get();

            foreach ($users as $user) {
                try {
                    // Queue email for each user
                    Mail::to($user->email)->queue(new CustomMail($user));
                } catch (\Exception $e) {
                    // Log errors if email fails
                    Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            // Notify success
            notify()->success('Emails have been sent successfully!');
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Unexpected error during email sending: ' . $e->getMessage());

            // Notify error
            notify()->error('An unexpected error occurred while sending emails.');
        }

        // Redirect back to the form
        return back();
    }


    //upload file
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        //Store the uploaded file in the public directory
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = public_path('uploads/' . $fileName);
        $file->move(public_path('uploads'), $fileName);

        //Check if the file exists
        if (!file_exists($filePath)) {
            Log::error('Uploaded file does not exist: ' . $filePath);
            return back()->with('error', 'Uploaded file does not exist.');
        }

        try {
            $email = config('app.adminEmail');

            Log::info("Sending email with attachment: " . $filePath);

            //Queue the mailable with the attachment
            Mail::to($email)->queue(new SendFileUploadedMail($filePath));
            return back()->with('success', 'Email with attachment has been sent successfully!');
        } catch (\Exception $e) {
            
            Log::error('Failed to send email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email.');
        }
    }
}
