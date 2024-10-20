### 1. Miglioramento del sistema di eventi casuali
- [x] Implementare eventi casuali dinamici basati su variabili come salute, denaro e posizione del personaggio.
- [ ] Integrare eventi casuali e sociali che influenzano i quartieri.
- [ ] Fluttuazioni Economiche: Simulare inflazione, salari e crescita economica basata sugli eventi nei quartieri.
- [ ] Interazioni di Mercato: Introdurre un sistema di mercato dinamico dove i personaggi possono vendere e acquistare beni immobili e influenzare i prezzi in base allo sviluppo del quartiere.
- [ ] Interazioni Sociali Avanzate: Introdurre scambi commerciali, alleanze, conflitti che influenzano non solo le relazioni, ma anche l'economia del quartiere.
- [ ] Sviluppo di Risorse e Imprese: Implementare la capacità di creare imprese che influenzano l'economia del quartiere.
- [ ] IA per le Decisioni Economiche: L'AI gestirà l'andamento economico del quartiere, determinando quando un'area prospera o necessita di aiuti economici o interventi infrastrutturali.

### 2. Gestione Risorse e Inventario
- [ ] Implementare la gestione del consumo di risorse (cibo, acqua, carburante, strumenti).
- [ ] Aggiungere un sistema di acquisto e vendita di risorse.
- [ ] Implementare un sistema di crafting per creare nuovi oggetti.

### 3. Economia e Commercio
#### a. Investimenti
- [ ] Tipologie di investimento: basso, medio e alto rischio.
- [ ] Struttura del database per gli investimenti.
- [ ] Calcolo dei rendimenti e simulazione di rischi.
- [ ] **Notifiche sugli investimenti**: aggiornamenti su successo o fallimento.

#### b. Sistema Bancario e Prestiti
- [ ] Gestione dei prestiti e mutui.
- [ ] Calcolo degli interessi e gestione delle rate.

### 4. Gestione delle risorse dei personaggi (denaro, salute, reputazione)
- [ ] Creare un sistema di monitoraggio delle risorse personali per ogni personaggio.
- [ ] Implementare logiche di variazione delle risorse basate sugli eventi.

### 5. Sviluppo della mappa della città con funzionalità di navigazione e divisione in quartieri
- [x] Mappa 36x36 suddivisa in settori (quartieri).
- [x] Implementare la gestione dei settori commerciali, residenziali, industriali.
- [x] Aggiungere edifici di servizi pubblici come stazioni di polizia e vigili del fuoco.
- [ ] Inserire ospedali nelle zone più densamente popolate (regole in sospeso).

### 6. Gestione quartieri e Costruzioni
- [x] Implementare un sistema di costruzioni nei quartieri (residenziali, commerciali, industriali).
- [ ] Sistema di Costruzioni Dinamiche: L'AI determinerà quali costruzioni sono necessarie in un determinato quartiere in base a fattori come popolazione, bisogni, economia.
- [ ] Modifica della Struttura della Tabella dei Quartieri: Aggiungere informazioni come il livello di sviluppo e i limiti di costruzione.
- [ ] Implementare edifici che cambiano dinamicamente con gli eventi sociali ed economici.
- [ ] Migliorare il sistema di costruzione per reagire meglio alle condizioni del quartiere.
- [ ] Aggiungere Tipologie di Settori: Considerare l'aggiunta di zone verdi, parchi, infrastrutture pubbliche (scuole, stazioni).

### 7. Evoluzione dei Quartieri
- [x] Ogni quartiere può evolversi nel tempo, migliorando o peggiorando in base agli eventi, alle costruzioni e alle interazioni sociali.
- [ ] Evoluzione dei Settori: Settori industriali possono degradare quelli residenziali vicini, mentre i settori commerciali li migliorano.

### 8. Interazione sociale e meccaniche relazionali
- [x] Sistema di relazioni tra i personaggi (spouse, parent, sibling, ecc.).
- [ ] Implementare il supporto AI per migliorare le interazioni sociali e modificare le relazioni in base agli eventi.

### 9. Gestione delle strutture abitative/commerciali
- [ ] Ogni sezione della mappa deve includere strutture abitative/commerciali.
- [ ] Ogni abitazione può ospitare un numero specifico di personaggi.

