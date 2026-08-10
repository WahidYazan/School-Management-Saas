<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Mail::raw(
            "Nama: {$validated['name']}\nEmail: {$validated['email']}\n\n{$validated['message']}",
            function ($message) use ($validated) {
                $message->to(config('mail.from.address'))
                    ->subject("[Kontak] {$validated['subject']}")
                    ->replyTo($validated['email'], $validated['name']);
            }
        );

        return back()->with('status', 'Pesan berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
