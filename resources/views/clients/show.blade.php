@extends('layouts.glass', ['title' => 'Client Details - ClientConnect'])

@section('content')
    <div class="glass header">
        <div>
            <h1 class="title">Client Details</h1>
            <p class="subtitle">{{ $client->name }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" data-open-modal="#edit-client-modal">Edit General</button>
            <a href="{{ route('clients.index') }}" class="btn btn-primary">Back to Clients</a>
        </div>
    </div>

    @if (session('status'))
        <p class="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tabs">
        <button
            type="button"
            class="tab-button active"
            data-tab-button
            data-tab-group="client-show"
            data-tab-target="#tab-general"
        >
            General
        </button>
        <button
            type="button"
            class="tab-button"
            data-tab-button
            data-tab-group="client-show"
            data-tab-target="#tab-contacts"
        >
            Contact(s)
        </button>
    </div>

    <section id="tab-general" class="glass card tab-panel active" data-tab-panel data-tab-group="client-show">
        <div class="kv">
            <p><strong>Name:</strong> {{ $client->name }}</p>
            <p><strong>Client code:</strong> {{ $client->client_code }}</p>
        </div>
    </section>

    <section id="tab-contacts" class="glass card tab-panel" data-tab-panel data-tab-group="client-show">
        <div class="table-wrap">
            @if ($client->contacts->isEmpty())
                <p class="muted">No contacts found.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Contact full name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($client->contacts as $contact)
                            <tr>
                                <td>{{ $contact->surname }} {{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    <div id="edit-client-modal" class="modal-overlay" data-modal-overlay>
        <div class="glass modal">
            <h2 style="margin-top: 0;">General Details</h2>
            <p class="subtitle" style="margin: 6px 0 18px;">Client code remains system-generated and readonly.</p>

            <div class="field">
                <label>Name</label>
                <input type="text" value="{{ $client->name }}" readonly>
            </div>

            <div class="field">
                <label>Client code</label>
                <input type="text" value="{{ $client->client_code }}" readonly>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-primary" data-close-modal="#edit-client-modal">Close</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (!empty($openGeneralModal) && $openGeneralModal)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = document.querySelector('#edit-client-modal');
                if (modal) {
                    modal.classList.add('open');
                }
            });
        </script>
    @endif
@endpush
