<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

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

    // POST /api/contacts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'personal_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'document' => 'nullable|file|max:2048', // Pastikan ada validasi untuk file!
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
}
