# Projetto Antaleja

## 1. Struttura del Progetto
- [x] Relazione 1:1 utente <-> personaggio (user <-> character).
- [x] **Progettare l'architettura del database** per personaggi, post (sonet), commenti, connessioni, e relazioni tra personaggi.
- [x] **Implementare autenticazione e gestione degli utenti** (registrazione, login, profilo).
- [x] **Definire ruoli e permessi** per gli utenti:
  - [ ]  admin, government, bank, citizen (character)
- [ ] **Ruoli e permessi avanzati**: Aggiungere un'interfaccia per la gestione avanzata dei ruoli direttamente dall'admin panel.
- [x] **Test unitari e funzionali** per verificare il corretto funzionamento delle feature.
- [x] **Debugging e gestione degli errori** nelle interazioni con il database e l’interfaccia utente.
- [x] **Utilizzare caching** per ottimizzare le query e il caricamento dei contenuti.
- [ ] **Ottimizzazione lato front-end** con jQuery e Ajax per caricare i contenuti senza ricaricare la pagina.
- [ ] Protezione contro XSS, CSRF e SQL Injection.

---

## 2. Funzionalità Principali


### 1. Messaggi
- [ ] Ricerca avanzata per email e messaggi.
- [ ] Backup degli allegati caricati (bassa priorità).


#### Migrazioni
1. **Migrations**: `database/migrations/000001_010_messages.php`

```php
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sender_id')->nullable()->constrained('characters')->cascadeOnDelete();
    $table->foreignId('recipient_id')->constrained('characters')->cascadeOnDelete();
    $table->string('subject');
    $table->text('message');
    $table->json('attachments')->nullable();
    $table->string('type')->nullable();
    $table->string('url')->nullable();
    $table->boolean('is_message')->default(true);
    $table->boolean('is_notification')->default(false); // Indica se il messaggio è una notifica
    $table->boolean('is_email')->default(false); // Indica se il messaggio è una email
    $table->boolean('is_archived')->default(false); // Indica se il messaggio è archiviato
    $table->enum('status', ['sent', 'unread', 'read', 'archived'])->default('unread');
    $table->softDeletes();
    $table->timestamps();
});
```

#### Percorsi
1. **Models**: `app/Models/Message.php`
   - Definire il modello `Message` con le relazioni:
     - `belongsTo(Character::class, 'sender_id')`
     - `belongsTo(Character::class, 'recipient_id')`
2. **Controllers**: `app/Http/Controllers/MessageController.php`
   - Implementare i metodi nel `MessageController`:
     - `inbox()`: Visualizzare i messaggi nella casella di posta.
     - `show()`: Visualizzare un messaggio specifico.
     - `store()`: Salvare un nuovo messaggio.
     - `update()`: Aggiornare un messaggio esistente.
     - `destroy()`: Eliminare un messaggio.
3. **Views**:
   - `resources/views/messages/inbox.blade.php`: Visualizzazione della casella di posta per i messaggi (includere opzione per visualizzare solo messaggi archiviati).
   - `resources/views/messages/show.blade.php`: Visualizzazione di un messaggio specifico.
   - `resources/views/messages/archived.blade.php`: Visualizzazione dei messaggi archiviati.

---

### 2. Notifiche

#### Percorsi
1. **Models**: `app/Models/Notification.php`
   - Definire il modello `Notification` con le relazioni:
     - `belongsTo(Character::class, 'recipient_id')`
2. **Controllers**: `app/Http/Controllers/NotificationController.php`
   - Implementare i metodi nel `NotificationController`:
     - `inbox()`: Visualizzare le notifiche nella casella di posta (filtrare anche `is_archived`).
     - `show()`: Visualizzare una notifica specifica.
     - `archive()`: Archiviare una notifica.
     - `unarchive()`: Rimuovere una notifica dall'archivio.
3. **Views**:
   - `resources/views/notifications/inbox.blade.php`: Visualizzare le notifiche nella casella di posta (filtrare anche `is_archived`).
   - `resources/views/notifications/show.blade.php`: Visualizzazione di una notifica specifica.

---

