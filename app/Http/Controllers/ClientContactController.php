<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientContactController extends Controller
{
    public function attachContactToClient(Request $request, Client $client): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
        ]);

        $contact = Contact::query()->findOrFail($validated['contact_id']);

        return $this->linkContactToClient($request, $client, $contact);
    }

    public function linkContactToClient(Request $request, Client $client, Contact $contact): JsonResponse|RedirectResponse
    {
        if ($client->contacts()->whereKey($contact->getKey())->exists()) {
            return $this->conflictResponse($request, 'Contact is already linked to this client.');
        }

        $client->contacts()->attach($contact->getKey());

        return $this->successResponse($request, 'Contact linked to client successfully.', 201);
    }

    public function unlinkContactFromClient(Request $request, Client $client, Contact $contact): JsonResponse|RedirectResponse
    {
        $detached = $client->contacts()->detach($contact->getKey());

        if ($detached === 0) {
            return $this->notFoundResponse($request, 'Contact is not linked to this client.');
        }

        return $this->successResponse($request, 'Contact unlinked from client successfully.');
    }

    public function linkClientToContact(Request $request, Contact $contact, Client $client): JsonResponse|RedirectResponse
    {
        if ($contact->clients()->whereKey($client->getKey())->exists()) {
            return $this->conflictResponse($request, 'Client is already linked to this contact.');
        }

        $contact->clients()->attach($client->getKey());

        return $this->successResponse($request, 'Client linked to contact successfully.', 201);
    }

    public function unlinkClientFromContact(Request $request, Contact $contact, Client $client): JsonResponse|RedirectResponse
    {
        $detached = $contact->clients()->detach($client->getKey());

        if ($detached === 0) {
            return $this->notFoundResponse($request, 'Client is not linked to this contact.');
        }

        return $this->successResponse($request, 'Client unlinked from contact successfully.');
    }

    public function attachClientToContact(Request $request, Contact $contact): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'integer', 'exists:clients,id'],
        ]);

        $client = Client::query()->findOrFail($validated['client_id']);

        return $this->linkClientToContact($request, $contact, $client);
    }

    private function successResponse(Request $request, string $message, int $statusCode = 200): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], $statusCode);
        }

        return back()->with('status', $message);
    }

    private function conflictResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 409);
        }

        return back()->with('error', $message);
    }

    private function notFoundResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 404);
        }

        return back()->with('error', $message);
    }
}
