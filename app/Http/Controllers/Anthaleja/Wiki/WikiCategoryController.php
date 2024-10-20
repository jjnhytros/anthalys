<?php

namespace App\Http\Controllers\Anthaleja\Wiki;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Wiki\WikiCategory;
use App\Services\Anthaleja\Wiki\AnalyticsService;

class WikiCategoryController extends Controller
{
    protected $analyticsService;
    public function __construct()
    {
        $this->analyticsService = new AnalyticsService();
    }

    public function index()
    {
        try {
            $categories = WikiCategory::all();
            // Statistiche relative alle categorie
            $categoryStats = $this->analyticsService->calculateCategoryStats();
            return view('anthaleja.wiki.categories.index', compact('categories', 'categoryStats'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error loading categories.']);
        }
    }

    /**
     * Mostra una categoria specifica e i suoi articoli.
     */
    public function show($slug, Request $request)
    {
        $category = WikiCategory::where('slug', $slug)->with('children')->firstOrFail();
        $articles = $category->articles()->paginate(24);

        return view('anthaleja.wiki.categories.show', compact('category', 'articles'));
    }

    /**
     * Mostra la pagina di creazione di una nuova categoria.
     */
    public function create()
    {
        return view('anthaleja.wiki.categories.create');
    }

    /**
     * Salva una nuova categoria nel database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wiki_categories',
            'slug' => 'required|string|max:255|unique:wiki_categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:wiki_categories,id',
        ]);

        WikiCategory::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category successfully created!');
    }

    /**
     * Modifica una categoria esistente.
     */
    public function edit($id)
    {
        $category = WikiCategory::findOrFail($id);
        return view('anthaleja.wiki.categories.edit', compact('category'));
    }

    /**
     * Aggiorna una categoria esistente nel database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:wiki_categories,name,' . $id,
            'slug' => 'required|string|max:255|unique:wiki_categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:wiki_categories,id',
        ]);

        $category = WikiCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category successfully updated!');
    }
}
