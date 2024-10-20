## Progetto SoNet

### **1. Struttura del Progetto**
   - [x] Relazione 1:1 utente<->personaggio (user<->character)
   - [x] **Progettare l'architettura del database** per personaggi, post (sonet), commenti, follower, e relazioni tra personaggi.
   - [x] **Implementare autenticazione e gestione degli utenti** (registrazione, login, profilo).
   - [x] **Definire ruoli e permessi** per gli utenti (es. admin, moderatore, utente standard).
   - [ ] **Ruoli e permessi avanzati**: Aggiungere un'interfaccia per la gestione avanzata dei ruoli direttamente dall'admin panel, per una gestione più granulare.
   - [x] **Test unitari e funzionali** per verificare il corretto funzionamento delle feature.
   - [x] **Debugging e gestione degli errori** nelle interazioni con il database e l’interfaccia utente.
   - [x] **Utilizzare caching** per ottimizzare le query e il caricamento dei contenuti.
   - [7] **Ottimizzazione lato front-end** con jQuery e Ajax per caricare i contenuti senza ricaricare la pagina.
   - [8] **Sistema di creazione di istanze** (siti separati per utenti diversi).
   - [9] **Integrazione tra istanze** per interazioni (federazione delle istanze simile a Mastodon).

### **2. Funzionalità Principali**
   - **a. Creazione di un sonet (post)**:
     - [x] Opzioni di visibilità:
       - [x] Solo follower.
       - [x] Pubblico.
       - [x] Visibile online ma non in timeline.
       - [x] Visibile solo agli utenti menzionati.
     - [ ] **Supporto per post multi-lingua**: Permettere di creare post in più lingue.
     - [ ] **Arricchire la gestione dei media**: Implementare una gestione avanzata dei media come immagini, video, e documenti.
     - [ ] **Preview dinamiche**: Visualizzare un'anteprima dinamica dei post.

   - **b. Visualizzazione timeline**:
     - [ ] **Ottimizzazione per mobile**: Assicurare che la timeline sia ottimizzata per la visualizzazione su dispositivi mobili.
     - [ ] **Gestione di timeline lunghe**: Implementare la gestione delle timeline con un gran numero di post in modo efficiente.

   - **c. Segnalazione, silenziamento e blocco**:
     - [x] Segnalare utenti o contenuti.
     - [x] Silenziare un utente senza bloccarlo.
     - [x] Blocco permanente degli utenti o delle stanze.

   - **d. Follower**:
     - [ ] **Suggerimenti automatici di persone da seguire**: Implementare un algoritmo per suggerire utenti da seguire basato sugli interessi e interazioni.

   - **e. Commenti**:
     - [ ] **Sistema di badge e riconoscimenti**: Aggiungere badge per riconoscimenti speciali o achievements.

   - **f. Sistema di reputazione e feedback**:
      - [x] Recensioni e valutazioni su profili professionali o servizi.
      - [x] Visualizzazione del punteggio in stelle (fino a 6).

    - **g. Notifiche avanzate**:
      - [ ] **Notifiche personalizzate**: Implementare la possibilità di personalizzare le notifiche per ciascuna interazione (notifiche push su mobile o web).
      - [ ] Preferenze di notifiche.
      - [ ] Storico delle notifiche.

    - **h. Marketplace e annunci**:
      - [ ] Acquisto e vendita di prodotti o servizi.
      - [ ] Gestione degli annunci per la promozione di attività.
      - [ ] Pagamento degli spazi pubblicitari al personaggio con ID 2.

    - **i. Opportunità lavorative (Fiverr + LinkedIn)**:
      - [x] **Pubblicazione di offerte di lavoro**: I personaggi possono pubblicare offerte di lavoro con dettagli sui progetti e competenze richieste.
      - [ ] **Sistema di tracking delle candidature**: Aggiungere la possibilità di tracciare lo stato delle candidature e risposte.
      - [ ] **Richieste di lavoro (Fiverr)**: I personaggi possono inviare richieste specifiche per servizi o collaborazioni.
      - [ ] **Freelance vs Assunzioni a lungo termine**: Distinzione tra lavori a progetto (freelance) e offerte di assunzioni a lungo termine.

    - **j. Gestione e moderazione dei contenuti**:
      - [ ] **Moderazione automatica basata su AI**: Aggiungere strumenti di moderazione basati su AI per filtrare contenuti offensivi.
      - [ ] **Gestione dei media** (immagini, video, audio).
      - [ ] Creare un sistema di report per contenuti inappropriati.

    - **k. Post multimediali**:
      - [ ] **Ottimizzazione per mobile**: Assicurati che i formati video e immagini siano ottimizzati per i dispositivi mobili.
      - [ ] **Storie e brevi video** (stile TikTok e Instagram).

    - **l. Video brevi e live streaming**:
      - [ ] Integrare strumenti come **WebRTC** per il live streaming e gestione delle reazioni in tempo reale.

    - **m. Creazione e gestione delle stanze** (gruppi o stanze di conversazione):
      - [ ] **Messaggi effimeri e canali tematici**: Aggiungere funzioni avanzate come messaggi che si autodistruggono e canali tematici.

    - **n. Integrazione di funzioni social**:
      - [ ] Implementare **reazioni rapide e condivisioni** integrate con altre piattaforme.

    - **o. Calendario eventi**:
      - [ ] Potrebbe essere utile un'integrazione con **Google Calendar** o altre piattaforme di gestione eventi.

    - **p. Time Capsule Post**: 
      - [ ] Aggiungere la possibilità di **visualizzare in anteprima** il contenuto programmato per il futuro.

    - **q. Sonet "Dual Persona" Mode**: 
      - [ ] Implementare la gestione dei post separati e ottimizzare l'interfaccia per la modalità personale/professionale.

    - **r. Rendimento Sociale (Social Impact Tracker)**: 
      - [ ] Tracciare il coinvolgimento in cause sociali e creare **badge o medaglie** basati sull'impatto.

    - **s. Interazioni Basate su Frequenza di Contatti**: 
      - [ ] Dare controllo agli utenti per gestire le priorità della timeline.

    - **t. Post "Dinamico" Basato su Dati del Mondo Reale**: 
      - [ ] Integrare API per **meteo o eventi sportivi live** che influenzano i post.

    - **u. Collaborazione Creativa in Tempo Reale**: 
      - [ ] Potrebbe essere interessante sviluppare un **editor collaborativo** per la co-creazione di contenuti.

    - **v. Cronologia Interattiva**: 
      - [ ] Creare una cronologia interattiva che mostri **l'evoluzione di eventi** o post nel tempo.

    - **w. Post "Effimero a Lungo Termine"**: 
      - [ ] Implementare post che si autodistruggono dopo mesi o anni, magari con **notifiche di avviso** prima della scadenza.

    - **x. Spazio Social Privato per Amici Ristretti (Private Circle)**: 
      - [ ] Creare gruppi privati chiusi, **gestiti dai membri**.

    - **y. Generatore di Discussioni Casuali**: 
      - [ ] Implementare algoritmi per creare discussioni su argomenti trend o **interessi comuni**.

    - **z. Feedback Visivo in Tempo Reale sui Sonet**: 
      - [ ] **Animazioni live** per mostrare le reazioni in tempo reale sui post.

### **3. Interfaccia Utente**
   - [1] **Progettare il layout e l'interfaccia utente** per la homepage (timeline).
   - [2] **Implementare le view per creare e modificare sonet (post)**.
   - [3] **Progettare il sistema di notifiche** per interazioni come menzioni, commenti, follower, ecc.
   - [4] **Implementare la gestione dei profili personali** (foto profilo, biografia, ecc.).
   - [5] **Profilo pubblico/privato** con informazioni personali (bio, foto, link).
   - [6] **Gestione avanzata del profilo**:
     - [ ] Integrazione con **curriculum professionale**.
     - [ ] **Portfolio professionale** per condividere progetti e successi.
   - [7] **Ottimizzazione della User Experience (UX)**:
     - [ ] Migliorare l'interfaccia per conversazioni, spazi pubblicitari o curriculum.
     - [ ] Aggiungere notifiche o avvisi personalizzati per transazioni o nuovi messaggi.

---

### **Da implementare (forse)**
- **Integrazione di 2FA** (Two-Factor Authentication): Aggiungere un secondo livello di sicurezza nell'autenticazione.
