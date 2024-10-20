# TODO

1. **Aggiungere le librerie ATHDateTime**:
   - implementare le classi:
     - ATHCalendar:
       - daysInMonth(int $month, int $year): int
       - fromAdn(int $adn): array
       - info(int $calendar = -1): array
       - toAdn(int $month, int $day, int $year): int
     - 🗹 ATHConstants:
     - ATHDateInterval:
       - getTotalSeconds(): int
       - addYears(int $years): self
       - addMonths(int $months): self
       - addDays(int $days): self
       - addHours(int $hours): self
       - addMinutes(int $minutes): self
       - addSeconds(int $seconds): self
       - getYears(): int
       - getMonths(): int
       - getDays(): int
       - getHours(): int
       - getMinutes(): int
       - getSeconds(): int
       - 🗹 format(string $format): string
       - 🗹 createFromDateString(string $time): ATHDateInterval|false
       - convertToSeconds(array $components): int
       - (?) setIntervalFromSeconds(int $totalSeconds): void
     - ATHDatePeriod:
       - getDates(): array
       - getTotalDays(): int
       - getTotalMonths(): float
       - getTotalYears(): float
       - contains(ATHDateTime $date): bool
     - ATHDateTime:
       - make($secs)
       - add(ATHDateInterval $interval): ATHDateTime
       - createFromFormat(string $format, string $datetime, ?ATHDateTimeZone $timezone = null): ATHDateTime|false
       - createFromImmutable(ATHDateTimeImmutable $object): static
       - createFromInterface(ATHDateTimeInterface $object): static
       - getLastErrors(): array
       - setError(string $message): void
       - modify(string $modifier): self
       - __set_state(array $array): ATHDateTime
       - setDate(int $year, int $month, int $day): ATHDateTime
       - setISODate(int $year, int $week, int $day = 1): ATHDateTime
       - setTime(int $hour, int $minute, int $second = 0): self
       - setTimestamp(int $timestamp): ATHDateTime
       - setTimezone(ATHDateTimeZone $timezone): ATHDateTime
       - sub(ATHDateInterval $interval): ATHDateTime
       - setDateTime(array $parsedDate, ?ATHDateTimeZone $timezone)
       - getTimestamp(): int
       - format(string $format): string
       - parseDateFromFormat(string $format, string $dateString): bool
       - getOffset(): int
       - diff(ATHDateTimeInterface $targetObject, bool $absolute = false): ATHDateInterval
       - getTimezone(): ATHDateTimeZone|false
       - getMonthName(int $monthNumber): string
       - getDayName(): string
       - getAllMonthNames(): array
       - getAllDayNames(): array
       - formatDate(): string
       - getAnthalDayNumber()
       - getFormattedDateTime()
       - getHMS()
       - calculateDayOfWeek(): int
       - normalizeDateTime(): void
       - updateTimestamp(): void
       - toTotalSeconds(): int
       - fromTotalSeconds(int $totalSeconds): void
       - buildRegexFromFormat(string $format): string
       - mapMatchesToComponents(string $format, array $matches): array
       - setDateComponents(array $components): bool
       - resetLastErrors(): void
       - setWarning(string $message): void
       - calculateTimestamp(): int
       - getAYear()
       - getAMonth()
       - getADay()
       - getAHour()
       - getAMinute()
       - getASecond()
       - __wakeup(): void
     - ATHDateTimeImmutable:
       - stesse funzioni di ATHDateTime
     - 🗹 ATHDateTimeInterface,
2. **Ottimizzazione CRUD per Timezones e Province**:
    - Implementazione completa della logica di ordinamento personalizzato alfabetico (per esempio, con lettere speciali come `ĉ` e `ĝ`).
    - Verifica e testing dell'integrazione del sistema di soft delete per Province e Timezones.
  
3. **Conferma prima delle modifiche per Mesi e Giorni**:
    - Implementare un messaggio di conferma migliore per l'aggiornamento o eliminazione di giorni e mesi in sola lettura.

4. **Ottimizzazione layout e UI/UX**:
    - Potenzialmente aggiungere una paginazione alle viste Province e Timezones se il numero di record è grande.