### 10. Suddivisione delle celle in settori più piccoli (vie, piazze, ecc.)
- [ ] Implementare la suddivisione di ogni cella in più settori per maggiore granularità.
- [ ] Aggiungere vie, piazze e piccole strutture all'interno di ogni cella della mappa.

### 11. Logiche AI
- [ ] L'AI influenzerà le decisioni di costruzione nei quartieri.
- [ ] Gestione delle risorse dei personaggi in base alle loro attività e all'ambiente circostante.
- [ ] L'AI determinerà la necessità di nuovi edifici e l'impatto delle interazioni tra i personaggi.

### 12. Progetto SoNet

Ecco una versione riorganizzata della checklist SoNet, con i punti integrati nelle sezioni e sottosezioni corrette:

#### **1. Sistema di Messaggistica**
- **Messaggi, Email e Notifiche**
  - [ ] Unificare messaggi, email e notifiche in una sola tabella.
  - [ ] Implementare una **ricerca avanzata** per email e messaggi.
  - [ ] Backup degli allegati caricati (bassa priorità).
  - [ ] Tag ed etichette per l'organizzazione delle email.

#### **2. Sonet (Post)**
- **Creazione e Gestione dei Sonet (Post)**
  - [ ] Creazione e gestione dei Sonet (Post).
  - [ ] Implementare le view per **creare e modificare i Sonet (Post)**.
  - [ ] Moderazione automatica basata su AI: Filtrare contenuti offensivi.
  - [ ] Creare algoritmi per discussioni su argomenti trend o interessi comuni.
  - [ ] **Cronologia interattiva**: Visualizzare l'evoluzione di eventi o post nel tempo.
  - [ ] **Controllo degli utenti** per gestire le priorità della timeline.
  - [ ] **Integrazione API** per meteo o eventi sportivi live.
  - [ ] **Post Multimediali**
    - Ottimizzazione per mobile.
    - Implementare **storie** e brevi video (stile TikTok e Instagram).
    - Gestione avanzata dei media (immagini, video, documenti).
    - **Preview dinamiche** dei post.
    - Post "effimero a lungo termine" che si autodistruggono dopo mesi o anni.
    - Post multilingue (bassa priorità).

#### **3. Opzioni di Visibilità**
  - [ ] Progettare il layout della homepage (timeline).
  - [ ] Ottimizzazione per mobile.
  - [ ] Gestione di timeline lunghe.
  - **Visibilità dei Post**:
    - Solo connessi.
    - Solo amici.
    - Pubblico.
    - Visibile online ma non in timeline.
    - Visibile solo agli utenti menzionati.

#### **4. Connessioni**
  - [ ] Sistema connessioni.
  - [ ] **Suggerimenti automatici di persone da connettere** basato sugli interessi.

#### **5. Commenti e Interazioni**
  - [ ] Sistema commenti e interazioni.
  - [ ] Notifiche per interazioni come menzioni, commenti, connessioni.
  - [ ] Badge per riconoscimenti o achievements.
  - **Lumina (Reazioni)**
    - [ ] Reazioni rapide e condivisioni.
    - [ ] Animazioni live per reazioni in tempo reale sui post.

#### **6. Recensioni e Feedback**
  - [ ] Sistema di reputazione e feedback.
  - [ ] Recensioni e valutazioni (fino a 6 stelle).

#### **7. Segnalazioni, Silenziamento e Blocco**
  - [ ] Silenziare un utente senza bloccarlo.
  - [ ] Blocco permanente degli utenti o delle stanze.
  - [ ] Segnalazione di utenti, contenuti, messaggi.

#### **8. Notifiche Avanzate**
  - [ ] Notifiche personalizzate per ciascuna interazione (push su mobile o web).
  - [ ] Preferenze di notifiche.
  - [ ] Storico delle notifiche.

#### **9. Opportunità Lavorative (Fiverr + LinkedIn)**
  - **Profili Professionali**
    - [ ] Gestione dei profili personali (foto, bio, ecc.).
    - [ ] Profilo pubblico/privato con informazioni personali.
    - [ ] Integrazione con curriculum professionale.
    - [ ] Portfolio professionale.
    - [ ] **Pubblicazione di offerte di lavoro**.
    - [ ] Sistema di tracking delle candidature.
    - [ ] **Richieste di lavoro** (Fiverr).
    - [ ] Distinzione tra freelance e assunzioni a lungo termine.
    - [ ] Badge o medaglie per l’impatto sociale.
  
  - **Marketplace e Annunci**
    - [ ] Acquisto e vendita di prodotti o servizi.
    - [ ] Gestione degli annunci per promozioni.
    - [ ] Pagamento degli spazi pubblicitari al personaggio con ID 2.

