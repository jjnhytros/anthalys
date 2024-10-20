<?php

namespace App\Http\Controllers\Anthaleja\Bank;

use Illuminate\Http\Request;
use App\Models\ATHDateTime\Month;
use App\Models\Anthaleja\Bank\IDS;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Anthaleja\Bank\Investment;

class InvestmentController extends Controller
{
    public function index()
    {
        $user = Auth::user()->character;
        $activeInvestments = Investment::where('character_id', $user->id)->where('status', 'active')->get();
        $completedInvestments = Investment::where('character_id', $user->id)->where('status', '!=', 'active')->get();

        return view('anthaleja.investments.index', compact('activeInvestments', 'completedInvestments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'types' => 'required|array',
            'types.*' => 'in:low_risk,medium_risk,high_risk',
            'duration' => 'required|integer|min:1',
        ]);

        $character = Auth::user()->character;

        DB::beginTransaction();

        try {
            if ($character->bank < $request->amount) {
                return redirect()->back()->withErrors(['error' => 'Fondi insufficienti.']);
            }
            $commission = config('ath.bank.commission_fee'); // Commissione prelevata
            $totalAmount = $request->amount + $commission;
            if ($character->bank < $totalAmount) {
                return redirect()->back()->withErrors(['error' => 'Fondi insufficienti per coprire la commissione.']);
            }

            $character->bank -= $totalAmount;
            $character->save();

            $investment = Investment::create([
                'character_id' => $character->id,
                'amount' => $request->amount,
                'type' => implode(',', $request->types),
                'duration' => $request->duration,
                'status' => 'active',
            ]);
            $investment->calculateReturnForTypes();

            DB::commit();
            return redirect()->route('investments.index')->with('success', 'Investimento creato con successo.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Errore durante la creazione dell\'investimento.']);
        }
    }

    public function updateIDS()
    {
        $this->calculateNewIDS();
        return response()->json(['status' => 'success', 'message' => 'IDS updated']);
    }

    public function calculateNewIDS()
    {
        $currentIDS = $this->getCurrentIDS();
        $currentMonth = date('n');

        $multiplier = DB::table('months')->where('id', $currentMonth)->value('multiplier');

        $newIDS = $currentIDS * $multiplier;
        IDS::updateOrCreate([], ['value' => $newIDS]);

        return $newIDS;
    }

    public function getCurrentIDS()
    {
        return IDS::latest()->value('value');
    }
}
