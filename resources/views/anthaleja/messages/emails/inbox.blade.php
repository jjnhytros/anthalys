@extends('layouts.mail')

@section('content')
    <div class="row">
        <!-- BEGIN INBOX -->
        <div class="col-md-12">
            <div class="card email vh-100">
                <div class="card-body">
                    <div class="row">
                        <!-- BEGIN INBOX MENU -->
                        <div class="col-md-3">
                            <h2 class="card-title">{!! getIcon('inbox', 'bi', 'Inbox') !!}</h2>
                            <button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#compose-modal">
                                {!! getIcon('pencil', 'bi', 'NEW MESSAGE') !!}
                            </button>

                            <hr>

                            <div>
                                <ul class="nav flex-column nav-pills">
                                    <li class="nav-item header">Folders</li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link active">
                                            {!! getIcon('inbox', 'bi', 'Inbox (' . $emails->count() . ')') !!}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">{!! getIcon('star', 'bi', 'Starred') !!}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">{!! getIcon('bookmark', 'bi', 'Important') !!}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">{!! getIcon('send', 'bi', 'Sent') !!}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">{!! getIcon('file-earmark', 'bi', 'Drafts') !!}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">{!! getIcon('ban', 'bi', 'Spam') !!}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- END INBOX MENU -->

                        <!-- BEGIN INBOX CONTENT -->
                        <div class="col-md-9">
                            <div class="row mb-2 px-2">
                                <div class="form-group d-flex justify-content-between align-items-center mb-3">
                                    <!-- Seleziona tutto e Azioni -->
                                    <div class="d-flex align-items-center">
                                        <!-- Seleziona tutto -->
                                        <div class="form-check me-3">
                                            <input type="checkbox" class="form-check-input" id="check-all">
                                            <label class="form-check-label" for="check-all">Seleziona tutto</label>
                                        </div>

                                        <!-- Dropdown Azioni -->
                                        <div class="btn-group me-auto">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Azioni <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="dropdown-item" id="invert-selection">Inverti
                                                        selezione</a></li>
                                                <li><a href="#" class="dropdown-item">Segna come letto</a></li>
                                                <li><a href="#" class="dropdown-item">Segna come non letto</a></li>
                                                <li><a href="#" class="dropdown-item">Segnala come spam</a></li>
                                                <li><a href="#" class="dropdown-item">Elimina</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Search Input, allineato a destra -->
                                    <div class="input-group ms-1">
                                        <input type="text" class="form-control" placeholder="Cerca...">
                                        <button type="submit" class="btn btn-primary">
                                            {!! getIcon('search', 'bi', 'Cerca') !!}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @php
                                            $emails = [];
                                            $faker = Faker\Factory::create('it_IT');
                                            for ($e = 1; $e <= 30; $e++) {
                                                $attachments = [];
                                                for ($a = 1; $a <= $faker->numberBetween(0, 3); $a++) {
                                                    $attachments[] = $faker->filePath();
                                                }

                                                $emails[] = (object) [
                                                    'id' => $e,
                                                    'sender' => (object) ['name' => $faker->name()],
                                                    'subject' => $faker->sentence(5),
                                                    'status' => $faker->randomElement(['read', 'unread']),
                                                    'attachments' => $faker->boolean()
                                                        ? json_encode($attachments)
                                                        : null,
                                                    'created_at' => $faker->randomElement([
                                                        \Carbon\Carbon::parse($faker->dateTimeThisCentury()),
                                                        \Carbon\Carbon::parse($faker->dateTimeThisMonth()),
                                                        \Carbon\Carbon::today()->setTime(
                                                            $faker->numberBetween(0, now()->hour), // Ora casuale fino all'ora corrente
                                                            $faker->numberBetween(0, now()->minute), // Minuti casuali fino ai minuti attuali
                                                            $faker->numberBetween(0, now()->second - 1), // Secondi casuali, inferiori a "now"
                                                        ),
                                                    ]),
                                                ];
                                            }
                                            usort($emails, function ($a, $b) {
                                                return $b->created_at->timestamp - $a->created_at->timestamp;
                                            });
                                        @endphp

                                        @foreach ($emails as $email)
                                            <tr class="{{ $email->status == 'unread' ? 'table-warning' : '' }}">
                                                <td class="action">
                                                    <input type="checkbox" class="form-check-input email-checkbox mx-1"
                                                        value="{{ $email->id }}" />
                                                </td>
                                                <td class="action">
                                                    <div class="d-flex justify-content-start">
                                                        <span class="mx-1">{!! getIcon('star') !!}</span>
                                                        <span class="mx-1">{!! getIcon('bookmark') !!}</span>
                                                        @if ($email->attachments)
                                                            <span class="mx-1">{!! getIcon('paperclip') !!}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="name">
                                                    <a
                                                        href="{{ route('messages.show', $email->id) }}">{{ $email->sender->name }}</a>
                                                </td>
                                                <td class="subject">
                                                    <a
                                                        href="{{ route('messages.show', $email->id) }}">{{ $email->subject }}</a>
                                                </td>
                                                <td class="time">
                                                    @if ($email->created_at->diffInMinutes(now()) <= 5)
                                                        Poco fa
                                                    @elseif ($email->created_at->isToday())
                                                        Oggi alle {{ $email->created_at->format('H:i') }}
                                                    @elseif ($email->created_at->isYesterday())
                                                        Ieri alle {{ $email->created_at->format('H:i') }}
                                                    @else
                                                        {{ $email->created_at->format('Y, d/m H:i') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <nav>
                                <ul class="pagination">
                                    <li class="page-item disabled"><a href="#" class="page-link">«</a></li>
                                    <li class="page-item active"><a href="#" class="page-link">1</a></li>
                                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                                    <li class="page-item"><a href="#" class="page-link">»</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- END INBOX CONTENT -->

                        <!-- BEGIN COMPOSE MESSAGE -->
                        <div class="modal fade" id="compose-modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-hidden="true"></button>
                                        <h4 class="modal-title">
                                            {!! getIcon('envelope', 'bi', 'Compose New Message') !!}
                                        </h4>
                                    </div>
                                    <form action="{{ route('messages.send') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input name="to" type="email" class="form-control"
                                                    placeholder="To">
                                            </div>
                                            <div class="form-group">
                                                <input name="subject" type="text" class="form-control"
                                                    placeholder="Subject">
                                            </div>
                                            <div class="form-group">
                                                <textarea name="message" class="form-control" placeholder="Message" rows="5"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="attachment">Allegati</label>
                                                <input type="file" name="attachments[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                {!! getIcon('times', 'bi', 'Discard') !!}
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                {!! getIcon('envelope', 'bi', 'Send Message') !!}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- END COMPOSE MESSAGE -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END INBOX -->
    </div>

@endsection
