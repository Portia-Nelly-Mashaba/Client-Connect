@extends('layouts.glass', ['title' => 'Contact Details - ClientConnect'])

@section('content')
    <div class="glass header">
        <div>
            <h1 class="title">Contact Details</h1>
            <p class="subtitle">{{ $contact->surname }} {{ $contact->name }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('contacts.index') }}" class="btn btn-primary">Back to Contacts</a>
        </div>
    </div>

    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif
    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <div class="tabs-shell">
        <div class="tabs">
            <a
                href="#"
                class="tab-button active"
                data-tab-button
                data-tab-group="contact-show"
                data-tab-target="#tab-general"
            >
                General
            </a>
            <a
                href="#"
                class="tab-button"
                data-tab-button
                data-tab-group="contact-show"
                data-tab-target="#tab-clients"
            >
                Client(s)
            </a>
        </div>
    </div>

    <section id="tab-general" class="glass card tab-panel active" data-tab-panel data-tab-group="contact-show">
        <div class="kv">
            <div class="field">
                <label for="contact-name-readonly">Name</label>
                <input id="contact-name-readonly" type="text" value="{{ $contact->name }}" readonly>
            </div>
            <div class="field">
                <label for="contact-surname-readonly">Surname</label>
                <input id="contact-surname-readonly" type="text" value="{{ $contact->surname }}" readonly>
            </div>
            <div class="field">
                <label for="contact-email-readonly">Email</label>
                <input id="contact-email-readonly" type="text" value="{{ $contact->email }}" readonly>
            </div>
        </div>
    </section>

    <section id="tab-clients" class="glass card tab-panel" data-tab-panel data-tab-group="contact-show">
        @if ($availableClients->isNotEmpty())
            <form method="post" action="{{ route('contacts.clients.attach', $contact) }}" class="link-row">
                @csrf
                <div class="field">
                    <label for="client_id">Link client</label>
                    <select id="client_id" name="client_id" required>
                        @foreach ($availableClients as $client)
                            <option value="{{ $client->id }}" @selected($selectedClientId === $client->id)>
                                {{ $client->name }} - {{ $client->client_code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Link</button>
            </form>
        @endif

        <div class="table-wrap">
            @if ($contact->clients->isEmpty())
                <p class="muted">No contact(s) found.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Client name</th>
                            <th>Client code</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contact->clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->client_code }}</td>
                                <td>
                                    <form
                                        method="post"
                                        action="{{ route('contacts.clients.unlink', ['contact' => $contact, 'client' => $client]) }}"
                                        class="inline-form"
                                    >
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-xs">Unlink</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>
@endsection