### 3. Email
- [ ] Tag e etichette per l'organizzazione delle email.

#### Percorsi
1. **Models**: `app/Models/Email.php`
   - Definire il modello `Email` con le relazioni:
     - `belongsTo(Character::class, 'sender_id')`
     - `belongsTo(Character::class, 'recipient_id')`
2. **Controllers**: `app/Http/Controllers/EmailController.php`
   - Implementare i metodi nel `EmailController`:
     - `inbox()`: Visualizzare le email nella casella di posta (filtrare anche `is_archived`).
     - `show()`: Visualizzare un'email specifica.
     - `archive()`: Archiviare un'email.
     - `unarchive()`: Rimuovere un'email dall'archivio.
3. **Views**:
   - `resources/views/emails/inbox.blade.php`: Visualizzazione della casella di posta per le notifiche (includere opzione per visualizzare solo notifiche archiviate).
   - `resources/views/emails/show.blade.php`: Visualizzazione di un'email specifica.
   - `resources/views/emails/archived.blade.php`: Visualizzazione delle email archiviate.

---



### a. Creazione di un Sonet (Post)
- [ ] Creazione e gestione dei post.
- [ ] Implementare le view per creare e modificare sonet (post).
- [ ] Moderazione automatica basata su AI: Aggiungere strumenti di moderazione basati su AI per filtrare contenuti offensivi.
- [ ] Implementare algoritmi per creare discussioni su argomenti trend o interessi comuni.
- [ ] Creare una cronologia interattiva che mostri l'evoluzione di eventi o post nel tempo.
- [ ] Dare controllo agli utenti per gestire le priorità della timeline.
- [ ] Integrare API per meteo o eventi sportivi live che influenzano i post.
- **Post Multimediali**
  - [ ] Ottimizzazione per mobile: Assicurarsi che i formati video e immagini siano ottimizzati per i dispositivi mobili.
  - [ ] Storie e brevi video (stile TikTok e Instagram).
  - [ ] Arricchire la gestione dei media: Implementare una gestione avanzata dei media come immagini, video, e documenti.
  - [ ] Preview dinamiche: Visualizzare un'anteprima dinamica dei post.
- **Time Capsule Post**
  - [ ] Aggiungere la possibilità di **visualizzare in anteprima** il contenuto programmato per il futuro.
- **Sonet "Dual Persona" Mode**
  - [ ] Implementare la gestione dei post separati e ottimizzare l'interfaccia per la modalità personale/professionale.
  **Post "Effimero a Lungo Termine"**
  - [ ] Implementare post che si autodistruggono dopo mesi o anni, magari con **notifiche di avviso** prima della scadenza.
- [ ] Permettere di creare post in più lingue (bassa priorità).

   **Opzioni di visibilità**:
  - [ ] Progettare il layout e l'interfaccia utente per la homepage (timeline).
    - [ ] **Ottimizzazione per mobile**: Assicurare che la timeline sia ottimizzata per dispositivi mobili.
    - [ ] **Gestione di timeline lunghe**: Implementare la gestione delle timeline con un gran numero di post in modo efficiente.
  - [ ] Solo connessi
  - [ ] Solo amici
  - [ ] Pubblico
  - [ ] Visibile online ma non in timeline
  - [ ] Visibile solo agli utenti menzionati



### c. Segnalazione, Silenziamento e Blocco
- [ ] Silenziare un utente senza bloccarlo.
- [ ] Blocco permanente degli utenti o delle stanze.



### f. Notifiche
- **Notifiche Avanzate**
- [ ] **Notifiche personalizzate**: Implementare la possibilità di personalizzare le notifiche per ciascuna interazione (notifiche push su mobile o web).
- [ ] Preferenze di notifiche.
- [ ] Storico delle notifiche.

