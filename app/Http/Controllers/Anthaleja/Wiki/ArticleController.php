<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\TemplateHelpers;
use Illuminate\Support\Facades\Cache;
use App\Services\Anthaleja\AI\AIService;
use App\Models\Anthaleja\Wiki\WikiArticle;
use League\CommonMark\CommonMarkConverter;
use App\Models\Anthaleja\Wiki\WikiCategory;
use App\Models\Anthaleja\Wiki\WikiRedirect;
use App\Models\Anthaleja\Character\Character;
use App\Services\Anthaleja\AI\LearningService;
use App\Models\Anthaleja\Wiki\CharacterActivity;
use App\Services\Anthaleja\Wiki\ResearchService;
use App\Services\Anthaleja\Wiki\AnalyticsService;
use App\Services\Anthaleja\Wiki\ModerationService;
use App\Services\Anthaleja\Wiki\WritingAidService;
use App\Services\Anthaleja\Wiki\IntegrationService;
use App\Services\Anthaleja\Wiki\ArticleMergeService;
use App\Services\Anthaleja\Wiki\DuplicateArticleService;

class ArticleController extends Controller
{
    protected $aiService;
    protected $analyticsService;
    protected $learningService;
    protected $writingAidService;
    protected $researchService;
    protected $integrationService;
    protected $duplicateArticleService;
    protected $articleMergeService;

    public function __construct(
        AIService $aiService,
        DuplicateArticleService $duplicateArticleService,
        ArticleMergeService $articleMergeService
    ) {
        $this->aiService = $aiService;
        $this->analyticsService = new AnalyticsService();
        $this->learningService = new LearningService();
        $this->writingAidService = new WritingAidService();
        $this->researchService = new ResearchService();
        $this->integrationService = new IntegrationService();
        $this->duplicateArticleService = $duplicateArticleService;
        $this->articleMergeService = $articleMergeService;
    }

    public function index()
    {
        $featuredArticles = WikiArticle::where('is_featured', true)->take(5)->get();
        $latestArticles = WikiArticle::latest()->take(5)->get();
        $categories = WikiCategory::all();
        $articleCount = WikiArticle::count();
        $contributorCount = Character::has('articles')->count();

        return view('anthaleja.wiki.wiki', compact('featuredArticles', 'latestArticles', 'categories', 'articleCount', 'contributorCount'));
    }

    public function create(Request $request)
    {
        // Mostra il modulo per creare un nuovo articolo
        return view('anthaleja.wiki.articles.create', [
            'title' => 'Create a New Article',
            'formAction' => route('articles.store'),
            'isEdit' => false,
            'nameValue' => $request->query('articleTitle') ?? '',
            'contentValue' => '',
            'categories' => WikiCategory::all(),
            'renderInfoboxValue' => true,
        ]);
    }

    public function edit($id)
    {
        $article = WikiArticle::findOrFail($id);

        return view('anthaleja.wiki.articles.edit', [
            'title' => 'Edit Article: ' . $article->title,
            'formAction' => route('articles.update', $article->id),
            'isEdit' => true,
            'nameValue' => $article->title,
            'contentValue' => $article->content,
            'categories' => WikiCategory::all(),
            'renderInfoboxValue' => $article->render_infobox
        ]);
    }


    public function generate(Request $request)
    {
        // Valida l'input
        $request->validate([
            'seed_word' => 'required|string|max:255',
        ]);

        // Usa il servizio AI per generare un articolo
        $generatedContent = $this->aiService->generateArticle($request->input('seed_word'), 300);

        return view('anthaleja.wiki.articles.create', [
            'title' => 'Generated Article',
            'formAction' => route('articles.store'),
            'isEdit' => false,
            'nameValue' => $request->input('seed_word'),
            'contentValue' => $generatedContent,
            'categories' => WikiCategory::all(),
        ]);
    }

