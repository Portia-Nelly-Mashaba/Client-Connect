@extends('layouts.glass', ['title' => 'Contacts - ClientConnect'])

@section('content')
    <div class="glass header">
        <div>
            <h1 class="title">Contacts</h1>
            <p class="subtitle">Contact directory</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Clients</a>
            <button type="button" class="btn btn-primary" data-open-modal="#create-contact-modal">+ New Contact</button>
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
            @if ($contacts->isEmpty())
                <p class="muted">No contact(s) found.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Email</th>
                            <th class="center">No. of linked clients</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->surname }}</td>
                                <td>{{ $contact->email }}</td>
                                <td class="center">{{ $contact->clients_count }}</td>
                                <td><a class="btn btn-secondary" href="{{ route('contacts.show', $contact) }}">Open</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    <div id="create-contact-modal" class="modal-overlay" data-modal-overlay>
        <div class="glass modal">
            <h2 style="margin-top: 0;">Create Contact</h2>
            <p class="subtitle" style="margin: 6px 0 18px;">All fields are required.</p>

            <form method="post" action="{{ route('contacts.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" maxlength="255" required>
                </div>

                <div class="field">
                    <label for="surname">Surname</label>
                    <input id="surname" name="surname" type="text" value="{{ old('surname') }}" maxlength="255" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" data-close-modal="#create-contact-modal">Cancel</button>
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
                var modal = document.querySelector('#create-contact-modal');
                if (modal) {
                    modal.classList.add('open');
                }
            });
        </script>
    @endif
@endpush
