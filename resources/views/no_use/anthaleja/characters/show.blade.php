@extends('layouts.main')

@section('content')
    <!-- Meteo corrente -->
    @php
        $weatherClass = '';
        $weather = app(\App\Services\TimeService::class)->generateWeather();

        switch ($weather->type) {
            case 'Sole':
                $weatherVideo = '/videos/sunny.mp4';
                break;
            case 'Pioggia':
                $weatherVideo = '/videos/rain.mp4';
                break;
            case 'Tempesta':
                $weatherVideo = '/videos/storm.mp4';
                break;
            case 'Neve':
                $weatherVideo = '/videos/snow.mp4';
                break;
        }
    @endphp
    <div class="weather-bg">

        <h1>Dettagli del Personaggio: {{ $character->name }}</h1>
        <!-- Mostra il punteggio di reputazione -->
        <p>Reputazione: {{ $character->reputation->reputation_score ?? 0 }}</p>

        <!-- Visualizza l'aspetto personalizzato -->
        <div>
            <p>Abbigliamento: {{ $character->customization->outfit ?? 'Default' }}</p>
            <p>Acconciatura: {{ $character->customization->hair_style ?? 'Corto' }}</p>
            <p>Accessorio: {{ $character->customization->accessory ?? 'Nessuno' }}</p>
        </div>

        <!-- Link per personalizzare -->
        <a href="{{ route('characters.customize', $character) }}" class="btn btn-primary">Personalizza il Personaggio</a>

        <p>Tempo corrente: {{ app(\App\Services\TimeService::class)->getFormattedTime() }}
            ({{ app(\App\Services\TimeService::class)->isDaytime() ? 'Giorno' : 'Notte' }})
        </p>

        @if (app(\App\Services\TimeService::class)->isDaytime())
            <p>È giorno! Puoi lavorare o fare altre attività diurne.</p>
        @else
            <p>È notte! Puoi dormire o riposarti.</p>
        @endif
        <!-- Controlliamo se c'è un evento di calendario -->
        @php
            $event = \App\Models\CalendarEvent::where(
                'day',
                app(\App\Services\TimeService::class)->getCurrentTime()['day'],
            )
                ->where('month', app(\App\Services\TimeService::class)->getCurrentTime()['month'])
                ->first();
        @endphp

        @if ($event)
            <p>Oggi è la festività: <strong>{{ $event->name }}</strong></p>
            <p>{{ $event->effects['description'] ?? '' }}</p>
        @endif

        @if ($weather)
            <p>Condizioni meteo: <strong>{{ $weather->type }}</strong></p>
            <p>Effetti sul personaggio: Felicità: {{ $weather->effects['happiness_change'] ?? 0 }}, Energia:
                {{ $weather->effects['energy_change'] ?? 0 }}</p>
        @else
            <p>Nessun cambiamento meteo significativo oggi.</p>
        @endif

        <!-- Previsioni per i prossimi giorni -->
        <h3>Previsioni Meteo per i Prossimi Giorni</h3>
        @php
            $forecasts = app(\App\Services\WeatherForecastService::class)->getForecastsForNextDays(7);
        @endphp

        <ul>
            @foreach ($forecasts as $forecast)
                <li>
                    Giorno {{ $forecast->day }}/{{ $forecast->month }} - Previsione: {{ $forecast->weather_type }}
                    (Accuratezza: {{ $forecast->accuracy }}%)
                </li>
            @endforeach
        </ul>

        <!-- Mostra i dettagli del personaggio -->
        <ul>
            <li>Felicità: {{ $character->happiness }}</li>
            <li>Energia: {{ $character->energy }}</li>
            <li>Denaro: {{ $character->money }} $</li>
        </ul>

        <!-- Form per aggiungere un'azione -->
        <form action="{{ route('characters.actions.store', $character) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="action_type">Azione</label>
                <select name="action_type" class="form-control" required>
                    <option value="work">Lavoro</option>
                    <option value="sleep">Dormire</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Esegui Azione</button>
        </form>
    </div>
@endsection
