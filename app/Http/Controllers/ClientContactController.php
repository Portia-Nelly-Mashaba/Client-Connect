<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ClientContactController extends Controller
{
    public function linkContactToClient(Client $client, Contact $contact): JsonResponse
    {
        if ($client->contacts()->whereKey($contact->getKey())->exists()) {
            return response()->json([
                'message' => 'Contact is already linked to this client.',
            ], 409);
        }

        $client->contacts()->attach($contact->getKey());

        return response()->json([
            'message' => 'Contact linked to client successfully.',
        ], 201);
    }

    public function unlinkContactFromClient(Client $client, Contact $contact): JsonResponse
    {
        $detached = $client->contacts()->detach($contact->getKey());

        if ($detached === 0) {
            return response()->json([
                'message' => 'Contact is not linked to this client.',
            ], 404);
        }

        return response()->json([
            'message' => 'Contact unlinked from client successfully.',
        ]);
    }

    public function linkClientToContact(Contact $contact, Client $client): JsonResponse
    {
        if ($contact->clients()->whereKey($client->getKey())->exists()) {
            return response()->json([
                'message' => 'Client is already linked to this contact.',
            ], 409);
        }

        $contact->clients()->attach($client->getKey());

        return response()->json([
            'message' => 'Client linked to contact successfully.',
        ], 201);
    }

    public function unlinkClientFromContact(Contact $contact, Client $client): JsonResponse
    {
        $detached = $contact->clients()->detach($client->getKey());

        if ($detached === 0) {
            return response()->json([
                'message' => 'Client is not linked to this contact.',
            ], 404);
        }

        return response()->json([
            'message' => 'Client unlinked from contact successfully.',
        ]);
    }
}
