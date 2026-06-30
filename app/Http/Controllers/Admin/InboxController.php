<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class InboxController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        $unreadCount = $messages->where('is_read', false)->count();

        // Mark all as read when viewing
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        return view('admin.inbox', compact('messages', 'unreadCount'));
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();

        return redirect()->route('admin.inbox')
                         ->with('success', 'Message deleted.');
    }
}
