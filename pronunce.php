<?php

// Funzione per convertire un testo nella pronuncia anthaliana
function convertToAnthalianPronunciation($text)
{
    // Mappature delle sostituzioni per le lettere e suoni speciali
    $pronunciationMap = [
        'ĉia' => 'tʃia',
        'ĉie' => 'tʃie',
        'ĉio' => 'tʃio',
        'ĉiu' => 'tʃiu',
        'ĝia' => 'dʒia',
        'ĝie' => 'dʒie',
        'ĝio' => 'dʒio',
        'ĝiu' => 'dʒiu',
        'gl'   => 'hl',
        'gn'   => 'hn',
        'ĝ'   => 'dʒ',
        'ĉ'   => 'tʃ',
        'h'    => 'h',  // 'h' raddoppia le vocali in specifiche circostanze
        'e'    => '/e:|ɛ:/',
        'o' => '/o:|ɔ:/',
        'w'    => '/u:/',
        'y' => '/i:/',
        'x'    => '/ʃ/',
        'j' => '/ʒ/',
        'a'    => '/a/',
        'b' => '/b/',
        'd' => '/d/',
        'f' => '/f/',
        'g' => '/g/',
        'i'    => '/i/',
        'k' => '/k/',
        'l' => '/l/',
        'm' => '/m/',
        'n' => '/n/',
        'p'    => '/p/',
        'r' => '/r/',
        's' => '/s/ o /z/',
        't' => '/t/',
        'v' => '/v/',
        'z'    => '/ts/ o /dz/'
    ];

    // Sostituzioni per raddoppiare le vocali seguite o precedute da 'h'
    $doubleVowelMap = [
        'ah' => 'aa',
        'eh' => 'ee',
        'oh' => 'oo'
    ];

    // Passaggio 1: Sostituire i suoni speciali
    foreach ($pronunciationMap as $pattern => $replacement) {
        $text = preg_replace('/' . $pattern . '/', $replacement, $text);
    }

    // Passaggio 2: Raddoppiare le vocali con 'h'
    foreach ($doubleVowelMap as $pattern => $replacement) {
        $text = preg_replace('/' . $pattern . '/', $replacement, $text);
    }

    return $text;
}

// Esempio di utilizzo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputText = $_POST['inputText'];
    $convertedText = convertToAnthalianPronunciation($inputText);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Convertitore di Pronuncia Anthaliana</title>
</head>

<body>
    <h1>Convertitore di Pronuncia Anthaliana</h1>

    <form method="POST" action="">
        <label for="inputText">Inserisci il testo da convertire:</label><br>
        <textarea id="inputText" name="inputText" rows="5" cols="50"><?php echo isset($inputText) ? $inputText : ''; ?></textarea><br><br>
        <input type="submit" value="Converti">
    </form>

    <?php if (isset($convertedText)): ?>
        <h2>Testo convertito in pronuncia anthaliana:</h2>
        <p><?php echo htmlspecialchars($convertedText); ?></p>
    <?php endif; ?>
</body>

</html>