### g. Opportunità Lavorative (Fiverr + LinkedIn)
- **Profili Professionali**
- [ ] Implementare la gestione dei profili personali (foto profilo, biografia, ecc.).
- [ ] Profilo pubblico/privato con informazioni personali (bio, foto, link).
- [ ] Gestione avanzata del profilo:
  - [ ] Integrazione con *curriculum professionale*.
  - [ ] *Portfolio professionale* per condividere progetti e successi.
  - [ ] *Pubblicazione di offerte di lavoro*: I personaggi possono pubblicare offerte di lavoro con dettagli sui progetti e competenze richieste.
  - [ ] *Sistema di tracking delle candidature*: Aggiungere la possibilità di tracciare lo stato delle candidature e risposte.
  - [ ] *Richieste di lavoro (Fiverr)*: I personaggi possono inviare richieste specifiche per servizi o collaborazioni.
  - [ ] *Freelance vs Assunzioni a lungo termine*: Distinzione tra lavori a progetto (freelance) e offerte di assunzioni a lungo termine.
  - [ ] Tracciare il coinvolgimento in cause sociali e creare badge o medaglie basati sull'impatto.
- **Marketplace e Annunci**
- [ ] Acquisto e vendita di prodotti o servizi.
- [ ] Gestione degli annunci per la promozione di attività.
- [ ] Pagamento degli spazi pubblicitari al personaggio con ID 2.

- **Ottimizzazione della User Experience (UX)**:
  - [ ] Migliorare l'interfaccia per conversazioni, spazi pubblicitari o curriculum.
  - [ ] Aggiungere notifiche o avvisi personalizzati per transazioni o nuovi messaggi.
- [ ] Opportunità lavorative e marketplace.

### h. Calendario Eventi
  - [ ] Creare la piattaforma di gestione eventi.

### l. Video brevi e live streaming (sezione esistente)
- [ ] Integrare strumenti come **WebRTC** per il live streaming e gestione delle reazioni in tempo reale.





#### Migrazioni
1. **Migrations**: `database/migrations/000010_01_sonetPosts.php`

```php
Schema::create('sonet_posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->text('content');
    $table->string('media')->nullable();
    $table->enum('visibility', ['public', 'follower', 'private', 'mentioned'])->default('public');
    $table->timestamps();
    $table->timestamp('publish_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('warning_sent')->default(false);
});
```

#### Percorsi
1. **Models**: `app/Models/Anthaleja/SoNet/SonetPost.php`
   - Definire il modello `SonetPost` con le relazioni:
     - `belongsTo(Character::class)`
     - `hasMany(SonetComment::class)`
     - `morphMany(Luminum::class, 'luminable')`
2. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/SonetPostController.php`
   - Implementare i metodi principali nel `SonetPostController`:
     - `index()`: Visualizzare i post nella timeline.
     - `create()`: Mostrare il form per creare un nuovo post.
     - `store()`: Salvare un nuovo post nel database.
     - `edit()`: Modificare un post esistente.
     - `update()`: Aggiornare i dati di un post.
     - `destroy()`: Eliminare un post.
3. **Views**
  - `resources/views/anthaleja/sonet/posts/create.blade.php`
  - `resources/views/anthaleja/sonet/posts/timeline.blade.php`
   - Creare le viste per i post:
     - `posts/create.blade.php`: Form per la creazione di un nuovo post.
     - `posts/timeline.blade.php`: Visualizzazione della timeline dei post.
4. **Rotte**
   - Definire le rotte corrispondenti per le azioni CRUD nei post.

---

### b. Commenti
- [ ] Commenti e interazioni.
- [ ] Progettare il sistema di notifiche per interazioni come menzioni, commenti, connessioni, ecc.
- [ ] Aggiungere badge per riconoscimenti speciali o achievements.


#### Migrazioni
1. **Migrations**: `database/migrations/000010_010_sonetComments.php`

```php
Schema::create('sonet_comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sonet_post_id')->constrained('sonet_posts')->cascadeOnDelete();
    $table->foreignId('parent_id')->nullable()->constrained('sonet_comments')->nullOnDelete();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->string('visibility')->default('public');
    $table->text('content');
    $table->timestamps();
});
```

#### Percorsi
1. **Models**: `app/Models/Anthaleja/SoNet/SonetComment.php`
   - Definire il modello `SonetComment` con le relazioni:
     - `belongsTo(SonetPost::class)`
     - `belongsTo(Character::class)`
     - `hasMany(SonetComment::class, 'parent_id')`
     - `morphMany(Luminum::class, 'luminable')`
2. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/CommentController.php`
   - Implementare i metodi nel `CommentController`:
     - `store()`: Salvare un nuovo commento.
     - `edit()`: Mostrare il form per modificare un commento.
     - `update()`: Aggiornare il contenuto di un commento.
     - `destroy()`: Eliminare un commento.
