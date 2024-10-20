<!DOCTYPE html>
<html>

<head>
    <title>Convertitore di Pronuncia Anthaliana</title>
</head>

<body>
    <h1>Convertitore di Pronuncia Anthaliana</h1>

    <form method="POST" action="{{ url('/pronunciation') }}">
        @csrf
        <label for="inputText">Inserisci il testo da convertire:</label><br>
        <textarea id="inputText" name="inputText" rows="5" cols="50">{{ old('inputText', $inputText ?? '') }}</textarea><br><br>
        <input type="submit" value="Converti">
    </form>

    @if (isset($convertedText))
        <h2>Testo convertito in pronuncia anthaliana:</h2>
        <p>{{ $convertedText }}</p>
    @endif
</body>

</html>
