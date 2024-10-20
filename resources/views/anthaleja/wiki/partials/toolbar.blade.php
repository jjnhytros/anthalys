<!-- Toolbar di Formattazione -->
<div class="btn-toolbar mb-2" role="toolbar">
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-primary"
            onclick="document.execCommand('copy')">{!! getIcon('clipboard', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-primary"
            onclick="document.execCommand('cut')">{!! getIcon('scissors', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-primary"
            onclick="document.execCommand('paste')">{!! getIcon('clipboard-plus', 'bi') !!}</button>
    </div>

    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-warning"
            onclick="document.execCommand('undo')">{!! getIcon('arrow-counterclockwise', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="document.execCommand('redo')">{!! getIcon('arrow-clockwise', 'bi') !!}</button>
    </div>


    <!-- Gruppo 1: Formattazione Testo (grassetto, corsivo, barrato, sottolineato) -->
    <div class="btn-group me-1 mb-1" role="group">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('bold')">{!! getIcon('type-bold', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('italic')">{!! getIcon('type-italic', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('underline')">{!! getIcon('type-underline', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('strikethrough')">{!! getIcon('type-strikethrough', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('superscript')">{!! getIcon('superscript', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('subscript')">{!! getIcon('subscript', 'bi') !!}</button>
    </div>
    <!-- Gruppo 2: Header (Dropdown per H1, H2, H3, H4, H5, H6) -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            Headers
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h1')">{!! getIcon('type-h1', 'bi') !!}</a></li>
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h2')">{!! getIcon('type-h2', 'bi') !!}</a></li>
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h3')">{!! getIcon('type-h3', 'bi') !!}</a></li>
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h4')">{!! getIcon('type-h4', 'bi') !!}</a></li>
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h5')">{!! getIcon('type-h5', 'bi') !!}</a></li>
            <li><a class="dropdown-item" href="#" onclick="applyFormat('h6')">{!! getIcon('type-h6', 'bi') !!}</a></li>
        </ul>
    </div>

    <!-- Gruppo 3: Liste ed Elenchi -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('ul')">{!! getIcon('list-ul', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('ol')">{!! getIcon('list-ol', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyIndent('increase')">{!! getIcon('text-indent-right', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyIndent('decrease')">{!! getIcon('text-indent-left', 'bi') !!}</button>
    </div>

    <!-- Gruppo 4: Link e Immagini -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyLink()">{!! getIcon('link', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyImage()">{!! getIcon('image', 'bi') !!}</button>
    </div>

    <!-- Gruppo 5: Blocchi di Codice e Citazioni -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('codeblock')">{!! getIcon('code-square', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('blockquote')">{!! getIcon('blockquote-right', 'bi') !!}</button>
    </div>

    <!-- Gruppo 6: Simboli e Tabelle -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success dropdown-toggle"
            data-bs-toggle="dropdown" aria-expanded="false">
            {!! getIcon('emoji-smile', 'bi') !!}
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="insertSymbol('&copy;')">&copy;</a></li>
            <li><a class="dropdown-item" href="#" onclick="insertSymbol('&euro;')">&euro;</a></li>
            <li><a class="dropdown-item" href="#" onclick="insertSymbol('&reg;')">&reg;</a></li>
            <li><a class="dropdown-item" href="#" onclick="insertSymbol('&trade;')">&trade;</a></li>
        </ul>
    </div>
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('table')">{!! getIcon('table', 'bi') !!}</button>
    </div>

    <!-- Gruppo 7: Allineamento -->
    <div class="btn-group me-1 mb-1">
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('left')">{!! getIcon('text-left', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('center')">{!! getIcon('text-center', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('right')">{!! getIcon('justify', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-light btn-outline-success"
            onclick="applyFormat('justify')">{!! getIcon('justify', 'bi') !!}</button>
    </div>

    <div class="btn-group me-1 mb-1 ">
        <button type="button" class="btn btn-sm btn-danger" id="togglePreview">{!! getIcon('eye', 'bi') !!}</button>
        <button type="button" class="btn btn-sm btn-info" id="insertTemplate">{!! getIcon('file-earmark-text', 'bi') !!}</button>

    </div>

</div>

<script>
    document.getElementById('togglePreview').addEventListener('click', function() {
        const textarea = document.getElementById('content');
        const preview = document.getElementById('preview');

        if (textarea.style.display === 'none') {
            // Disattiva la preview e mostra l'editor
            textarea.style.display = 'block';
            preview.style.display = 'none';
        } else {
            // Attiva la preview e nasconde l'editor
            textarea.style.display = 'none';
            preview.style.display = 'block';

            // Recupera il contenuto Markdown
            const content = textarea.value;

            // Invia una richiesta AJAX per convertire il Markdown in HTML
            fetch('/markdown/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Aggiorna l'anteprima con il contenuto HTML
                    preview.innerHTML = data.html; // Assicurati di usare .html e non l'oggetto direttamente
                })
                .catch(error => {
                    console.error('Errore nella preview:', error);
                });
        }
    });

    function applyFormat(formatType) {
        const textarea = document.getElementById('content');
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        const selectedText = textarea.value.substring(startPos, endPos);

        let formattedText = '';

        switch (formatType) {
            case 'bold':
                formattedText = `**${selectedText}**`;
                break;
            case 'italic':
                formattedText = `*${selectedText}*`;
                break;
            case 'strikethrough':
                formattedText = `~~${selectedText}~~`;
                break;
            case 'underline':
                formattedText = `<u>${selectedText}</u>`;
                break;
            case 'superscript': // Superscript
                formattedText = `<sup>${selectedText}</sup>`;
                break;
            case 'subscript': // Subscript
                formattedText = `<sub>${selectedText}</sub>`;
                break;

            case 'h1':
                formattedText = `# ${selectedText}`;
                break;
            case 'h2':
                formattedText = `## ${selectedText}`;
                break;
            case 'h3':
                formattedText = `### ${selectedText}`;
                break;
            case 'h4':
                formattedText = `#### ${selectedText}`;
                break;
            case 'h5':
                formattedText = `##### ${selectedText}`;
                break;
            case 'h6':
                formattedText = `###### ${selectedText}`;
                break;
            case 'ul': // Elenco puntato
                formattedText = `- ${selectedText}`;
                break;
            case 'ol': // Elenco numerato
                formattedText = `1. ${selectedText}`;
                break;
            case 'table': // Creazione di tabella
                formattedText =
                    `| Header 1 | Header 2 | Header 3 |\n| -------- | -------- | -------- |\n| Cell 1   | Cell 2   | Cell 3   |\n| Cell 4   | Cell 5   | Cell 6   |`;
                break;
            case 'left': // Allineamento a sinistra
                formattedText = `<div style="text-align: left;">${selectedText}</div>`;
                break;
            case 'center': // Allineamento centrato
                formattedText = `<div style="text-align: center;">${selectedText}</div>`;
                break;
            case 'right': // Allineamento a destra
                formattedText = `<div style="text-align: right;">${selectedText}</div>`;
                break;
            case 'justify': // Allineamento giustificato
                formattedText = `<div style="text-align: justify;">${selectedText}</div>`;
                break;
            case 'blockquote': // Citazione
                formattedText = `> ${selectedText}`;
                break;
            case 'codeblock': // Blocco di codice multi-linea
                formattedText = `\`\`\`\n${selectedText}\n\`\`\``;
                break;
            default:
                formattedText = selectedText;
        }

        textarea.setRangeText(formattedText, startPos, endPos, 'end');
        textarea.dispatchEvent(new Event('input')); // Aggiorna l'anteprima
    }

    // Funzione per l'inserimento di link
    function applyLink() {
        const textarea = document.getElementById('content'); // Assicurati che questo ID sia corretto
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        const selectedText = textarea.value.substring(startPos, endPos);

        // Chiedi all'utente di inserire l'URL o il titolo della pagina wiki
        const pageTitleOrUrl = prompt("Inserisci il nome della pagina wiki o l'URL:", "");

        if (pageTitleOrUrl) {
            // Se il testo è selezionato, usa il testo selezionato come parte del link, altrimenti usa l'URL stesso
            const formattedText = `[${selectedText || pageTitleOrUrl}](/wiki/${encodeURIComponent(pageTitleOrUrl)})`;

            // Inserisci il link nel testo markdown
            textarea.setRangeText(formattedText, startPos, endPos, 'end');

            // Scatena l'evento di input per aggiornare l'anteprima del contenuto
            textarea.dispatchEvent(new Event('input'));
        }
    }

    // Funzione per l'inserimento di un'immagine
    function applyImage() {
        const textarea = document.getElementById('content');
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;

        const url = prompt("Inserisci l'URL dell'immagine:", "https://");
        const altText = prompt("Inserisci il testo alternativo (alt text):", "Descrizione immagine");

        if (url) {
            const formattedText = `![${altText}](${url})`;
            textarea.setRangeText(formattedText, startPos, endPos, 'end');
            textarea.dispatchEvent(new Event('input')); // Aggiorna l'anteprima
        }
    }

    // Funzione per inserire simboli
    function insertSymbol(symbol) {
        const textarea = document.getElementById('content');
        const startPos = textarea.selectionStart;
        textarea.setRangeText(symbol, startPos, startPos, 'end');
        textarea.dispatchEvent(new Event('input')); // Aggiorna l'anteprima
    }

    function applyIndent(indentType) {
        const textarea = document.getElementById('content');
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        const selectedText = textarea.value.substring(startPos, endPos);

        let formattedText = selectedText;

        if (indentType === 'increase') {
            formattedText = `    ${selectedText}`; // Aumenta indentazione con 4 spazi
        } else if (indentType === 'decrease') {
            formattedText = selectedText.replace(/^ {1,4}/, ''); // Riduci di 4 spazi
        }

        textarea.setRangeText(formattedText, startPos, endPos, 'end');
        textarea.dispatchEvent(new Event('input')); // Aggiorna l'anteprima
    }
</script>
