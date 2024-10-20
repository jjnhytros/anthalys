<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Anthaleja\HomeController;
use App\Http\Controllers\Anthaleja\Wiki\PortalController;
use App\Http\Controllers\Anthaleja\Wiki\ArticleController;
use App\Http\Controllers\Anthaleja\Wiki\TemplateController;
use App\Http\Controllers\Anthaleja\Admin\WikiAdminController;
use App\Http\Controllers\Anthaleja\Wiki\WikiCategoryController;

Route::prefix('/wiki')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('home'); // Home page con lista articoli
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index'); // Lista articoli
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create'); // Pagina creazione articoli
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store'); // Salvataggio articolo
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show'); // Visualizzazione articolo
    Route::post('/articles/generate', [ArticleController::class, 'generate'])->name('articles.generate');
    Route::post('/articles/{slug}/translate', [ArticleController::class, 'translate'])->name('articles.translate');
    Route::post('/articles/{id}/like', [ArticleController::class, 'like'])->name('articles.like');
    Route::post('/articles/{id}/timeSpent', [ArticleController::class, 'timeSpent'])->name('articles.timeSpent');
    Route::get('/articles/merge/{id1}/{id2}', [ArticleController::class, 'showMergeForm'])->name('articles.mergeForm');
    Route::post('/articles/merge/{id1}/{id2}', [ArticleController::class, 'merge'])->name('articles.merge');
    Route::post('/articles/search', [ArticleController::class, 'search'])->name('articles.search');

    Route::middleware('auth')->group(function () {
        Route::get('/admin/edit-weights', [WikiAdminController::class, 'editWeights'])->name('admin.editWeights');
        Route::post('/admin/update-weights', [WikiAdminController::class, 'updateWeights'])->name('admin.updateWeights');
    });
});

// Rotte per gli articoli
// Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');      // Elenco degli articoli
// Route::get('/wiki/create', [ArticleController::class, 'create'])->name('articles.create');
// Route::get('/wiki/{slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit'); // Messo prima
// Route::get('/wiki/{slug}', [ArticleController::class, 'show'])->name('articles.show');     // Messo dopo
// Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');     // Salvataggio di un nuovo articolo
// Route::put('/wiki/{slug}', [ArticleController::class, 'update'])->name('articles.update');
// Route::post('/articles/generate', [ArticleController::class, 'generate'])->name('articles.generate');

// Rotte per i template
// Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');  // Elenco dei template
// Route::get('/templates/create', [TemplateController::class, 'create'])->name('templates.create'); // Creazione di un template
// Route::post('/templates', [TemplateController::class, 'store'])->name('templates.store');
// Route::get('/templates/{slug}', [TemplateController::class, 'apply'])->name('templates.apply'); // Applicazione di un template
// Route::get('/templates/{slug}/edit', [TemplateController::class, 'edit'])->name('templates.edit');
// Route::put('/templates/{slug}', [TemplateController::class, 'update'])->name('templates.update');

// Portali
// Route::get('/portals', [PortalController::class, 'index'])->name('portals.index');
// Route::get('/portals/{id}', [PortalController::class, 'show'])->name('portals.show');

// Categorie
// Route::get('/categories', [WikiCategoryController::class, 'index'])->name('categories.index');
// Route::get('/categories/{slug}', [WikiCategoryController::class, 'show'])->name('categories.show');


// Rotte per la gestione di anteprime markdown e formattazione (opzionale)
// Route::post('/markdown/preview', [ArticleController::class, 'preview'])->name('markdown.preview'); // Endpoint per anteprima live (se decidi di gestirla via AJAX)
