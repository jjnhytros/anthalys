<?php

namespace App\Http\Controllers\Anthaleja\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class WikiAdminController extends Controller
{
    /**
     * Mostra il modulo di amministrazione per i pesi delle raccomandazioni.
     *
     * @return \Illuminate\View\View
     */
    public function editWeights()
    {
        // Recupera i pesi dal database
        $weights = DB::table('weights')->pluck('weight', 'action')->toArray();

        return view('anthaleja.admin.wiki.edit-weights', compact('weights'));
    }

    /**
     * Aggiorna i pesi delle raccomandazioni.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWeights(Request $request)
    {
        // Valida i nuovi pesi inseriti dall'amministratore
        $request->validate([
            'view' => 'required|numeric|min:0',
            'like' => 'required|numeric|min:0',
            'comment' => 'required|numeric|min:0',
            'time_spent' => 'required|numeric|min:0',
        ]);

        // Invalida la cache e aggiorna i pesi nella cache
        Cache::forget('recommendation_weights');
        Cache::remember('recommendation_weights', 3600, function () {
            return DB::table('weights')->pluck('weight', 'action')->toArray();
        });

        // Aggiorna i pesi nel database
        DB::table('weights')->where('action', 'view')->update(['weight' => $request->input('view')]);
        DB::table('weights')->where('action', 'like')->update(['weight' => $request->input('like')]);
        DB::table('weights')->where('action', 'comment')->update(['weight' => $request->input('comment')]);
        DB::table('weights')->where('action', 'time_spent')->update(['weight' => $request->input('time_spent')]);

        // Reindirizza con un messaggio di successo
        return redirect()->route('admin.editWeights')->with('success', 'Weights updated successfully!');
    }
}
