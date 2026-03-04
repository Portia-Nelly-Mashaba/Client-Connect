<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class ContactController extends Controller
{
    public function index(): View
    {
        $contacts = Contact::query()
            ->ordered()
            ->withCount('clients')
            ->get();

        return view('contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return $this->index();
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $contact = Contact::query()->create([
            'name' => $request->string('name')->toString(),
            'surname' => $request->string('surname')->toString(),
            'email' => strtolower($request->string('email')->toString()),
        ]);

        return redirect()
            ->route('contacts.show', $contact)
            ->with('status', 'Contact created successfully.');
    }

    public function show(Request $request, Contact $contact): View
    {
        $contact->load([
            'clients' => fn ($query) => $query->ordered(),
        ]);
        $contact->loadCount('clients');
        $linkedClientIds = $contact->clients->pluck('id');
        $availableClients = Client::query()
            ->ordered()
            ->whereNotIn('id', $linkedClientIds)
            ->get();

        return view('contacts.show', [
            'contact' => $contact,
            'availableClients' => $availableClients,
            'selectedClientId' => $this->selectedId($request, 'client_id', $availableClients),
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
