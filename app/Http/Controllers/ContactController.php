<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function index(): JsonResponse
    {
        $contacts = Contact::query()
            ->ordered()
            ->withCount('clients')
            ->get();

        return response()->json([
            'data' => $contacts,
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'message' => 'Submit name, surname, and email to create a contact.',
        ]);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = Contact::query()->create([
            'name' => $request->string('name')->toString(),
            'surname' => $request->string('surname')->toString(),
            'email' => strtolower($request->string('email')->toString()),
        ]);

        return response()->json([
            'message' => 'Contact created successfully.',
            'data' => $contact,
        ], 201);
    }

    public function show(Contact $contact): JsonResponse
    {
        $contact->load([
            'clients' => fn ($query) => $query->ordered(),
        ]);
        $contact->loadCount('clients');

        return response()->json([
            'data' => $contact,
        ]);
    }
}