3. **Views**
  - `resources/views/anthaleja/sonet/partials/comment_list.blade.php`
   - Creare la vista per visualizzare l'elenco dei commenti.
4. **Rotte**
   - Aggiungere le rotte per la gestione dei commenti.

---

### c. Connessioni
- [ ] Sistema connessioni.
- [ ] **Suggerimenti automatici di persone da connettere**: Implementare un algoritmo per suggerire utenti da connettere basato sugli interessi e interazioni.


#### Migrazioni
1. **Migrations**: `database/migrations/000010_015_sonetConnections.php`

```php
Schema::create('sonet_connections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sender_id')->constrained('characters')->cascadeOnDelete();
    $table->foreignId('recipient_id')->constrained('characters')->cascadeOnDelete();
    $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/SonetConnection.php`
   - Definire il modello `SonetConnection` con le relazioni:
     - `belongsTo(Character::class, 'sender_id')`
     - `belongsTo(Character::class, 'recipient_id')`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/FollowerController.php`
   - Implementare i metodi nel `FollowerController`:
     - `index()`: Visualizzare la lista delle connessioni.
     - `create()`: Mostrare il form per inviare una richiesta di connessione.
     - `store()`: Salvare una nuova connessione.
     - `destroy()`: Annullare o eliminare una connessione.
4. **Views**
  - `resources/views/anthaleja/sonet/connections/index.blade.php`
  - `resources/views/anthaleja/sonet/connections/create.blade.php`
  - `resources/views/anthaleja/sonet/connections/show.blade.php`
   - Creare le viste per la gestione delle connessioni.
5. **Rotte**
   - Definire le rotte per la gestione delle connessioni.

---

### d. Recensioni
- [ ] Sistema di reputazione e feedback.
  - [ ] Recensioni e valutazioni su profili professionali o servizi.
  - [ ] Visualizzazione del punteggio in stelle (fino a 6).

#### Migrazioni
1. **Migrations**: `database/migrations/000010_020_sonetReviews.php`

```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->foreignId('reviewer_id')->constrained('characters')->cascadeOnDelete();
    $table->integer('rating')->unsigned()->default(0);
    $table->text('review');
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/Review.php`
   - Definire il modello `Review` con le relazioni:
     - `belongsTo(Character::class, 'character_id')`
     - `belongsTo(Character::class, 'reviewer_id')`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/ReviewController.php`
   - Implementare i metodi nel `ReviewController`:
     - `index()`: Visualizzare tutte le recensioni.
     - `create()`: Mostrare il form per creare una nuova recensione.
     - `store()`: Salvare una nuova recensione.
     - `destroy()`: Eliminare una recensione.
4. **Views**
  - `resources/views/anthaleja/sonet/reviews/create.blade.php`
  - `resources/views/anthaleja/sonet/reviews/index.blade.php`
   - Creare le viste per la gestione delle recensioni.
5. **Rotte**
   - Definire le rotte per la gestione delle recensioni.

---

### e. Segnalazioni
- [ ] Segnalazione: silenziamento e blocco personaggi/utenti, contenuti.

#### Migrazioni
1. **Migrations**: `database/migrations/000010_025_sonetReports.php`

