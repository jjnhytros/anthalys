### **Checklist Progetto SoNet Unificato**

**Fasi Preliminari**
- [x] Progetto Laravel già creato  
- [x] Directory Models: app/Models/Anthaleja/SoNet  
- [x] Directory Controllers: app/Http/Controllers/Anthaleja/SoNet  
- [x] Directory Views: resources/views/anthaleja/sonet  
- [x] Route prefix: 'sonet'  
- [x] Route name: 'sonet.'  

**Risorse condivise da entrambe le sezioni**  
- Layout principale:
  - resources/views/layouts/main
  - resources/views/layouts/partials/alerts.blade.php
  - resources/views/layouts/partials/footer.blade.php
  - resources/views/layouts/partials/head.blade.php
  - resources/views/layouts/partials/navbar_top.blade.php
  - resources/views/layouts/partials/navbar_phone.blade.php  

- File per le route: sonet.php  
- File di configurazione: config/ath.php  
- Tabelle: users, characters, profiles  
  - Relazione 1:1 user <-> character  
  - Relazione 1:1 character <-> profile  

---

### 1. **Struttura del Progetto Unificato**
- [x] Progettare l'architettura del database.
- [x] Implementare autenticazione e gestione degli utenti.
- [x] Definire ruoli e permessi per gli utenti.
- [x] Test unitari e funzionali.
- [ ] Debugging e gestione degli errori.
- [ ] Utilizzare caching per ottimizzare le query e il caricamento dei contenuti.
- [ ] Ottimizzazione lato front-end.

---

### 2. **Funzionalità Principali**
   - **Monetizzazione con Athel**
     - [ ] Implementare il wallet per gestire cash, bank, e bank_account in Athel.
     - [ ] Implementare le transazioni per donazioni tra utenti (P2P) in Athel.
     - [ ] Creare un sistema di abbonamenti e servizi premium pagabili con Athel.
     - [ ] Integrare la gestione degli spazi pubblicitari pagabili con Athel.
     - [ ] Implementare un sistema di commissioni opzionali sulle transazioni.
- [ ] Gestione e moderazione dei contenuti.
- [ ] Post multimediali:
   - [ ] Implementare una gestione avanzata dei media come immagini, video, e documenti.
   - [ ] **[Integrazione con sistemi di raccomandazione personalizzati]**
- [ ] Video brevi e live streaming.
- [ ] Creazione e gestione delle stanze.
- [ ] Integrazione di funzioni social.
- [ ] Calendario eventi.
- [ ] Sistema di pagamento con Athel (cash, bank) per donazioni, tip, vendite, e iscrizioni a gruppi privati.
- [ ] Gestione eventi con Athel.
- [ ] Altre funzionalità avanzate.

---

### 3. **Interfaccia Utente**
- [ ] Ottimizzazione dell'UX per diverse interazioni.
- [ ] **[Implementare supporto multilingua per i contenuti video e interfaccia utente]**
- [ ] Emozioni e reazioni interattive.

---

### 4. **Sicurezza e Privacy**
- [ ] Implementare sistemi di sicurezza per l'autenticazione.
- [ ] Protezione contro XSS, CSRF e SQL Injection.

---

### 5. **Funzionalità di Reporting o Analytics**
- [ ] Statistiche dettagliate su interazioni.
- [ ] Report per utenti professionali.

---

### 6. **Funzionalità future**
- [ ] **[Creazione di un marketplace unificato per servizi aggiuntivi]**
- [ ] Sviluppare ulteriori funzionalità di integrazione con il sistema di Athel.
- [ ] Gestione avanzata delle interazioni sociali in base ai gruppi di appartenenza.

---