#### **10. UX Ottimizzata**
  - [ ] Migliorare l’interfaccia per conversazioni, spazi pubblicitari e curriculum.
  - [ ] Aggiungere notifiche personalizzate per transazioni o nuovi messaggi.

#### **11. Abbonamenti**
  - [ ] Implementare un sistema di abbonamenti.

#### **12. Annunci (Ads)**
  - [ ] Inserimento e gestione degli annunci pubblicitari.

#### **13. Sonet Rooms (Stanze)**
  - [ ] Creare gruppi privati chiusi gestiti dai membri.
  - [ ] Funzioni avanzate come messaggi che si autodistruggono e canali tematici.

#### **14. Collaborazione Creativa in Tempo Reale**
  - [ ] Implementare un editor collaborativo per la creazione di contenuti in tempo reale.

#### **15. Funzionalità Speciali**
  - **Time Capsule Post**
    - [ ] Aggiungere la possibilità di **visualizzare in anteprima** il contenuto programmato per il futuro.
  - **Sonet "Dual Persona" Mode**
    - [ ] Implementare la gestione dei post separati per modalità personale/professionale.



#### 16. **Personalizzazione basata su AI**
   - **Timeline e contenuti personalizzati**: L'AI può analizzare le interazioni degli utenti (like, commenti, visualizzazioni) e creare una timeline personalizzata, mettendo in evidenza i contenuti più rilevanti per ciascun utente.
   - **Raccomandazioni di connessioni e contenuti**: Implementare un sistema di raccomandazioni AI che suggerisca nuove connessioni, gruppi e contenuti basati sugli interessi e sui comportamenti dell'utente.

#### 17. **Moderazione e Controllo AI**
   - **Filtraggio automatico dei contenuti**: Utilizzare algoritmi di AI per rilevare e moderare in tempo reale contenuti inappropriati, offensivi o che violano le linee guida della piattaforma.
   - **Monitoraggio dinamico delle conversazioni**: L'AI può intervenire in chat o stanze per moderare in modo intelligente i contenuti, promuovendo una comunicazione rispettosa e inclusiva.
   
#### 18. **Analisi comportamentale e relazionale**
   - **Analisi delle dinamiche sociali**: L'AI può analizzare i comportamenti degli utenti e le loro interazioni per identificare le dinamiche sociali e suggerire miglioramenti o opportunità di connessione e interazione.
   - **Prevenzione del burnout digitale**: L'AI può monitorare i livelli di attività degli utenti e suggerire delle pause o un bilanciamento nell'uso della piattaforma per evitare il burnout.

#### 19. **Automazione delle interazioni**
   - **Chatbot avanzati**: Creare chatbot che possano automatizzare le risposte a domande comuni, suggerendo post correlati o eventi rilevanti.
   - **Sistemi di assistenza personalizzati**: L'AI potrebbe fornire suggerimenti personalizzati durante la scrittura di post, migliorando la grammatica, il tono e la chiarezza.

#### 20. **Analisi dei dati per migliorare le funzionalità**
   - **Analisi predittiva del comportamento utente**: Prevedere il tipo di contenuti che un utente potrebbe preferire e ottimizzare l'esperienza in base alle sue interazioni passate.
   - **Tendenze globali e regionali**: L'AI potrebbe analizzare tendenze globali e locali e adattare i contenuti o suggerire nuovi argomenti di discussione per l'utente.

#### 21. **Gestione del tempo e produttività**
   - **Time management assistito da AI**: Un sistema di AI che aiuti gli utenti a gestire meglio il loro tempo, offrendo suggerimenti su come ottimizzare l’uso della piattaforma.
   - **Promemoria intelligenti**: Promemoria basati sulle interazioni passate, come seguire eventi o rivedere post rilevanti per le proprie aree di interesse.

#### 22. **AI e Creatività**
   - **Generazione automatica di contenuti**: Utilizzare AI per generare bozze di post, video o immagini per aiutare gli utenti a creare contenuti originali in modo più rapido.
   - **Supporto alla creazione visiva e grafica**: L'AI potrebbe suggerire miglioramenti grafici ai contenuti multimediali degli utenti, come l'editing delle immagini o la creazione automatizzata di template per i post.
