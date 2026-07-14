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

        // Always persist to DB — this must never be blocked by a mail failure
        ContactMessage::create($validated);

        // Send notification email — wrapped so a mail config issue
        // never causes a 500 for the user submitting the form.
        try {
            Mail::to('rinaqonitah@gmail.com')
                ->send(new ContactSupportMail($validated['subject'], $validated['message']));

            return back()->with('success', 'Pesan berhasil dikirim! Kami akan membalas dalam 24 jam.');

        } catch (\Throwable $e) {
            // Log the real error so it is visible in Railway logs
            Log::error('[ContactSupport] Mail failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Message was saved to DB; inform user without exposing internals
            return back()->with('error', 'Gagal mengirim pesan melalui email, silakan coba lagi atau hubungi kami langsung.');
        }
    }
}
