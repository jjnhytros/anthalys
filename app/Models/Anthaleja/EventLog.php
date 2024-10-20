<?php

namespace App\Models\Anthaleja;


use App\Models\Anthaleja\City\Property;
use App\Models\Anthaleja\City\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anthaleja\City\MapSquare;
use App\Models\Anthaleja\City\Investment;
use App\Models\Anthaleja\Character\Character;

class EventLog extends Model
{
    public $table = "event_logs";
    protected $fillable = ['character_id', 'event_type', 'character_attributes', 'event_context'];

    protected $casts = [
        'character_attributes' => 'array',
        'event_context' => 'array',
    ];

    public static function logInvestmentReturn(Character $character, Investment $investment)
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'investment_return',
            'details' => json_encode([
                'investment_type' => $investment->types,
                'new_value' => $investment->current_value,
                'duration_remaining' => $investment->duration,  // Giorni rimanenti nel sistema personalizzato
            ]),
            'event_context' => json_encode(['city_state' => 'investment_growth']),
            'created_at' => now(),
        ]);
    }

    public static function logLoanEvent(Character $character, $amount, $interestRate)
    {
        $details = [
            'loan_amount' => $amount,
            'interest_rate' => $interestRate,
            'due_date' => now()->addMonths(6),
        ];

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'loan_granted',
            'details' => json_encode($details),
            'event_context' => json_encode(['city_state' => 'economic_growth']),
            'created_at' => now(),
        ]);
    }

    public static function logLoanRepaymentEvent(Character $character, $amount)
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'loan_repaid',
            'details' => json_encode(['amount' => $amount]),
            'event_context' => json_encode(['city_state' => 'loan_repayment']),
            'created_at' => now(),
        ]);
    }

    public static function logInvestmentEvent(Character $character, $investmentAmount, $return, $riskLevel)
    {
        $details = [
            'investment_amount' => $investmentAmount,
            'return' => $return,
            'risk_level' => $riskLevel,
        ];

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'investment',
            'details' => json_encode($details),
            'event_context' => json_encode(['city_state' => 'investment']),
            'created_at' => now(),
        ]);
    }

    public static function logTaxEvent(Character $character, $taxAmount)
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'tax_paid',
            'details' => json_encode(['tax_amount' => $taxAmount]),
            'event_context' => json_encode(['city_state' => 'tax_collection']),
            'created_at' => now(),
        ]);
    }

    public static function logPropertyTransaction(Character $character, Property $property, $transactionType)
    {
        $details = [
            'property_id' => $property->id,
            'transaction_type' => $transactionType,
            'price' => $property->price,
        ];

        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'property_transaction',
            'details' => json_encode($details),
            'event_context' => json_encode(['city_state' => 'real_estate_market']),
            'created_at' => now(),
        ]);
    }

    public static function logMarketFluctuation(MapSquare $square, $fluctuation)
    {
        EventLog::create([
            'event_type' => 'market_fluctuation',
            'details' => json_encode(['map_square_id' => $square->id, 'fluctuation' => $fluctuation]),
            'event_context' => json_encode(['city_state' => 'market_fluctuation']),
            'created_at' => now(),
        ]);
    }

    public static function logResourceTransaction(Character $character, Resource $resource, $quantity)
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'resource_transaction',
            'details' => json_encode([
                'resource_type' => $resource->type,
                'quantity' => $quantity,
                'new_amount' => $resource->amount
            ]),
            'event_context' => json_encode(['city_state' => 'resource_market']),
            'created_at' => now(),
        ]);
    }

    public static function logResourceConsumption(Character $character, Resource $resource, $quantity)
    {
        EventLog::create([
            'character_id' => $character->id,
            'event_type' => 'resource_consumed',
            'details' => json_encode(['resource_type' => $resource->type, 'quantity' => $quantity]),
            'event_context' => json_encode(['city_state' => 'resource_consumption']),
            'created_at' => now(),
        ]);
    }
}
