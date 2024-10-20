CREATE TABLE IF NOT EXISTS "wiki_articles" ("id" integer primary key autoincrement not null, "character_id" integer not null, "category_id" integer, "title" varchar not null, "slug" varchar not null, "content" text not null, "html_content" text not null, "code_language" varchar, "render_infobox" boolean null, "published_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("character_id") references "characters"("id") on delete cascade, foreign key("category_id") references "wiki_categories"("id") on delete set null);
INSERT INTO wiki_articles VALUES(1,1,NULL,'Guida all’utilizzo della Wiki di Anthalys','guida-allutilizzo-della-wiki-di-anthalys',replace('# Benvenuto nella Wiki di Anthalys! Questa guida ti aiuterà a comprendere come creare, modificare e gestire articoli, infobox, categorie e template all''interno della wiki. Segui i passaggi qui sotto per utilizzare al meglio le funzionalità della piattaforma.\n\n## Indice\n\n1. [Creazione di un nuovo articolo](#creazione-di-un-nuovo-articolo)\n2. [Utilizzo degli Infobox](#utilizzo-degli-infobox)\n3. [Gestione delle categorie](#gestione-delle-categorie)\n4. [Modifica di un articolo esistente](#modifica-di-un-articolo-esistente)\n5. [Template personalizzati](#template-personalizzati)\n6. [Link interni alla Wiki](#link-interni-alla-wiki)\n7. [Gestione dei Redirect](#gestione-dei-redirect)\n8. [Gestione degli articoli mancanti](#gestione-degli-articoli-mancanti)\n\n---\n\n## 1. Creazione di un nuovo articolo\n\nPer creare un nuovo articolo all''interno della wiki:\n\n1. Accedi alla pagina di creazione degli articoli cliccando su "Crea Articolo" dalla homepage della wiki o tramite il pulsante nella barra di navigazione.\n2. Inserisci il titolo dell''articolo. Questo sarà anche utilizzato per generare lo slug (URL) dell''articolo.\n3. Scrivi il contenuto dell''articolo utilizzando la sintassi Markdown.\n4. Aggiungi infobox, immagini e collegamenti ipertestuali all''interno dell''articolo, se necessario.\n5. Al termine, clicca su "Salva" per pubblicare l''articolo.\n\n### Esempio di sintassi Markdown\n\n```md\n# Titolo dell''Articolo\n\nQuesto è un esempio di contenuto scritto in Markdown.\n\n## Sottotitolo\n\nTesto normale. Puoi **evidenziare** testo in grassetto, *corsivo* o inserire [link](https://esempio.com).\n\n### Elenchi\n\n- Elemento 1\n- Elemento 2\n\n### Elenco numerato\n\n1. Passaggio 1\n2. Passaggio 2\n```\n\n## 2. Utilizzo degli Infobox\n\nGli infobox sono blocchi informativi che forniscono un riassunto strutturato dei dati relativi all''argomento dell''articolo, come dettagli su una città, una persona o un oggetto.\n\n### Creazione di un Infobox\n\n1. All''interno del contenuto dell''articolo, puoi inserire un infobox utilizzando il seguente formato:\n\n   ```md\n   {{ infobox_city\n   {\n       "title": "Città di Anthalys",\n       "Data di fondazione": "10 Ottobre 1830",\n       "Popolazione": "1.2 milioni",\n       "Superficie": "5.000 km²",\n       "Governatore": "J.J.Nhytros"\n   }\n   }}\n   ```\n\n2. L''infobox sarà visualizzato nella colonna laterale dell''articolo con un layout a card.\n\n### Tipologie di Infobox\n\n- **city**: Utilizzato per descrivere città.\n- **person**: Utilizzato per descrivere persone (biografie).\n  \nSe il tipo di infobox non esiste, sarà visualizzato un messaggio di errore.\n\n## 3. Gestione delle categorie\n\nOgni articolo può essere associato a una categoria che lo raggruppa con articoli simili. Le categorie possono essere navigabili e organizzate in una struttura gerarchica.\n\n### Aggiungere un articolo a una categoria\n\n1. Quando crei o modifichi un articolo, puoi selezionare una categoria dalla lista di categorie disponibili.\n2. Se un articolo appartiene a una categoria che ha sottocategorie, puoi visualizzare la gerarchia completa nella pagina dell''articolo.\n\n## 4. Modifica di un articolo esistente\n\nPuoi modificare un articolo in qualsiasi momento seguendo questi passaggi:\n\n1. Vai alla pagina dell''articolo che desideri modificare.\n2. Clicca sul pulsante "Modifica" visibile nella breadcrumb in alto a destra.\n3. Apporta le modifiche necessarie al titolo, al contenuto o all''infobox.\n4. Salva le modifiche cliccando su "Aggiorna".\n\n## 5. Template personalizzati\n\nI template sono blocchi riutilizzabili di codice o contenuto che puoi inserire all''interno degli articoli.\n\n### Creazione di un template\n\n1. Vai nella sezione template della wiki.\n2. Crea un nuovo template inserendo il codice HTML che desideri riutilizzare.\n3. Usa il template nei tuoi articoli con la sintassi:\n\n   ```md\n   {{ template:nome_template }}\n   ```\n\nI template possono essere modificati e aggiornati in qualsiasi momento.\n\n## 6. Link interni alla Wiki\n\nPuoi creare collegamenti ipertestuali a pagine interne della wiki utilizzando la seguente sintassi:\n\n```md\n[Nome della pagina](wiki/slug-della-pagina)\n```\n\nSe il link punta a una pagina non esistente, il collegamento sarà visualizzato in rosso. Cliccando su un link rosso, potrai creare la nuova pagina direttamente.\n\n## 7. Gestione dei Redirect\n\nSe un articolo viene rinominato, la wiki crea automaticamente un redirect dall''URL precedente a quello nuovo. Questo garantisce che i vecchi link non causino errori 404.\n\n### Creare un Redirect Manuale\n\n1. Puoi creare un redirect manuale specificando un vecchio e un nuovo slug.\n2. Se un utente visita un URL che è stato rinominato, verrà automaticamente reindirizzato alla nuova pagina.\n\n## 8. Gestione degli articoli mancanti\n\nQuando crei un collegamento a una pagina che non esiste, il link sarà evidenziato in rosso. Cliccando sul link, potrai:\n\n1. Creare l''articolo mancante direttamente dalla pagina che stai visualizzando.\n2. Modificare il collegamento se non desideri creare un nuovo articolo.\n\n---\n\nQuesta guida ti aiuterà a gestire e organizzare al meglio i tuoi contenuti all''interno della wiki di Anthalys. Se hai bisogno di ulteriori informazioni, non esitare a contattare gli amministratori della piattaforma.\n\n---','\n',char(10)),replace('<h1>Guida all''utilizzo della Wiki di Anthalys</h1>\n<p>Benvenuto nella Wiki di Anthalys! Questa guida ti aiuterà a comprendere come creare, modificare e gestire articoli, infobox, categorie e template all''interno della wiki. Segui i passaggi qui sotto per utilizzare al meglio le funzionalità della piattaforma.</p>\n<h2>Indice</h2>\n<ol>\n<li>\n<a href="#creazione-di-un-nuovo-articolo">Creazione di un nuovo articolo</a>\n</li>\n<li>\n<a href="#utilizzo-degli-infobox">Utilizzo degli Infobox</a>\n</li>\n<li>\n<a href="#gestione-delle-categorie">Gestione delle categorie</a>\n</li>\n<li>\n<a href="#modifica-di-un-articolo-esistente">Modifica di un articolo esistente</a>\n</li>\n<li>\n<a href="#template-personalizzati">Template personalizzati</a>\n</li>\n<li>\n<a href="#link-interni-alla-wiki">Link interni alla Wiki</a>\n</li>\n<li>\n<a href="#gestione-dei-redirect">Gestione dei Redirect</a>\n</li>\n<li>\n<a href="#gestione-degli-articoli-mancanti">Gestione degli articoli mancanti</a>\n</li>\n</ol>\n<hr />\n<h2>1. Creazione di un nuovo articolo</h2>\n<p>Per creare un nuovo articolo all''interno della wiki:</p>\n<ol>\n<li>Accedi alla pagina di creazione degli articoli cliccando su &quot;Crea Articolo&quot; dalla homepage della wiki o tramite il pulsante nella barra di navigazione.</li>\n<li>Inserisci il titolo dell''articolo. Questo sarà anche utilizzato per generare lo slug (URL) dell''articolo.</li>\n<li>Scrivi il contenuto dell''articolo utilizzando la sintassi Markdown.</li>\n<li>Aggiungi infobox, immagini e collegamenti ipertestuali all''interno dell''articolo, se necessario.</li>\n<li>Al termine, clicca su &quot;Salva&quot; per pubblicare l''articolo.</li>\n</ol>\n<h3>Esempio di sintassi Markdown</h3>\n<pre><code class="language-md"># Titolo dell''Articolo\n\nQuesto è un esempio di contenuto scritto in Markdown.\n\n## Sottotitolo\n\nTesto normale. Puoi **evidenziare** testo in grassetto, *corsivo* o inserire [link](https://esempio.com).\n\n### Elenchi\n\n- Elemento 1\n- Elemento 2\n\n### Elenco numerato\n\n1. Passaggio 1\n2. Passaggio 2\n</code></pre>\n<h2>2. Utilizzo degli Infobox</h2>\n<p>Gli infobox sono blocchi informativi che forniscono un riassunto strutturato dei dati relativi all''argomento dell''articolo, come dettagli su una città, una persona o un oggetto.</p>\n<h3>Creazione di un Infobox</h3>\n<ol>\n<li>\n<p>All''interno del contenuto dell''articolo, puoi inserire un infobox utilizzando il seguente formato:</p>\n<pre><code class="language-md">{{ infobox_city\n{\n    &quot;title&quot;: &quot;Città di Anthalys&quot;,\n    &quot;Data di fondazione&quot;: &quot;10 Ottobre 1830&quot;,\n    &quot;Popolazione&quot;: &quot;1.2 milioni&quot;,\n    &quot;Superficie&quot;: &quot;5.000 km²&quot;,\n    &quot;Governatore&quot;: &quot;J.J.Nhytros&quot;\n}\n}}\n</code></pre>\n</li>\n<li>\n<p>L''infobox sarà visualizzato nella colonna laterale dell''articolo con un layout a card.</p>\n</li>\n</ol>\n<h3>Tipologie di Infobox</h3>\n<ul>\n<li>\n<strong>city</strong>: Utilizzato per descrivere città.</li>\n<li>\n<strong>person</strong>: Utilizzato per descrivere persone (biografie).</li>\n</ul>\n<p>Se il tipo di infobox non esiste, sarà visualizzato un messaggio di errore.</p>\n<h2>3. Gestione delle categorie</h2>\n<p>Ogni articolo può essere associato a una categoria che lo raggruppa con articoli simili. Le categorie possono essere navigabili e organizzate in una struttura gerarchica.</p>\n<h3>Aggiungere un articolo a una categoria</h3>\n<ol>\n<li>Quando crei o modifichi un articolo, puoi selezionare una categoria dalla lista di categorie disponibili.</li>\n<li>Se un articolo appartiene a una categoria che ha sottocategorie, puoi visualizzare la gerarchia completa nella pagina dell''articolo.</li>\n</ol>\n<h2>4. Modifica di un articolo esistente</h2>\n<p>Puoi modificare un articolo in qualsiasi momento seguendo questi passaggi:</p>\n<ol>\n<li>Vai alla pagina dell''articolo che desideri modificare.</li>\n<li>Clicca sul pulsante &quot;Modifica&quot; visibile nella breadcrumb in alto a destra.</li>\n<li>Apporta le modifiche necessarie al titolo, al contenuto o all''infobox.</li>\n<li>Salva le modifiche cliccando su &quot;Aggiorna&quot;.</li>\n</ol>\n<h2>5. Template personalizzati</h2>\n<p>I template sono blocchi riutilizzabili di codice o contenuto che puoi inserire all''interno degli articoli.</p>\n<h3>Creazione di un template</h3>\n<ol>\n<li>\n<p>Vai nella sezione template della wiki.</p>\n</li>\n<li>\n<p>Crea un nuovo template inserendo il codice HTML che desideri riutilizzare.</p>\n</li>\n<li>\n<p>Usa il template nei tuoi articoli con la sintassi:</p>\n<pre><code class="language-md">{{ template:nome_template }}\n</code></pre>\n</li>\n</ol>\n<p>I template possono essere modificati e aggiornati in qualsiasi momento.</p>\n<h2>6. Link interni alla Wiki</h2>\n<p>Puoi creare collegamenti ipertestuali a pagine interne della wiki utilizzando la seguente sintassi:</p>\n<pre><code class="language-md">[Nome della pagina](wiki/slug-della-pagina)\n</code></pre>\n<p>Se il link punta a una pagina non esistente, il collegamento sarà visualizzato in rosso. Cliccando su un link rosso, potrai creare la nuova pagina direttamente.</p>\n<h2>7. Gestione dei Redirect</h2>\n<p>Se un articolo viene rinominato, la wiki crea automaticamente un redirect dall''URL precedente a quello nuovo. Questo garantisce che i vecchi link non causino errori 404.</p>\n<h3>Creare un Redirect Manuale</h3>\n<ol>\n<li>Puoi creare un redirect manuale specificando un vecchio e un nuovo slug.</li>\n<li>Se un utente visita un URL che è stato rinominato, verrà automaticamente reindirizzato alla nuova pagina.</li>\n</ol>\n<h2>8. Gestione degli articoli mancanti</h2>\n<p>Quando crei un collegamento a una pagina che non esiste, il link sarà evidenziato in rosso. Cliccando sul link, potrai:</p>\n<ol>\n<li>Creare l''articolo mancante direttamente dalla pagina che stai visualizzando.</li>\n<li>Modificare il collegamento se non desideri creare un nuovo articolo.</li>\n</ol>\n<hr />\n<p>Questa guida ti aiuterà a gestire e organizzare al meglio i tuoi contenuti all''interno della wiki di Anthalys. Se hai bisogno di ulteriori informazioni, non esitare a contattare gli amministratori della piattaforma.</p>\n<hr />\n','\n',char(10)),NULL,'1973-09-24 07:45:10','1973-09-24 07:45:10','1973-09-24 07:45:10');