<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Mail\ContactMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = ContactMessage::orderBy('created_at', 'desc');

        if ($request->status === 'read') {
            $query->where('is_read', true);
        } elseif ($request->status === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->status === 'replied') {
            $query->whereNotNull('replied_at');
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15);

        // Stats for cards
        $totalMessages = ContactMessage::count();
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $readMessages = ContactMessage::where('is_read', true)->count();
        $repliedMessages = ContactMessage::whereNotNull('replied_at')->count();

        return view('admin.contacts.index', compact(
            'messages',
            'totalMessages',
            'unreadMessages',
            'readMessages',
            'repliedMessages'
        ));
    }

    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        return view('admin.contacts.show', compact('message'));
    }

    public function showReply(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }
        return view('admin.contacts.reply', compact('message'));
    }

    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reply_message' => 'required|string|max:5000',
        ]);

        try {
            Mail::to($message->email)->send(new ContactMessageReply($message, $request->reply_message));
            
            $message->update([
                'replied_at' => now(),
                'is_read' => true,
            ]);

            return redirect()->route('admin.contacts.index')
                ->with('success', 'Reply has been sent successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to send reply email: ' . $e->getMessage());
            return back()->withErrors('Failed to send reply. Please try again.');
        }
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message has been deleted.');
    }

    public function newsletters()
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.newsletters.index', compact('newsletters'));
    }

    public function exportNewsletters()
    {
        $emails = Newsletter::where('is_active', true)
            ->pluck('email')
            ->implode("\n");

        return response($emails)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="newsletter_emails.txt"');
    }

    public function destroyNewsletter(Newsletter $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Newsletter subscription has been deleted.');
    }
}