5. **Sistema di Ordinamento Avanzato**:
    - Rifinire e correggere il sorting alfabetico personalizzato in base all'alfabeto richiesto per le province, utilizzando la logica personalizzata.

6. **Testing**:
    - Aggiungere test unitari e funzionali per Timezones, Province, Mesi e Giorni.
    - Verificare l'accuratezza della generazione degli **offset** per Timezones e l'aggiornamento di **abbreviation**.

7. **Refactoring**:
    - Ottimizzare i controller e i modelli per garantire che la logica complessa venga gestita correttamente, mantenendo una buona separazione delle responsabilità.

8. **Homepage**:
    - Aggiungere ulteriori dettagli e link sulla homepage per gestire Mesi e Giorni, rendendo più accessibile l'interazione con queste entità.
  
9.  **Controllo di sicurezza**:
    - Implementare misure di sicurezza come il controllo dei permessi per le operazioni CRUD, in particolare per Timezones e Province.

10. **Impostare sulla mappa le coordinate di Anthalys**

11. **Implementare le coordinate reali della mappa per le regioni**
    
12. **Sistema di risorse e inventario avanzato**
- [ ] Implementare la **gestione del consumo di risorse** (cibo, acqua, carburante, strumenti).
- [ ] Aggiungere un sistema di **acquisto e vendita di risorse** nei mercati regionali.
- [ ] Implementare un **sistema di crafting** che permetta di combinare risorse per creare nuovi oggetti o strumenti.

13. **Sistema di eventi dinamici avanzati**
- [ ] Creare **nuovi tipi di eventi dinamici**, come incontri con NPC, pericoli ambientali (animali, banditi).
- [ ] Implementare eventi che causano **effetti a lungo termine** (malattie, ferite, tesori).

14. **Sistema di reputazione e relazioni**
- [ ] Creare un **sistema di reputazione con le regioni o fazioni**, in cui le azioni del personaggio influenzano la sua reputazione.
- [ ] Implementare un **sistema di relazioni con NPC**, dove il personaggio può sviluppare rapporti che portano a missioni o ricompense speciali.

15. **Ottimizzazione del sistema di viaggio**
- [ ] Aggiungere la possibilità di intraprendere **viaggi automatici**, con meno input del giocatore ma con maggior rischio di eventi imprevisti.
- [ ] Implementare **sistemi di trasporto diversificati** (cavalli, navi, veicoli) con costi e velocità diversi.

16. **Integrazione del ciclo giorno/notte e cambiamenti stagionali**
- [ ] Aggiungere un **ciclo giorno/notte** che influenzi il viaggio (ad esempio, viaggiare di notte è più pericoloso).
- [ ] Implementare i **cambiamenti stagionali**, dove le condizioni meteo, risorse disponibili e difficoltà di viaggio variano in base alla stagione (inverno più difficile, estate più facile).

17. **Sistema economico e di commercio**
- [ ] Aggiungere **mercati regionali** che permettano l'acquisto e la vendita di risorse e oggetti.
- [ ] Creare un sistema di **fluttuazione dei prezzi** basato su domanda e offerta, o eventi globali (guerre, disastri naturali).

18. **Sistema di combattimento o sfide**
- [ ] Implementare **sfide di combattimento** contro minacce come banditi o animali selvaggi.
- [ ] Aggiungere sfide fisiche o mentali che richiedano abilità specifiche del personaggio (attraversare un fiume in piena, risolvere enigmi).

19. **Sviluppo del personaggio**
- [ ] Creare un **sistema di crescita del personaggio** basato sull'esperienza, dove il personaggio migliora abilità come forza, agilità, intelligenza, ecc.
- [ ] Implementare un sistema di **missioni e obiettivi** che il personaggio può completare per ottenere ricompense ed esperienza.

20. **Interfaccia utente e miglioramenti UX**
- [ ] Migliorare l'interfaccia del viaggio, aggiungendo **dettagli come la mappa del percorso**, incontri, e stato del personaggio in tempo reale.
- [ ] Aggiungere un sistema di **notifiche migliorate** che informi il giocatore in modo chiaro sui cambiamenti, eventi e risultati importanti.

