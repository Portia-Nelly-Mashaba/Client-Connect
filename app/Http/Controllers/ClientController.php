<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Services\ClientCodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view('clients.show', [
            'client' => $client,
            'openGeneralModal' => $request->boolean('edit'),
        ]);
    }
}