    public function store(Request $request, ModerationService $moderationService)
    {
        // Valida la richiesta
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'render_infobox' => 'boolean'
        ]);

        // Controlla se il contenuto è offensivo o spam
        if ($moderationService->containsOffensiveLanguage($request->input('content'))) {
            return redirect()->back()->with('error', 'Your article contains offensive language and cannot be published.');
        }

        if ($moderationService->isSpam($request->input('content'))) {
            return redirect()->back()->with('error', 'Your article has been flagged as spam and cannot be published.');
        }


        // Prepara i dati per il nuovo articolo
        $character = Auth::user()->character;
        $converter = new CommonMarkConverter();
        $htmlContent = $converter->convert($request->input('content'));

        $article = new WikiArticle();
        $article->character_id = $character->id;
        $article->title = $request->title;
        $article->slug = Str::slug($request->title);
        $article->content = $request->input('content');
        $article->html_content = $htmlContent;
        $article->render_infobox = $request->input('render_infobox', true);
        $article->published_at = now();

        // Suggerimenti di miglioramento dal WritingAidService
        $writingSuggestions = $this->writingAidService->suggestImprovements($request->input('content'));

        // Salva l'articolo
        $article->save();

        // Reindirizza con suggerimenti
        return redirect()->route('articles.index')->with('success', 'Article created successfully!')
            ->with('writingSuggestions', $writingSuggestions);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'render_infobox' => 'boolean' // Validazione del campo render_infobox
        ]);

        // Aggiorna l'articolo esistente
        $article = WikiArticle::findOrFail($id);
        $article->update([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'auto_infobox' => $request->has('auto_infobox'),
            'render_infobox' => $request->input('render_infobox', false) // Aggiorna il campo render_infobox
        ]);

        return redirect()->route('articles.index')->with('success', 'Article updated successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $articles =
            WikiArticle::where('content', 'like', '%' . $query . '%')
            ->orWhere('title', 'like', '%' . $query . '%')
            ->get();


        return view('anthaleja.wiki.articles.search', compact('articles', 'query'));
    }


    public function show(Request $request, $slug)
    {
        // Gestione dei redirect con caching per migliorare le prestazioni
        $maxRedirects = 5;
        $redirectCount = 0;

        while ($redirect = Cache::remember("redirect_{$slug}", 3600, function () use ($slug) {
            return WikiRedirect::where('old_slug', $slug)->first();
        })) {
            $slug = $redirect->new_slug;

            if (++$redirectCount > $maxRedirects) {
                abort(500, 'Redirect loop detected.');
            }

            $redirect->increment('redirect_count');
            DB::table('wiki_redirect_logs')->insert([
                'redirect_id' => $redirect->id,
                'character_ip' => $request->ip(),
                'character_id' => Auth::check() ? Auth::user()->character->id : null,
                'created_at' => now(),
            ]);
        }

        // Ottieni l'articolo e i dati correlati
        $article = WikiArticle::with(['character', 'tags', 'category', 'portals'])->where('slug', $slug)->firstOrFail();
        $content = $article->content;  // Lavoriamo su una copia del contenuto dell'articolo

        // Verifica se è abilitata la generazione dinamica dell'infobox
        if ($article->render_infobox) {
            // AI per suggerire e generare infobox dinamicamente in base alla categoria e al contenuto
            $infoboxData = $this->learningService->suggestInfobox($article);

            // Se l'AI ha generato un infobox, visualizzalo
            if ($infoboxData) {
                $infobox = TemplateHelpers::renderInfobox($infoboxData['type'], $infoboxData['content']);
            } else {
                // Altrimenti, cerca se c'è un infobox nel contenuto dell'articolo e lo rimuove
                $infoboxData = $this->removeInfoboxFromContent($content);
                $infobox = $infoboxData ? TemplateHelpers::renderInfobox($infoboxData['type'], $infoboxData['content']) : null;
            }
        } else {
            // Visualizza l'infobox raw senza modificarlo
            $infobox = $this->getRawInfobox($content);
        }

        // Recupera gli articoli correlati
        $relatedArticles = collect($this->aiService->suggestRelatedArticles($article));

        // Suggerisci articoli duplicati o ridondanti
        $similarTitles = collect($this->duplicateArticleService->findSimilarTitles($article->title));
        $similarContent = collect($this->duplicateArticleService->findSimilarContent($article->content));
        $similarCategories = collect($this->duplicateArticleService->findByCategory($article));
        $suggestMerge = ($similarTitles->isNotEmpty() || $similarContent->isNotEmpty() || $similarCategories->isNotEmpty());

        // Registra l'attività del Character
        if (Auth::check()) {
            $character = Auth::user()->character;
            CharacterActivity::create([
                'character_id' => $character->id,
                'article_id' => $article->id,
                'action' => 'view',
            ]);
        }

        // Gestione dell'infobox: parsing e rimozione dal contenuto dell'articolo
        $infoboxData = $this->removeInfoboxFromContent($content);  // Usa la copia locale del contenuto
        $infobox = $infoboxData ? TemplateHelpers::renderInfobox($infoboxData['type'], $infoboxData['content']) : null;

        // Converti il contenuto in HTML
        $converter = new CommonMarkConverter();
        $htmlContent = $converter->convert($content)->getContent();

        return view('anthaleja.wiki.articles.show', [
            'article' => $article,
            'content' => $htmlContent,
            'infobox' => $infobox,
            'relatedArticles' => $relatedArticles,
            'similarTitles' => $similarTitles,
            'similarContent' => $similarContent,
            'similarCategories' => $similarCategories,
            'suggestMerge' => $suggestMerge,
        ]);
    }

    public function showMergeForm($id1, $id2)
    {
        $article1 = WikiArticle::findOrFail($id1);
        $article2 = WikiArticle::findOrFail($id2);

        // Suddividi i contenuti in sezioni
        $sections1 = $this->articleMergeService->splitIntoSections($article1->content);
        $sections2 = $this->articleMergeService->splitIntoSections($article2->content);

        // Calcola le sezioni simili e i suggerimenti di fusione
        $suggestedMerges = $this->articleMergeService->findSimilarSections($sections1, $sections2);

        return view('anthaleja.wiki.articles.merge', compact('article1', 'article2', 'sections1', 'sections2', 'suggestedMerges'));
    }

    public function fetchExternalData(Request $request)
    {
        // Usa IntegrationService per ottenere dati esterni
        $apiUrl = $request->input('apiUrl');
        $externalData = $this->integrationService->fetchExternalData($apiUrl);

        // Mostra i dati esterni
        return view('anthaleja.wiki.articles.external', compact('externalData'));
    }

    public function merge(Request $request, $id1, $id2)
    {
        $article1 = WikiArticle::findOrFail($id1);
        $article2 = WikiArticle::findOrFail($id2);

        // Sezioni selezionate dall'utente per la fusione
        $selectedSections = $request->input('sections');

        // Se l'utente ha scelto di unire paragrafi da entrambi gli articoli
        $mergedContent = [];
        foreach ($selectedSections['content'] as $section) {
            $mergedContent[] = trim($section);
        }

        // Effettua la fusione delle sezioni selezionate
        $mergedArticle = $this->articleMergeService->mergeArticlesWithSections($article1, $article2, [
            'title' => $request->input('title'),
            'content' => $mergedContent
        ]);

        return redirect()->route('articles.show', $mergedArticle->slug)
            ->with('success', 'Articoli fusi con successo!');
    }

    public function timeSpent(Request $request, $articleId)
    {
        if (Auth::check()) {
            $character = Auth::user()->character;

            // Registra il tempo di permanenza
            CharacterActivity::create([
                'character_id' => $character->id,
                'article_id' => $articleId,
                'action' => 'time_spent',
                'value' => $request->input('time_spent'), // Tempo in secondi
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Not authenticated'], 401);
    }

    private function getRawInfobox($content)
    {
        preg_match('/\{\{\s*infobox_(\w+)\s*(.*?)\}\}/s', $content, $matches);
        return $matches ? $matches[0] : null;
    }

    private function removeInfoboxFromContent(&$content)
    {
        $infoboxPattern = '/\{\{\s*infobox_(\w+)\s*(.*?)\}\}/s';
        if (preg_match($infoboxPattern, $content, $matches)) {
            $content = preg_replace($infoboxPattern, '', $content);
            return ['type' => $matches[1], 'content' => $matches[2]];
        }
        return null;
    }
}