21. **Debugging e ottimizzazione del codice**
- [ ] Ottimizzare il codice per migliorare le **prestazioni del sistema di viaggio**, soprattutto se il numero di personaggi o regioni cresce.
- [ ] Implementare un sistema di **logging e monitoraggio** per tracciare gli eventi importanti e facilitare il debugging.

Per strutturare un **browser game** con Laravel che abbia caratteristiche simili a **The Sims**, dovresti considerare una progettazione modulare che copra diversi aspetti chiave del gioco come la gestione dei personaggi, il mondo di gioco, l'interazione con gli oggetti e il sistema economico. Ecco un possibile schema della struttura:

### 1. **Architettura generale**
- **Laravel Backend**: Gestirà la logica di gioco, le interazioni degli utenti, il salvataggio e il caricamento delle partite.
- **Frontend (Vue.js / React / Livewire)**: Per un’esperienza interattiva e in tempo reale, è possibile usare framework front-end moderni come Vue.js o React integrati con Laravel, o Livewire per un'interazione più dinamica senza scrivere molto JavaScript.
- **WebSocket / Pusher**: Per aggiornamenti in tempo reale e interazioni multiplayer, puoi usare Laravel Echo con Pusher o Socket.io.
- **Database**: MySQL o PostgreSQL per salvare lo stato del gioco, i profili dei giocatori, le statistiche e la progressione.

### 2. **Struttura delle tabelle di database**

#### **Users (Giocatori)**
- `id`: Identificativo unico del giocatore.
- `username`: Nome utente.
- `email`: Email dell'utente.
- `password`: Password crittografata.
- `last_login`: Data dell'ultimo login.
- `avatar`: Immagine del personaggio.

#### **Characters (Personaggi)**
- `id`: Identificativo unico del personaggio.
- `user_id`: Relazione con la tabella degli utenti.
- `name`: Nome del personaggio.
- `happiness`: Percentuale di felicità.
- `energy`: Livello di energia.
- `hunger`: Stato di fame.
- `cleanliness`: Livello di pulizia.
- `money`: Denaro disponibile.

#### **Properties (Case / Appartamenti)**
- `id`: Identificativo unico della proprietà.
- `user_id`: Relazione con il proprietario.
- `address`: Indirizzo virtuale.
- `size`: Dimensioni della proprietà.
- `rooms`: Numero di stanze.
- `value`: Valore economico.
- `bills_due`: Data per il pagamento delle bollette.

#### **Objects (Oggetti / Mobili)**
- `id`: Identificativo unico dell'oggetto.
- `character_id`: Relazione con il proprietario (può essere condiviso con altri personaggi).
- `property_id`: Relazione con la casa in cui si trova l'oggetto.
- `name`: Nome dell'oggetto.
- `type`: Tipo (mobili, elettrodomestici, ecc.).
- `condition`: Stato di usura.
- `interaction_effects`: Effetti che ha sul personaggio (es. +5 felicità).

#### **Actions (Azioni dei Personaggi)**
- `id`: Identificativo unico dell'azione.
- `character_id`: Personaggio che compie l'azione.
- `object_id`: Oggetto con cui il personaggio interagisce.
- `action_type`: Tipo di azione (es. mangiare, dormire, lavorare).
- `duration`: Durata in secondi o minuti.
- `energy_change`: Modifica del livello di energia.
- `happiness_change`: Modifica della felicità.

#### **Economy (Sistema Economico)**
- `id`: Identificativo unico dell'attività economica.
- `user_id`: Relazione con l'utente coinvolto.
- `transaction_type`: Tipo di transazione (acquisto, vendita, lavoro).
- `amount`: Importo.
- `description`: Descrizione della transazione (es. “Pagamento bollette”).

### 3. **Struttura del Gioco**

#### **Personaggi**
- Ogni giocatore ha uno o più personaggi che hanno bisogni (fame, sonno, igiene, socialità).
- Azioni giornaliere come mangiare, lavorare, pulire la casa, ecc.
- Un sistema di crescita e gestione delle risorse (tempo, denaro, energia).

