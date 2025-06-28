<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\ContactApprovalMail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('kontak.index', compact('contacts'));
    }

    public function view()
    {
        $contacts = Contact::latest()->get();
        return view('kontak.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'personal_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'document' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documents', $filename, 'public');
            $validated['document'] = $path;
        }

        $contact = Contact::create($validated);

        return response()->json([
            'message' => 'Contact submitted successfully',
            'data' => $contact,
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kontak berhasil dihapus']);
        }

        return redirect()->route('kontak.index')->with('success', 'Kontak berhasil dihapus');
    }

    public function approve(Contact $contact)
    {
        if (!$contact->email) {
            return redirect()->back()->with('error', 'Email tidak tersedia untuk kontak ini.');
        }

        try {
            Mail::to($contact->email)->send(new ContactApprovalMail($contact));
            return redirect()->back()->with('success', 'Pesan persetujuan berhasil dikirim ke ' . $contact->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
