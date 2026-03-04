<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Models\Contact;
use App\Services\ClientCodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::query()
            ->ordered()
            ->withCount('contacts')
            ->get();

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return $this->index();
    }

    public function store(
        StoreClientRequest $request,
        ClientCodeGeneratorService $clientCodeGeneratorService
    ): RedirectResponse {
        $client = DB::transaction(function () use ($request, $clientCodeGeneratorService): Client {
            $client = Client::query()->create([
                'name' => $request->string('name')->toString(),
            ]);

            $client->update([
                'client_code' => $clientCodeGeneratorService->generateForName($client->name),
            ]);

            return $client;
        });

        return redirect()
            ->route('clients.show', $client)
            ->with('status', 'Client created successfully.');
    }

    public function show(Request $request, Client $client): View
    {
        $client->load([
            'contacts' => fn ($query) => $query->ordered(),
        ]);
        $linkedContactIds = $client->contacts->pluck('id');
        $availableContacts = Contact::query()
            ->ordered()
            ->whereNotIn('id', $linkedContactIds)
            ->get();

        return view('clients.show', [
            'client' => $client,
            'availableContacts' => $availableContacts,
            'selectedContactId' => $this->selectedId($request, 'contact_id', $availableContacts),
            'openGeneralModal' => $request->boolean('edit'),
        ]);
    }

    private function selectedId(Request $request, string $field, Collection $availableItems): ?int
    {
        $requestedId = $request->integer($field);

        if ($requestedId > 0 && $availableItems->contains('id', $requestedId)) {
            return $requestedId;
        }

        return $availableItems->first()?->id;
    }
}