#### **Case e Oggetti**
- I giocatori possono acquistare o affittare case.
- Gli oggetti sono acquistabili e hanno diversi effetti sui personaggi (migliorare l'umore, l'energia, ecc.).
- Le case hanno fatture periodiche (bollette) da pagare per mantenere il comfort.

#### **Economia e Lavoro**
- Ogni personaggio ha un lavoro che genera reddito giornaliero o settimanale.
- Possibilità di investire in miglioramenti per aumentare la qualità della vita o risparmiare denaro.
- Un sistema di skills per migliorare la carriera o la produttività in diverse attività.

### 4. **Interazione con il Mondo di Gioco**
- **Mappe**: Ogni proprietà o luogo pubblico è rappresentato da una mappa che contiene posizioni e oggetti con cui interagire.
- **Azioni e Oggetti**: Gli utenti possono interagire con gli oggetti del mondo virtuale tramite comandi e menù a tendina.
- **Simulazione di Tempo**: Il gioco avrà un sistema di giorno/notte e cicli settimanali (es. lavoro durante la settimana, riposo nel weekend).

### 5. **Gameplay**

#### **Gestione delle Attività Quotidiane**
- I giocatori pianificano le attività quotidiane dei personaggi come mangiare, dormire, socializzare, e lavorare.
- Ogni azione consuma o restituisce energia e influisce sul benessere del personaggio.

#### **Progressione**
- I personaggi possono crescere nel gioco migliorando abilità specifiche, aumentando le loro finanze, e migliorando le loro proprietà.

#### **Eventi Casuali**
- Eventi imprevisti possono accadere (es. un guasto nella casa, un invito a un evento sociale), e i giocatori devono gestirli per mantenere la felicità del personaggio.

### 6. **Multiplayer**
- Implementa un sistema di vicini (altri giocatori possono essere vicini di casa o incontrarsi nei luoghi pubblici).
- Possibilità di cooperare o competere con altri utenti per risorse limitate o attività sociali (feste, eventi di quartiere).

### 7. **Tecnologie Addizionali**
- **Integrazione con APIs**: Puoi usare API per creare eventi o aggiungere contenuti dinamici.
- **Notifiche in tempo reale**: Usare WebSocket per notifiche di eventi (es. un altro giocatore ha visitato la tua casa).
- **Salvataggio automatico**: Il gioco deve salvare automaticamente lo stato del personaggio e del mondo per garantire che i progressi non vadano persi.

### 8. **Sicurezza**
- **Autenticazione**: Usa il sistema di autenticazione di Laravel (se necessario) per gestire gli account dei giocatori.
- **Middleware**: Proteggere l’accesso alle risorse sensibili tramite middleware e ruoli.
- **Protezione dai bot**: Implementa meccanismi come CAPTCHA o rate-limiting per proteggere il gioco da interazioni automatiche.

#### 9. **Account e profili (LinkedIn/Facebook)**
   - [ ] **Profilo pubblico/privato** con informazioni personali (bio, foto, link).
   - [ ] **Account verificati** per autenticare utenti famosi o professionisti.
   - [ ] **Connessioni professionali** come in LinkedIn.
   - [ ] **Amici o follower**, con opzioni di gestione della privacy.
   - [ ] **Gestione avanzata del profilo**:
     - **Integrazione con curriculum professionale**.
     - **Portfolio professionale** per condividere progetti e successi.

#### 10. **Post multimediali (Instagram/Facebook/Twitter)**
   - [ ] **Foto e video** con la possibilità di commentare e mettere like.
   - [ ] **Post di testo** simili a Twitter, con hashtag e menzioni.
   - [ ] **Stories e brevi video** (stile TikTok e Instagram).
   - [ ] **Reazioni** multiple ai post (come Facebook) o solo like (come Instagram).

#### 11. **Feed personalizzati (Twitter/Facebook)**
   - [ ] **Algoritmo** per mostrare i post in base alle preferenze e alle interazioni.
   - [ ] **Filtri** per vedere solo post recenti, popolari o per categorie (es. lavoro, intrattenimento).

#### 12. **Video brevi e live streaming (TikTok/Instagram)**
   - [ ] **Video brevi** con audio in background (stile TikTok).
   - [ ] **Live streaming** con commenti in tempo reale e possibilità di inviare reazioni durante la trasmissione (in sospeso).

