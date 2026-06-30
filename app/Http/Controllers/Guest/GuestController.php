<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Mail\ContactSupportMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GuestController extends Controller
{
    public function privacy()
    {
        return view('pages.privacy-policy');
    }

    public function terms()
    {
        return view('pages.terms-of-service');
    }

    public function contact()
    {
        return view('pages.contact-support');
    }

    /**
     * Store a contact message submission and forward it by email.
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Persist to DB
        ContactMessage::create($validated);

        // Forward email to support inbox
        try {
            Mail::to('maureenthana@gmail.com')->send(new ContactSupportMail(
                senderName:  $validated['name'],
                senderEmail: $validated['email'],
                messageSubject: $validated['subject'],
                body:        $validated['message'],
            ));
        } catch (\Exception $e) {
            // Log silently — don't fail the user if mail config is missing
            Log::warning('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->route('guest.contact')
                         ->with('success', 'Your message has been sent! We\'ll get back to you within 24 hours.');
    }
}