```php
Schema::create('sonet_reports', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->morphs('reportable');
    $table->text('reason');
    $table->string('status')->default('pending');
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/SonetReport.php`
   - Definire il modello `SonetReport` con le relazioni:
     - `morphTo('reportable')`
     - `belongsTo(Character::class)`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/SonetReportController.php`
   - Implementare i metodi nel `SonetReportController`:
     - `index()`: Visualizzare tutte le segnalazioni.
     - `store()`: Salvare una nuova segnalazione.
     - `update()`: Aggiornare lo stato di una segnalazione.
     - `destroy()`: Eliminare una segnalazione.
4. **Views**
  - `resources/views/anthaleja/sonet/reports/index.blade.php`
   - Creare la vista per visualizzare l'elenco delle segnalazioni.
5. **Rotte**
   - Aggiungere le rotte per la gestione delle segnalazioni.

---

### f. Mention (Menzioni)

#### Migrazioni
1. **Migrations**: `database/migrations/000010_030_sonetMentions.php`

```php
Schema::create('mentions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sonet_post_id')->nullable()->constrained('sonet_posts')->cascadeOnDelete();
    $table->foreignId('comment_id')->nullable()->constrained('sonet_comments')->cascadeOnDelete();
    $table->foreignId('mentioned_id')->constrained('characters')->cascadeOnDelete();
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/Mention.php`
   - Definire il modello `Mention` senza relazioni particolari.
3. **Integrazione**
   - Gestire le menzioni all'interno dei controller `SonetPostController` e `CommentController` per rilevare le menzioni nei contenuti.
4. **Views**
   - Non sono necessarie viste dedicate per le menzioni.
5. **Rotte**
   - Non sono richieste rotte dedicate per la gestione delle menzioni.

---

### g. Lumina (Reazioni)
- [ ] Implementare **reazioni rapide e condivisioni** integrate con altre piattaforme.
- [ ] Animazioni live per mostrare le reazioni in tempo reale sui post.

#### Migrazioni
1. **Migrations**: `database/migrations/000010_035_sonetLumina.php`

```php
Schema::create('lumina', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->morphs('luminable'); // Supporta Sonet, commenti e profili
    $table->timestamps();

    // Indice per evitare duplicati
    $table->unique(['character_id', 'luminable_type', 'luminable_id']);
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/Luminum.php`
   - Definire il modello `Luminum` con la relazione:
     - `morphTo('luminable')`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/LuminumController.php`
   - Implementare i metodi nel `LuminumController`:
     - `store()`: Salvare una nuova reazione.
     - `destroy()`: Rimuovere una reazione.
4. **Views**
  - `resources/views/anthaleja/sonet/partials/lumina.blade.php`
   - Creare la vista per visualizzare le reazioni.
5. **Rotte**
   - Aggiungere le rotte per la gestione delle Lumina.

---

### h. Abbonamenti (Subscriptions)

#### Migrazioni
1. **Migrations**: `database/migrations/000010_040_sonetSubscriptions.php`

```php
Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 24, 2);
    $table->enum('duration', ['1 month', '3 months', '6 months', '9 months', '18 months']);
    $table->date('next_payment_date');  // Data della prossima transazione
    $table->boolean('active')->default(true);
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/Subscription.php`
   - Definire il modello `Subscription` con la relazione:
     - `belongsTo(Character::class)`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/SubscriptionController.php`
   - Implementare i metodi nel `SubscriptionController`:
     - `index()`: Visualizzare gli abbonamenti attivi.
     - `store()`: Creare un nuovo abbonamento.
     - `update()`: Aggiornare un abbonamento esistente.
     - `destroy()`: Annullare un abbonamento.
4. **Views**
  - `resources/views/anthaleja/sonet/subscriptions/index.blade.php`
   - Creare la vista per visualizzare e gestire gli abbonamenti.
5. **Rotte**
   - Aggiungere le rotte per la gestione degli abbonamenti.

---

### i. Annunci (Ads)

#### Migrazioni
1. **Migrations**: `database/migrations/000010_045_sonetAds.php`

```php
Schema::create('ads', function (Blueprint $table) {
    $table->id();
    $table->foreignId('character_id')->constrained()->cascadeOnDelete();
    $table->string('content');
    $table->decimal('cost', 24, 2);
    $table->enum('type', ['paid', 'ppv', 'ppc', 'free'])->default('paid'); // Tipologia di annuncio
    $table->unsignedBigInteger('views')->default(0); // Visualizzazioni per PPV
    $table->unsignedBigInteger('clicks')->default(0); // Click per PPC
    $table->date('start_date');
    $table->date('end_date');
    $table->boolean('active')->default(true); // Stato attivo o meno
    $table->timestamps();
});
```

#### Percorsi
2. **Models**: `app/Models/Anthaleja/SoNet/Ad.php`
   - Definire il modello `Ad` con la relazione:
     - `belongsTo(Character::class)`
3. **Controller**: `app/Http/Controllers/Anthaleja/SoNet/AdController.php`
   - Implementare i metodi nel `AdController`:
     - `index()`: Visualizzare tutti gli annunci.
     - `create()`: Mostrare il form per creare un annuncio.
     - `store()`: Salvare un nuovo annuncio.
     - `edit()`: Modificare un annuncio esistente.
     - `update()`: Aggiornare un annuncio.
     - `destroy()`: Eliminare un annuncio.
4. **Views**
  - `resources/views/anthaleja/sonet/ads/index.blade.php`
   - Creare la vista per visualizzare e gestire gli annunci.
5. **Rotte**
   - Aggiungere le rotte per la gestione degli annunci.

---

### j. Stanze (Sonet Rooms), Membri (Sonet Room Members), Messaggi (Sonet Room Messages)
- [ ] Creare gruppi privati chiusi, gestiti dai membri.
- [ ] Aggiungere funzioni avanzate come messaggi che si autodistruggono e canali tematici.

#### Migrazioni
1. **Migrations**: `database/migrations/000010_050_sonetRooms.php`

```php
Schema::create('sonet_rooms', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('type', ['public', 'private', 'invite-only'])->default('public');
    $table->foreignId('created_by')->constrained('characters')->cascadeOnDelete();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

Schema::create('sonet_room_members', function (Blueprint $table) {
    $table->id();
    $table->foreignId('room_id')->constrained('sonet_rooms')->cascadeOnDelete();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->enum('role', ['admin', 'moderator', 'member'])->default('member');
    $table->timestamp('joined_at')->useCurrent();
});

Schema::create('sonet_room_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('room_id')->constrained('sonet_rooms')->cascadeOnDelete();
    $table->foreignId('character_id')->constrained('characters')->cascadeOnDelete();
    $table->foreignId('reply_to')->index()->nullable()->constrained('sonet_room_messages')->nullOnDelete();
    $table->text('message');
    $table->enum('type', ['text', 'image', 'video', 'audio'])->default('text');
    $table->string('media_url')->nullable();
    $table->boolean('edited')->default(false);
    $table->json('attachments')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
```

#### Percorsi
2. **Models**:
   - `app/Models/Anthaleja/SoNet/SonetRoom.php`
   - `app/Models/Anthaleja/SoNet/SonetRoomMember.php`
   - `app/Models/Anthaleja/SoNet/SonetRoomMessage.php`
3. **Controllers**:
   - `app/Http/Controllers/Anthaleja/SoNet/SonetRoomController.php`
   - `app/Http/Controllers/Anthaleja/SoNet/SonetRoomMessageController.php`
4. **Views**:
  - `resources/views/anthaleja/sonet/rooms/index.blade.php`
  - `resources/views/anthaleja/sonet/rooms/create.blade.php`
  - `resources/views/anthaleja/sonet/rooms/messages/index.blade.php`
  - `resources/views/anthaleja/sonet/rooms/messages/edit.blade.php`
5. **Rotte**
   - Aggiungere le rotte per la gestione delle stanze, membri e messaggi.

#### Modelli
- **SonetRoom**
  - Relazioni:
    - `hasMany(SonetRoomMember::class)`
    - `hasMany(SonetRoomMessage::class)`
- **SonetRoomMember**
  - Relazioni:
    - `belongsTo(SonetRoom::class)`
    - `belongsTo(Character::class)`
- **SonetRoomMessage**
  - Relazioni:
    - `belongsTo(SonetRoom::class)`
    - `belongsTo(Character::class)`
    - `belongsTo(SonetRoomMessage::class, 'reply_to')`
    - `hasMany(SonetRoomMessage::class, 'reply_to')`

---

## 2. Funzionalità Principali









### u. Collaborazione Creativa in Tempo Reale
- [ ] Potrebbe essere interessante sviluppare un **editor collaborativo** per la co-creazione di contenuti.






---

## 3. Interfaccia Utente

---

## 4. Sicurezza e Privacy
- [ ] **Integrazione di 2FA** (Two-Factor Authentication): Aggiungere un secondo livello di sicurezza nell'autenticazione.
- [ ] Implementare sistemi di sicurezza per l'autenticazione.

---

### Checklist di Sviluppo:
[ ] 3. **Sviluppo della mappa della città** con funzionalità di navigazione e divisione in quartieri
    - Gestione Quartieri e Costruzioni
      - [ ] Modifica della Struttura della Tabella dei Quartieri: Aggiungeremo informazioni per gestire lo sviluppo dei quartieri, come il livello di sviluppo e i limiti di costruzione.
      - [ ] Sistema di Costruzioni Dinamiche: Creeremo un sistema che permetta la costruzione di edifici nei vari quartieri, influenzato dall'AI.
      - [ ] Interazione con il Sistema AI: L'AI determinerà quali costruzioni sono necessarie in un determinato quartiere, tenendo conto di vari fattori (popolazione, bisogni, economia).
      - [ ] Evoluzione dei Quartieri: Ogni quartiere può evolversi nel tempo, migliorando o peggiorando in base agli eventi, alle costruzioni e alle interazioni sociali.
      - [ ] Integrare eventi casuali e sociali che influenzano i quartieri.
      - [ ] Implementare logiche più avanzate per simulare l'economia e le interazioni tra i personaggi.
        - [ ] Fluttuazioni Economiche: Il sistema economico può simulare l'inflazione, i salari, e la crescita economica basata sugli eventi nei quartieri. Ad esempio, un quartiere con un "economic boom" potrebbe vedere un aumento delle attività commerciali, con i personaggi che guadagnano di più, mentre una "crisi economica" può ridurre i salari e aumentare il tasso di disoccupazione.
        - [ ] Interazioni di Mercato: Potremmo introdurre un sistema di mercato dinamico, in cui i personaggi possono vendere e acquistare beni immobili, partecipare a investimenti, e influenzare il mercato locale. Le fluttuazioni dei prezzi immobiliari potrebbero essere collegate allo sviluppo del quartiere o agli eventi globali.
        - [ ] Interazioni Sociali Avanzate: I personaggi possono interagire tra di loro attraverso scambi commerciali, alleanze e conflitti. Queste interazioni potrebbero influenzare non solo le relazioni tra i personaggi, ma anche lo stato economico o la reputazione di un intero quartiere. Eventi come "negoziazioni" o "partnership" possono influenzare la crescita del quartiere.
        - [ ] Sviluppo di Risorse e Imprese: I personaggi possono sviluppare imprese che portano profitti o risorse, come negozi, fabbriche, o aziende tecnologiche. Le risorse prodotte influenzano direttamente l'economia del quartiere, con più risorse che incrementano la ricchezza di una determinata area.
        - [ ] IA per le Decisioni Economiche: L'AI può prendere decisioni basate sui dati economici generati dal sistema per determinare quali quartieri prosperano e quali hanno bisogno di interventi (come aiuti economici o nuove costruzioni).
      - [ ] Migliorare il sistema di costruzione per reagire meglio alle condizioni del quartiere.
    - [ ] Aggiungere Tipologie di Settori: considerare di aggiungere settori speciali come zone verdi, parchi, o infrastrutture pubbliche (scuole, stazioni).
    - [ ] Evoluzione dei Settori: implementare una logica in cui settori industriali degradano i settori residenziali vicini, o settori commerciali migliorano quelli residenziali, dinamizzando la crescita della città.

[ ] 4. **Implementazione degli NPC nei test del sistema**
[ ] 5. **Sistema economico avanzato**, inclusi acquisti immobiliari e mercato
[ ] 6. **Gestione delle reputazioni e dei comportamenti**
[ ] 7. **Integrazione con il sistema scolastico e lavorativo**