#### 13. **Gruppi e community (Facebook/LinkedIn)**
   - [ ] **Gruppi di discussione** tematici o professionali (come LinkedIn e Facebook).
   - [ ] **Creazione di eventi** e gestione RSVP.
   - [ ] **Gruppi privati con moderazione**:
     - Creazione di gruppi privati o aziendali con moderatori.
     - Contenuti esclusivi condivisi solo con i membri del gruppo.

#### 14. **Sistema di raccomandazioni e tagging (Instagram/Twitter/LinkedIn)**
   - [ ] **Hashtag** e **mentions** per facilitare la ricerca e il collegamento dei contenuti.
   - [ ] **Raccomandazioni automatiche** di persone da seguire, post interessanti o opportunità lavorative.

#### 15. **Sistema di messaggistica privata (Facebook/Instagram)**
   - [ ] **Chat private** con messaggi diretti, foto e video.
   - [ ] **Messaggi vocali** e possibilità di inviare video brevi via chat.

#### 16. **Opportunità lavorative (LinkedIn)**
   - [ ] **Sezione lavoro** con offerte di lavoro e possibilità di pubblicare curriculum.
   - [ ] **Raccomandazioni** per lavori basati sulle competenze dell’utente.

#### 17. **N/D**
   - [Sezione omessa per libera scelta]

#### 18. **Analytics per utenti (TikTok/Instagram/LinkedIn)**
   - [ ] **Statistiche dettagliate** sulle visualizzazioni, like, commenti e interazioni dei post.
   - [ ] **Report** per utenti professionali che mostrano l’impatto delle loro attività social.
   - [ ] **Strumenti di analisi avanzata per contenuti**:
     - **Analisi dei contenuti in tempo reale**.
     - **Benchmarking** delle performance dei contenuti rispetto ad altri utenti.

#### 19. **Sicurezza e privacy**
   - [ ] **Opzioni di privacy avanzate** per gestire la visibilità dei contenuti (pubblici, privati, amici).
   - [ ] **Segnalazione e moderazione** dei contenuti inappropriati con riferimenti ai personaggi.

#### 20. **Notifiche**
   - [ ] **Notifiche avanzate**:
     - **Notifiche personalizzate** per ogni tipo di interazione (nuovi follower, commenti, reazioni).
     - **Preferenze di notifiche** per gestire quali notifiche ricevere.
     - **Storico delle notifiche** per vedere notifiche passate.

#### 21. **Marketplace e annunci**
   - [ ] **Acquisto e vendita**: possibilità per i personaggi di pubblicare annunci di prodotti o servizi.
   - [ ] **Gestione degli annunci** per la vendita di oggetti, servizi, o promozione della propria attività.

#### 22. **Sistema di reputazione e feedback**
   - [ ] **Rating e recensioni**: possibilità di valutare e lasciare recensioni su profili professionali o servizi.
   - [ ] **Badge e riconoscimenti**: assegnare badge basati sull'attività e performance degli utenti.

#### 23. **Calendario eventi**
   - [ ] **Calendario interattivo**: gli utenti possono vedere eventi in arrivo e segnare la loro partecipazione.
   - [ ] **Integrazione con eventi professionali**: collegare eventi a opportunità lavorative o networking.

#### 24. **Gamification**
   - [ ] **Punti e classifiche**: sistema di punti basato sulle interazioni con classifiche periodiche.
   - [ ] **Sfide tra utenti**: competizioni tra utenti basate su punteggi o altre metriche.
   - [ ] **Monitoraggio dettagliato dei progressi della sfida**: tracciamento in tempo reale di post, commenti, reazioni o punti guadagnati.
   - [ ] **Sfide di gruppo**: possibilità di creare sfide di gruppo e aggiungere più partecipanti.
   - [ ] **Classifiche per le sfide**: classifiche specifiche per le sfide basate sui risultati delle competizioni passate.
   - [ ] **Notifiche avanzate e promemoria per le sfide**: notifiche periodiche e promemoria per le sfide attive o in scadenza.
   - [ ] **Premi e incentivi per le sfide**: sistema di premi per i vincitori delle sfide (badge, punti bonus).
   - [ ] **Storia delle sfide**: cronologia delle sfide passate, con risultati finali e premi guadagnati.

