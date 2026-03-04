@extends('layouts.glass', ['title' => 'Clients - ClientConnect'])

@section('content')
    <div class="glass header">
        <div>
            <h1 class="title">Clients</h1>
            <p class="subtitle">ClientConnect dashboard</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Contacts</a>
            <button type="button" class="btn btn-primary" data-open-modal="#create-client-modal">+ New Client</button>
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

    <section class="glass card">
        <div class="table-wrap">
            @if ($clients->isEmpty())
                <p class="muted">No client(s) found.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Client code</th>
                            <th class="center">No. of linked contacts</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->client_code ?? '-' }}</td>
                                <td class="center">{{ $client->contacts_count }}</td>
                                <td><a class="btn btn-secondary" href="{{ route('clients.show', $client) }}">Open</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    <div id="create-client-modal" class="modal-overlay" data-modal-overlay>
        <div class="glass modal">
            <h2 style="margin-top: 0;">Create Client</h2>
            <p class="subtitle" style="margin: 6px 0 18px;">Client code is auto-generated after save.</p>

            <form method="post" action="{{ route('clients.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" data-close-modal="#create-client-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = document.querySelector('#create-client-modal');
                if (modal) {
                    modal.classList.add('open');
                }
            });
        </script>
    @endif
@endpush
