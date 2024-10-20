metti i commenti generali in italiano, per funzione, traduci in italiano eventuali commenti in inglese, traduci in inglese le stringhe italiano, traduci in inglese anche le stringhe di errore, separa pubbliche, private, protette, dove possibile implementa try...catch, se ci sono correzioni da fare, falle ma dimmi quali hai fatto, comprimi dove possibile gli use, commenta le funzioni e use non utilizzate, i commenti a fianco, se possibile spostali sopra la riga di codice, anzichè \Log::error usa dd
traduci in inglese gli errori nei dd

### **Checklist Completa per la Wiki di Anthalys**

1. **Struttura Iniziale**
   - [x] Creare il database per gli articoli della wiki.
   - [x] Definire il modello `Article` per gestire gli articoli della wiki.
   - [x] Creare il controller per gestire la logica di visualizzazione e creazione degli articoli.
   - [x] Creare le rotte per visualizzare e creare articoli.
   - [x] Implementare una homepage con layout simile a Wikipedia.
   - [ ] Aggiungere un **sommario automatico** per articoli lunghi.
   - [ ] Implementare una **pagina delle modifiche recenti**, visibile a tutti gli utenti.
   - [ ] Aggiungere una **pagina delle statistiche dettagliate** (numero di articoli, categorie, utenti attivi, ecc.).

2. **Editor Markdown**
   - [x] Creare un editor markdown da zero utilizzando Prism.js e League/CommonMark.
   - [x] Aggiungere la gestione del codice (Lua, Python, C, ecc.) da eseguire in un template o articolo.
   - [ ] Implementazione di una toolbar con i seguenti pulsanti:
     - [x] Pulsanti per la formattazione del testo (grassetto, corsivo, ecc.).
     - [x] Dropdown per gli header (H1, H2, H3, H4, H5, H6).
     - [x] Elenchi puntati e numerati (toggle).
     - [x] Creare tabelle.
     - [x] Allineamento (sinistra, centro, destra, giustificato).
     - [x] Inserimento di simboli speciali.
       - [ ] Aggiungere altri simboli
       - [ ] Memorizzare i simboli recenti e più usati nel db
     - [x] Pulsanti per: superscript, subscript, 
     - [x] Pulsanti undo/redo, 
     - [x] Pulsanti increase/decrease indent, 
     - [x] Pulsanti copy/cut/paste.
     - [ ] Aggiunta pulsante link (da sistemare).
     - [ ] Aggiunta pulsante immagine (da sistemare).
   - [x] Includere la toolbar con i pulsanti di formattazione all'interno di un partial.
   - [ ] Aggiungere il supporto per **note a piè di pagina** e **citazioni**.
   - [ ] Implementare la **visualizzazione delle fonti** e dei riferimenti in modo strutturato.

3. **Template**
   - [x] Aggiungere la possibilità di creare template utilizzabili negli articoli.
   - [x] Aggiungere la possibilità di inserire e gestire i template nei contenuti tramite tag speciali.
   - [x] Se nel contenuto è presente un tag `{{ template:nometemplate }}`, controllare se esiste nel database:
     - [x] Se non esiste, aggiungere alla lista in rosso (link per creare).
     - [x] Se esiste, aggiungere alla lista in blu (link per visualizzare in modal e modificare).
   - [x] Visualizzare i template utilizzati in una sidebar solo in fase di modifica.
   - [x] Gestire i template esistenti con un modal che permette la modifica inline.
   - [x] Implementare il metodo `store()` e `update()` per i template.
   - [x] Gestire la visualizzazione dei template non esistenti e creare un nuovo template.
   - [ ] Implementare un sistema di **categorizzazione dei template** per facilitare l'organizzazione e la ricerca.

4. **Homepage della Wiki**
   - [x] Creare una homepage per la wiki con layout simile a Wikipedia.
   - [ ] Implementare una barra di ricerca per gli articoli della wiki.
   - [ ] Visualizzare articoli in evidenza e ultimi articoli nella homepage.
   - [ ] Aggiungere una sidebar con categorie e statistiche della wiki.
   - [ ] Aggiungere link utili e sezioni informative nella sidebar.
   - [x] Creare una sezione per i **portali tematici** che raggruppano argomenti correlati.
   - [ ] Implementare un'area per le **voci in vetrina**, con articoli di particolare interesse o qualità.

5. **Gestione degli Articoli**
   - [x] Creare e modificare articoli con l'editor markdown.
   - [x] Visualizzare gli articoli in una struttura ordinata e paginata.
   - [x] Aggiungere un sistema di anteprima per visualizzare il contenuto in formato markdown. (da sistemare)
   - [x] Implementare il parsing dei template negli articoli e gestire quelli mancanti.
   - [ ] Creare una logica di archiviazione per articoli obsoleti o in revisione. (softDelete?)
   - [ ] Aggiungere un sistema di **versionamento degli articoli** con possibilità di vedere differenze tra revisioni.
   - [ ] Implementare un sistema di **disambiguazione** per gestire articoli con nomi simili o identici.
   - [x] Aggiungere un sistema di redirect automatici per articoli rinominati o spostati. (Concluso)
   - [x] Implementare la paginazione degli articoli
   - [ ] Implementare un sistema di tag per gli articoli
   - [ ]  Implementare una struttura gerarchica visuale (albero delle categorie).
   - [ ]  Suggerimento di articoli correlati
   - [ ] Implementare una funzione di **spostamento articoli** tra categorie o sezioni.

6. **Categorie e Navigazione**
   - [ ] Implementare un sistema di **categorie gerarchiche** per organizzare gli articoli per argomento.
   - [ ] Visualizzare le **categorie** in fondo agli articoli con link diretti per navigare tra articoli simili.
   - [ ] Aggiungere un sistema di **sottocategorie** per articoli più specifici.
   - [ ] Creare una pagina che elenca tutte le **categorie principali e secondarie** della wiki.

7. **Esecuzione del Codice**
1. [x] Aggiungere un metodo per eseguire codice Python
2. [x] Aggiungere un metodo per eseguire codice Lua
3. [x] Aggiungere un metodo per eseguire codice C
4. [ ] Verificare la sicurezza del codice inserito (sanitizzazione e controllo input)
5. [ ] Restituire e visualizzare il risultato dell'esecuzione sotto il blocco di codice
6. [ ] Implementare opzioni per limitare o sandboxare l'esecuzione del codice

### **Sviluppo (forse) futuro**
   - [ ] Gestire la creazione e l’aggiornamento di articoli via API.
   - [ ] Aggiungere una funzione per contributori che mostra la lista degli utenti che hanno contribuito maggiormente a ciascun articolo.