#### **25. Progetto TV on-demand**
   - [ ] **Setup iniziale**: Installazione e configurazione di Laravel e TMDB.
   - [ ] **Integrazione con TMDB**: Richiesta e memorizzazione dei dati da TMDB.
   - [ ] **Struttura del database**: Creazione tabelle Movies, TVShows, Episodes, Cast, Crew.
   - [ ] **Funzionalità di ricerca**: Interfaccia di ricerca con TMDB e memorizzazione.
   - [ ] **Visualizzazione dettagliata**: Pagine per film, serie TV, episodi.
   - [ ] **Ottimizzazioni e caching**: Riduzione richieste a TMDB tramite caching.
   - [ ] **Test e Deploy**: Test delle funzionalità e deploy su server di produzione.

26. [ ] **Storage**: AWS S3 o un altro servizio per archiviare immagini, video e file multimediali.
27. [ ] [ ] **Sistema di autenticazione**: registro/login tramite email o social login.
28. [ ] **Creazione e gestione del profilo utente**.
29. [ ] **Post multimediali**: possibilità di caricare foto, video, testo e interazioni.
30. [ ] **Feed principale**: visualizzazione dei post in base alle connessioni o ai follower.
31. [ ] il personaggio interagisce, non l'utente, e i personaggi con attività possono acquistare spazi pubblicitari con pagamento al personaggio con ID 2. Lo storage sarà in locale per il momento.

32. [ ] **Ottimizzazione della User Experience (UX)**:
   [ ] - Migliorare l'interfaccia per la gestione delle conversazioni, spazi pubblicitari o curriculum.
   [ ] - Aggiungere notifiche o avvisi personalizzati per transazioni, nuovi messaggi o offerte di lavoro.

33. [ ] **Funzionalità di Reporting o Analytics**:
   [ ] - Aggiungere un sistema per monitorare le transazioni, acquisti di spazi pubblicitari o statistiche di utilizzo (ad esempio, quante volte uno spazio pubblicitario è stato visto).

34. [ ] **Gestione e moderazione dei contenuti**:
   [ ] - Implementare funzionalità di moderazione per controllare contenuti inappropriati negli annunci o nei gruppi.

35. [ ] **Integrazione di funzioni social**:
   [ ] - Implementare funzionalità di reazione e commenti sugli spazi pubblicitari, offerte di lavoro o profili dei personaggi.

36. **Sistema di Notifiche Avanzato**:

    a. Notifiche dinamiche nella phone-app:
    - [ ] Mostrare un'icona con il conteggio delle notifiche non lette nella barra delle app del telefono che abbiamo implementato.
    - [ ] Notifiche aggiornate automaticamente.

    b. Personalizzazione dell'interfaccia per le notifiche:
    - [ ] Migliorare l'aspetto visivo delle notifiche, ad esempio con l'utilizzo di badge, pillole di notifiche, o modal per mostrare i dettagli.
  
    c. Ulteriori miglioramenti del sistema di messaggistica:
    - [ ] Aggiungere una visualizzazione dettagliata dei messaggi di sistema, delle email e delle notifiche.
  
    d. Integrazione con altre funzionalità del sistema (marketplace o messaggeria interna):
    - [ ] Collega il sistema di notifiche con altre parti del gioco come il marketplace o il sistema di messaggistica interna.

37. [ ] **Ricerca Avanzata nelle Email e nei Messaggi**: Potremmo migliorare la funzione di ricerca per consentire la ricerca per testo, mittente, data e persino per contenuti degli allegati.

38. [ ] **Tag e Etichette per le Email**: Potremmo implementare la funzionalità di tag o etichette, consentendo agli utenti di organizzare le loro email in categorie personalizzate.

39. [x] **Integrazione con il Sistema di Marketplace o Transazioni**:
    - [ ] Potremmo integrare il sistema di messaggistica con altre funzionalità del gioco, come il marketplace, notificando gli utenti di aggiornamenti su offerte o transazioni.
    - [ ] Collegare le operazioni del marketplace con il sistema bancario, tracciando i pagamenti e aggiornando i bilanci dei personaggi.

