<?php

namespace App\Http\Controllers\Anthaleja\Marketplace;

use Illuminate\Http\Request;
use App\Models\Anthaleja\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Transaction;
use App\Models\Anthaleja\Marketplace\Item;
use App\Models\Anthaleja\Marketplace\Region;
use App\Models\Anthaleja\Character\Character\CraftingRecipe;
use App\Models\Anthaleja\Marketplace\MarketplaceEvent;
use App\Models\Anthaleja\Marketplace\MarketplaceTransaction;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $this->updatePrices();

        $query = Item::with('region');
        $region = null;

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $items = $query->get()->map(function ($item) use ($region) {
            if ($region) {
                $item->price = $item->price * $region->price_multiplier; // Calcola il prezzo solo se la regione è definita
            }
            return $item;
        });
        $items = $query->get();
        $regions = Region::all();
        return view('anthaleja.marketplace.index', compact('items', 'regions'));
    }

    public function show(Item $item)
    {
        $item->load('region');
        return view('anthaleja.marketplace.show', compact('item'));
    }

    public function purchase(Request $request, Item $item)
    {
        // Gestire l'acquisto, aggiornare il saldo del giocatore e creare una transazione
        $buyer = Auth::user()->character;
        $seller = $item->owner;

        // Aggiornare il saldo
        $buyer->bank -= $item->price;
        $seller->bank += $item->price;

        // Creare una transazione
        MarketplaceTransaction::create([
            'item_id' => $item->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'price' => $item->price,
        ]);

        $item->updateDemand(rand(rand(1, 3), rand(6, 12)));

        // Registra l'uscita della banca per l'acquirente
        Transaction::create([
            'sender_id' => $buyer->id,
            'recipient_id' => $seller->id,
            'amount' => $item->price,
            'type' => 'expense',
            'status' => 'success',
            'description' => 'Acquisto di ' . $item->name,
        ]);

        // Registra l'entrata della banca per il venditore
        Transaction::create([
            'sender_id' => $buyer->id,
            'recipient_id' => $seller->id,
            'amount' => $item->price,
            'type' => 'income',
            'status' => 'success',
            'description' => 'Vendita di ' . $item->name,
        ]);

        // Assegnare l'oggetto al compratore
        $item->owner_id = $buyer->id;
        $item->save();

        return redirect()->route('marketplace.index')->with('success', 'Acquisto completato');
    }

    public function craft(Request $request, CraftingRecipe $recipe)
    {
        $character = Auth::user()->character;
        $resources = $character->resources; // Recuperiamo le risorse del personaggio

        $requiredResources = json_decode($recipe->resources_required, true);

        // Verifica se il personaggio ha tutte le risorse necessarie
        foreach ($requiredResources as $resource => $quantity) {
            if (!isset($resources[$resource]) || $resources[$resource] < $quantity) {
                return redirect()->back()->with('error', 'Non hai abbastanza risorse per creare questo oggetto.');
            }
        }

        // Sottrai le risorse dal personaggio
        foreach ($requiredResources as $resource => $quantity) {
            $resources[$resource] -= $quantity;
        }
        $character->resources = json_encode($resources);
        $character->save();

        // Crea l'oggetto per il personaggio
        Item::create([
            'name' => $recipe->item->name,
            'type' => 'crafted',
            'price' => 0, // L'oggetto craftato può non avere un prezzo
            'owner_id' => $character->id,
            'is_craftable' => true,
        ]);

        return redirect()->route('anthaleja.marketplace.index')->with('success', 'Oggetto creato con successo!');
    }

    public function history()
    {
        $character = Auth::user()->character;

        // Recupera le transazioni in cui il personaggio è acquirente o venditore
        $transactions = MarketplaceTransaction::where('buyer_id', $character->id)
            ->orWhere('seller_id', $character->id)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('anthaleja.marketplace.history', compact('transactions'));
    }

    public function events()
    {
        $events = MarketplaceEvent::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->get();

        return view('anthaleja.marketplace.events', compact('events'));
    }
    public function notifyTransaction($transaction)
    {
        Message::create([
            'sender_id' => 1, // ID del sistema o dell'amministratore
            'recipient_id' => $transaction->buyer_id,
            'subject' => 'Conferma acquisto',
            'message' => "Hai acquistato l'oggetto '{$transaction->item->name}' per " . athel($transaction->amount),
            'is_notification' => true,
        ]);

        Message::create([
            'sender_id' => 1,
            'recipient_id' => $transaction->seller_id,
            'subject' => 'Conferma vendita',
            'message' => "Hai venduto l'oggetto '{$transaction->item->name}' per " . athel($transaction->amount),
            'is_notification' => true,
        ]);

        $transaction->update(['notification_sent' => true]);
    }
    public function demandSupplyMonitor()
    {
        // Recupera tutti gli oggetti con la domanda e la variazione di prezzo
        $items = Item::all()->map(function ($item) {
            // Calcola la variazione di prezzo rispetto al base_price
            $priceChange = ($item->price - $item->base_price) / $item->base_price * 100;

            // Motivi della variazione (ipotetico, per esempio basato sulla stagione o eventi)
            $reason = 'Fluttuazione stagionale';

            $item->price_change = $priceChange;
            $item->reason = $reason;

            return $item;
        });

        return view('anthaleja.marketplace.demand_supply', compact('items'));
    }

    public function distributeResources(Region $region)
    {
        $resources = Resource::where('region_id', $region->id)->get();

        foreach ($resources as $resource) {
            // Logica per la distribuzione delle risorse
        }
    }

    public function showPriceStatistics()
    {
        $priceData = MarketplaceTransaction::select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(price) as avg_price'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('anthaleja.marketplace.statistics', compact('priceData'));
    }
    public function updatePrices()
    {
        // Recupera la stagione corrente utilizzando ATHDateTime
        $currentSeason = (new ATHDateTime())->getCurrentSeason();
        // Limita il numero di oggetti da aggiornare per ciclo (es. 100 per ciclo)
        $batchSize = 96;
        $items = Item::where('updated_at', '<=', Carbon::now()->subHours(28))
            ->take($batchSize)
            ->get();

        foreach ($items as $item) {
            // Crea una chiave unica per l'item e la stagione corrente per il caching
            $cacheKey = 'item_' . $item->id . '_season_' . $currentSeason;
            // Verifica se il prezzo è già in cache
            $cachedPrice = Cache::get($cacheKey);
            if (!$cachedPrice) {

                // Verifica se sono passate più di 28 ore dall'ultimo aggiornamento del prezzo
                $lastUpdated = Carbon::parse($item->updated_at);
                if ($lastUpdated->diffInHours(Carbon::now()) >= 28) {

                    // Definisce dinamicamente se modificare il prezzo basato su più fattori
                    $shouldUpdatePrice = $this->shouldUpdatePrice($item, $currentSeason);

                    if ($shouldUpdatePrice) {
                        // Definisce moltiplicatori dinamici per la stagione
                        $dynamicMultiplier = $this->getSeasonModifier($currentSeason);

                        // Imposta un nuovo base_price se necessario
                        $basePrice = rand(1, $item->price - 1);

                        // Calcola il nuovo prezzo con il moltiplicatore
                        $updatedPrice = $basePrice * $dynamicMultiplier;

                        // Assicura che il nuovo prezzo non superi il doppio del base_price
                        if ($updatedPrice > ($basePrice * 2)) {
                            $updatedPrice = $basePrice * 2;
                        }

                        // Aggiorna l'item con il nuovo prezzo e il base_price
                        $item->update([
                            'base_price' => $basePrice,
                            'price' => $updatedPrice,
                        ]);
                        // Riduci la domanda leggermente se l'item non viene acquistato
                        $item->updateDemand(rand(-2, -5)); // Riduzione lenta della domanda
                    }
                }
            } else {
                // Se esiste già un prezzo in cache, usalo senza ricalcolare
                $item->update([
                    'price' => $cachedPrice,
                    'updated_at' => Carbon::now(), // Imposta l'ora dell'ultimo aggiornamento
                ]);
            }
        }
        // Se ci sono più oggetti da aggiornare, chiama di nuovo la funzione in batch
        if (Item::where('updated_at', '<=', Carbon::now()->subHours(28))->count() > 0) {
            // Invia una chiamata successiva per aggiornare il prossimo batch
            $this->updatePrices();
        }
    }

    private function shouldUpdatePrice($item, $currentSeason)
    {
        // Base random chance
        $baseChance = rand(0, 100);

        // Influenza la decisione basandosi sul tipo di item
        $itemTypeModifier = $this->getItemTypeModifier($item->type);

        // Influenza la decisione basandosi sulla stagione
        $seasonModifier = $this->getSeasonModifier($currentSeason);

        // Influenza basata sulla domanda (ipotetico valore)
        $demandModifier = $this->getDemandModifier($item);

        // Calcola il punteggio finale combinando i vari fattori
        $finalChance = $baseChance + $itemTypeModifier + $seasonModifier + $demandModifier;

        // Definisce che il prezzo viene aggiornato se il punteggio finale è superiore a una certa soglia
        return $finalChance > rand(65, 75);
    }

    private function getSeasonModifier($currentSeason)
    {
        // Aggiunge o sottrae punti in base alla stagione corrente
        switch ($currentSeason) {
            case 'Spring':
                return rand(8, 12); // Probabilità maggiore in primavera
            case 'Summer':
                return rand(13, 18); // Probabilità ancora maggiore in estate
            case 'Autumn':
                return rand(-3, -7); // Meno probabilità in autunno
            case 'Winter':
                return rand(-8, -12); // Bassa probabilità in inverno
            default:
                return 0;
        }
    }

    private function getItemTypeModifier($itemType)
    {
        // Aggiunge o sottrae punti in base al tipo di item
        switch ($itemType) {
            case 'tech':
                return rand(8, 12); // Gli oggetti tech hanno maggiori probabilità di cambiamento
            case 'real_estate':
                return rand(3, 6);
            case 'agriculture':
                return rand(13, 18); // I prodotti agricoli sono molto dinamici
            case 'energy':
                return rand(6, 10);
            default:
                return 0; // Altri tipi hanno una probabilità neutra
        }
    }

    private function getDemandModifier($item)
    {
        $demand = $item->demand;

        if ($demand > rand(78, 82)) {
            return rand(15, 25); // Alta domanda, maggiore probabilità di cambio
        } elseif ($demand < rand(18, 22)) {
            return rand(-8, -12); // Bassa domanda, minore probabilità di cambio
        } elseif ($demand >= 50) {
            return rand(1, 5); // Domanda moderata in aumento
        } else {
            return rand(-1, -5); // Domanda moderata in calo
        }
    }
}
