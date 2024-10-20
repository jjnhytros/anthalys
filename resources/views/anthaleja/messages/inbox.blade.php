{{-- resources/views/anthaleja/messages/inbox.blade.php --}}

@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1 class="mb-4 d-flex justify-content-between align-items-center">
            Communications
        </h1>

        @include('anthaleja.messages.partials.bulk-actions')

        <!-- Message List -->
        <div class="list-group" id="message-list" style="max-height: 576px; overflow-y: auto;">
            <!-- Checkbox to select all -->
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <input type="checkbox" id="select-all" class="form-check-input px-2">
                <label for="select-all" class="ms-2">Select All</label>
            </div>

            @php
                $faker = Faker\Factory::create('it_IT');
                // Messaggi di esempio
                $messages = [
                    (object) [
                        'id' => 1,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Benvenuto su SoNet!',
                        'message' =>
                            'Siamo felici di darti il benvenuto su SoNet, la piattaforma che ti connette con il mondo!',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                    (object) [
                        'id' => 2,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Aggiornamento dell\'account',
                        'message' => 'Abbiamo aggiornato le tue impostazioni di sicurezza. Assicurati di rivederle.',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                    (object) [
                        'id' => 3,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Nuove funzionalità disponibili!',
                        'message' => 'Scopri tutte le nuove funzionalità che abbiamo aggiunto a SoNet.',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                    (object) [
                        'id' => 4,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Evento in arrivo',
                        'message' => 'Non perdere l\'evento del mese su SoNet. Registrati ora per partecipare.',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                    (object) [
                        'id' => 5,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Offerta di lavoro',
                        'message' =>
                            'Ciao, ti invito a candidarti per un\'offerta di lavoro su SoNet. Non lasciarti sfuggire questa opportunità!',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                    (object) [
                        'id' => 6,
                        'sender' => (object) ['name' => $faker->name()],
                        'subject' => 'Aggiornamento profilo',
                        'message' =>
                            'Abbiamo riscontrato alcune modifiche sul tuo profilo, assicurati che siano corrette.',
                        'status' => $faker->randomElement(['read', 'unread']),
                        'created_at' => $faker->dateTimeThisCentury(),
                    ],
                ];
            @endphp


            @foreach ($messages as $message)
                <div id="message-{{ $message->id }}"
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ $message->status == 'unread' ? 'list-group-item-warning' : '' }}">
                    <div class="d-flex align-items-center">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold d-flex align-items-center">
                                {!! getIcon('person-circle', 'bi', $message->sender->name) !!}
                            </div>
                            <div class="mt-2">
                                <input type="checkbox" name="messages[]" value="{{ $message->id }}"
                                    class="form-check-input" />
                                <a href="{{ route('messages.show', $message->id) }}" class="text-decoration-none">
                                    <h5>{{ $message->subject }}</h5>
                                </a>
                            </div>
                            <p class="mb-0 text-muted">
                                {{ Str::limit($message->message, 100) }} <!-- Message preview -->
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <small class="text-muted">{{ $message->created_at->format('d-m-Y H:i') }}</small>
                        <span class="badge {{ $message->status == 'unread' ? 'bg-warning text-dark' : 'bg-success' }} mt-2">
                            {{ ucfirst($message->status == 'unread' ? 'Unread' : 'Read') }}
                        </span>
                        <div class="btn-group mt-2" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="archiveMessage({{ $message->id }})">
                                {!! getIcon('archive', 'bi') !!}
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="toggleReadStatus({{ $message->id }}, '{{ $message->status }}')">
                                {!! getIcon($message->status === 'unread' ? 'envelope-open' : 'envelope', 'bi') !!}
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="deleteMessage({{ $message->id }})">
                                {!! getIcon('trash', 'bi') !!}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal for deletion confirmation -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the selected message(s)? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <form id="delete-form" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script></script>
    </div>
@endsection