40. **Integrazione del Marketplace con le Transazioni Bancarie**:
   - [ ] Collegare le operazioni del marketplace con il sistema bancario, tracciando i pagamenti e aggiornando i bilanci dei personaggi.
   
41. **Sistema di Gestione Risorse (Inventario e Crafting)**:
   - [ ] Implementare un sistema per la gestione delle risorse e il crafting, incluso l'inventario dei personaggi e la creazione di oggetti.

42. **Piano di Sviluppo per la Chat (Ispirata a Discord e Telegram)**:
    A. Architettura di Base

    - [x] Creazione delle stanze di chat (simile ai server di Discord o gruppi di Telegram).
    - [x] Creazione di chat private e di gruppo (con uno schema per messaggi diretti tra utenti e gruppi).
    - [x] Aggiunta del supporto per multi-utente in una stanza.
    
    B. Sistema di Messaggistica

    - [x] Invio di messaggi di testo, incluse le reazioni e i messaggi in evidenza.
    - [x] Implementazione di messaggi multimediali (immagini, audio, video).
    - [x] Modifica ed eliminazione dei messaggi (incluso undo tramite soft delete).

    
    C. Notifiche e Aggiornamenti

    - [x] Notifiche in tempo reale per nuovi messaggi (simile a Telegram) senza Laravel Echo e Pusher.
    - [x] Sistema di menzioni (@utente) e risposte ai messaggi.
    - [x] Icona dinamica nella navbar (phone) con il conteggio dei messaggi non letti.

    D. Interfaccia Utente

    - [x] Design ispirato a Discord, con una barra laterale per i canali e le chat private.
    - [ ] Multimedia: Aggiungere il supporto per immagini, audio e video nei messaggi.
    - [ ] Sistema di status degli utenti (online/offline).
    - [ ] Integrazione di emote e reazioni come su Discord/Telegram.

    E. Funzionalità Avanzate (più avanti, come espansione)

      - [ ] Implementazione di canali vocali e videochiamate.
      - [ ] Aggiunta di chat bot o automazioni per gestire funzioni di base.


43. **Gestione del Personaggio**:
   - [ ] Lavorare sulle caratteristiche del personaggio, come le statistiche sociali o altre interazioni legate al gameplay.

Fammi sapere quale punto preferisci o se hai altre priorità in mente!
44. [ ] **Backup degli Allegati**: Creare un sistema di backup automatico per gli allegati caricati, garantendo che i file vengano salvati in più luoghi per evitare perdite.

[ ] Aggiungiamo un sistema di risparmio automatico?
[ ] Aggiungiamo un sistema di sanzioni?
[ ] Come calcoliamo le rate dei prestiti?
[ ] Come gestiamo le spese ricorrenti?
[ ] Come possiamo evitare transazioni duplicate?
[ ] Come possiamo implementare le penalità?
[x] Come vogliamo visualizzare i prestiti?
[ ] Continuiamo con la gestione dei prestiti?
[ ] Dovremmo aggiungere il saldo giornaliero?
[ ] Implementiamo la logica per i limiti?
[ ] Impostiamo la gestione degli interessi bancari?
[ ] Mostriamo la commissione in modo diverso?
[ ] Possiamo aggiungere un sistema di investimenti?
[ ] Vogliamo passare al sistema di risparmio?
[x] Calcoliamo anche il saldo medio annuale?
[x] Calcoliamo anche la giacenza media annuale?
[x] Possiamo calcolare il saldo medio mensile?
[x] Saldo di Emergenza
[x] Aggiungiamo la possibilità di estendere i prestiti?
[ ] Come integriamo le notifiche automatiche?
[x] Integriamo il pagamento anticipato del prestito?
[x] Come gestiamo i pagamenti in ritardo?
[ ] Aggiungiamo nuove funzionalità alla banca?
[ ] Possiamo aggiungere la gestione dei mutui?
[x] Integriamo il calcolo degli interessi passivi?
[ ] Aggiungiamo garanzie per i prestiti?
[ ] Come gestiamo il mancato pagamento totale?
[ ] Come gestiamo le transazioni ricorrenti?
