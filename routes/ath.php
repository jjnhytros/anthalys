<?php
// routes/ath.php

use Illuminate\Support\Facades\Route;
use App\Models\Anthaleja\Character\NPC;
use App\Http\Controllers\Anthaleja\AIController;
use App\Models\Anthaleja\Character\NPCReputation;
use App\Http\Controllers\Anthaleja\HomeController;
use App\Http\Controllers\Anthaleja\MessageController;
use App\Http\Controllers\Anthaleja\City\MapController;
use App\Http\Controllers\Anthaleja\Bank\BankController;
use App\Http\Controllers\Anthaleja\Bank\LoanController;
use App\Http\Controllers\Anthaleja\PreferenceController;
use App\Http\Controllers\Anthaleja\NotificationController;
use App\Services\Anthaleja\Character\NPC\NPCMissionService;
use App\Services\Anthaleja\MegaWareHouse\SimulationService;
use App\Services\Anthaleja\Character\NPC\NPCTrainingService;
use App\Http\Controllers\Anthaleja\Bank\InvestmentController;
use App\Http\Controllers\Anthaleja\Bank\TransactionController;
use App\Services\Anthaleja\Character\NPC\NPCManagementService;
use App\Http\Controllers\Anthaleja\Marketplace\OfferController;
use App\Http\Controllers\Anthaleja\Character\ReputationController;
use App\Http\Controllers\Anthaleja\Marketplace\ResourceController;
use App\Services\Anthaleja\MegaWareHouse\ProductCategoryAIService;
use App\Http\Controllers\Anthaleja\Marketplace\MarketplaceController;
use App\Http\Controllers\Anthaleja\MegaWareHouse\WarehouseController;

Route::post('/profile/night-mode', [PreferenceController::class, 'updateNightMode'])->name('profile.updateNightMode');


Route::prefix('/warehouse')->group(function () {
    Route::get('/dashboard', [WarehouseController::class, 'dashboard'])->name('warehouse.dashboard');
    Route::get('/manage', [WarehouseController::class, 'manage'])->name('warehouse.manage');
    Route::get('/{warehouse}/energy', [WarehouseController::class, 'manageEnergy'])->name('warehouse.energy');
    Route::get('/levels', [WarehouseController::class, 'showLevels'])->name('warehouse.levels');
    Route::get('/levels/{level}/grid', [WarehouseController::class, 'loadGrid'])->name('warehouse.levels.grid');
    Route::get('levels/{levelId}/cells/{x}/{y}', [WarehouseController::class, 'showCellData']);
    Route::get('/generate-categories', function (ProductCategoryAIService $aiService) {
        return $aiService->generateProductCategories();
    });

    Route::get('/run-simulation', function (SimulationService $simulationService) {
        return $simulationService->runRealisticSimulation();
    });
    Route::get('/manage-npcs', function (NPCManagementService $npcService) {
        $npcService->assignTasks();
        $npcService->assignDailyMissions();
        return "NPC tasks assigned successfully.";
    });
    Route::get('/assign-daily-missions', function (NPCMissionService $missionService) {
        $missionService->assignDailyMissions();
        return "Daily missions assigned successfully.";
    });
    Route::get('/interact-with-npc/{npcId}/{action}', function ($npcId, $action) {
        $npc = NPC::find($npcId);
        return $npc->interactWithPlayer($action);
    });
    Route::get('/update-reputation/{npcId}/{score}', function ($npcId, $score) {
        $reputation = NPCReputation::updateOrCreate(
            ['npc_id' => $npcId],
            ['reputation_score' => $score]
        );
        return "Reputation updated for NPC {$npcId}.";
    });
    Route::get('/train-npc/{npcId}', function ($npcId, NPCTrainingService $trainingService) {
        $npc = NPC::find($npcId);
        $trainingService->conductTraining($npc);
        return "{$npc->name} has completed training.";
    });
});


Route::get('/map', [MapController::class, 'showMap'])->name('city.map');
Route::get('/map/square/{x}/{y}', [MapController::class, 'getSquareDetails']);
Route::get('/map/transport', [MapController::class, 'showTransportMap'])->name('transport.map');
// Route::get('/transport-map', [MapController::class, 'showTransportNetwork'])->name('transport.map');
Route::get('/map/sub-cells/{mapSquareId}', [MapController::class, 'loadSubCells'])->name('map.sub-cells');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware('auth')->name('dashboard');

Route::get('/ai/trigger-event/{characterId}', [AIController::class, 'triggerEvent']);
Route::get('/ai/make-decision/{characterId}', [AIController::class, 'makeDecision']);


Route::prefix('/bank')->group(function () {
    Route::prefix('transactions')->group(function () {
        Route::post('/confirm/{transaction}', [TransactionController::class, 'confirm'])->name('transactions.confirm');
        Route::post('/approve/{transaction}', [TransactionController::class, 'approve'])->name('transactions.approve');
    });
    Route::get('/', [BankController::class, 'index'])->name('bank.index');
    Route::get('/deposit', [BankController::class, 'depositForm'])->name('bank.deposit.form');
    Route::post('/deposit', [BankController::class, 'deposit'])->name('bank.deposit');
    Route::get('/loan-request', [LoanController::class, 'loanRequest'])->name('bank.loan.request');
    Route::get('/loans', [LoanController::class, 'showLoans'])->name('bank.loans');
    Route::get('/repay-loan', [LoanController::class, 'repayLoanForm'])->name('bank.loan.repay.form');
    Route::post('/repay-loan', [LoanController::class, 'repayLoan'])->name('bank.loan.repay');
    Route::post('/full-repayment', [LoanController::class, 'fullRepayment'])->name('bank.loan.full_repayment');
    Route::get('/statement', [BankController::class, 'statement'])->name('bank.statement');
    Route::get('/transfer', [BankController::class, 'transfer'])->name('bank.transfer');
    Route::post('/transfer', [BankController::class, 'processTransfer'])->name('bank.processTransfer');
    Route::get('/withdraw', [BankController::class, 'withdrawForm'])->name('bank.withdraw.form');
    Route::post('/withdraw', [BankController::class, 'withdraw'])->name('bank.withdraw');
    Route::post('/apply-loan', [LoanController::class, 'applyLoan'])->name('bank.loan.apply');
    Route::post('/extend-loan', [LoanController::class, 'extendLoan'])->name('bank.loan.extend');
    // Bank -> Investments
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');
    Route::get('/investments/create', [InvestmentController::class, 'create'])->name('investments.create');
    Route::post('/investments', [InvestmentController::class, 'store'])->name('investments.store');
    Route::post('/investments/{investment}/complete', [InvestmentController::class, 'completeInvestment'])->name('investments.complete');
    Route::get('/update-ids', [InvestmentController::class, 'updateIDS'])->name('ids.update');
});


//Bank -> Other
Route::get('/bank/check-emergency-balance', [LoanController::class, 'checkEmergencyBalance'])->name('bank.check.emergency');
Route::get('/bank/other-operations', [BankController::class, 'otherOperations'])->name('bank.other-operations');

// Marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{item}', [MarketplaceController::class, 'show'])->name('marketplace.show');
Route::post('/marketplace/{item}/purchase', [MarketplaceController::class, 'purchase'])->name('marketplace.purchase');
Route::post('/marketplace/{item}/make-offer', [OfferController::class, 'makeOffer'])->name('marketplace.makeOffer');
Route::get('/marketplace/history', [MarketplaceController::class, 'history'])->name('marketplace.history');
Route::post('/offers/{offer}/accept', [OfferController::class, 'acceptOffer'])->name('offers.accept');
Route::post('/offers/{offer}/reject', [OfferController::class, 'rejectOffer'])->name('offers.reject');
Route::get('/marketplace/demand-supply', [MarketplaceController::class, 'demandSupplyMonitor'])->name('marketplace.demand_supply');


Route::post('/regions/{region}/distribute-resources', [ResourceController::class, 'distributeResources'])->name('regions.distributeResources');

Route::middleware('auth')->group(function () {

    /**
     * Messages, Notifications, Internal E-mails
     */
    // Messages
    Route::prefix('/messages')->name('messages.')->group(function () {
        Route::get('/inbox', [MessageController::class, 'inbox'])->name('inbox');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::post('/send', [MessageController::class, 'send'])->name('send');
        Route::post('/{message}/reply', [MessageController::class, 'reply'])->name('reply');
        Route::post('/{message}/forward', [MessageController::class, 'forward'])->name('forward');
        Route::post('/bulk-delete', [MessageController::class, 'softDeleteSelected'])->name('bulkDelete'); // Renamed for clarity
        Route::post('/bulk-restore', [MessageController::class, 'restoreSelected'])->name('bulkRestore'); // Renamed for clarity
        Route::post('/bulk-force-delete', [MessageController::class, 'forceDeleteSelected'])->name('bulkForceDelete'); // Renamed for clarity
        Route::post('/bulk-archive', [MessageController::class, 'archiveSelected'])->name('bulkArchive'); // Renamed for clarity
        Route::get('/archived', [MessageController::class, 'archivedMessages'])->name('archived');
        Route::post('/restore-archived', [MessageController::class, 'restoreArchived'])->name('restoreArchived');
        Route::post('/force-delete-archived', [MessageController::class, 'forceDeleteArchived'])->name('forceDeleteArchived');
        Route::post('/{id}/toggle-read', [MessageController::class, 'toggleReadStatus'])->name('messages.toggleRead');
    });

    // Notifications
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::get('/check-notifications', [NotificationController::class, 'checkNotifications'])->name('messages.checkNotifications');
        Route::get('/', [NotificationController::class, 'index'])->name('index'); // Elenco delle notifiche
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/{id}/archive', [NotificationController::class, 'archive'])->name('archive');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [NotificationController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force', [NotificationController::class, 'forceDelete'])->name('forceDelete');
    });

    // Internal E-mails
    Route::prefix('/email')->name('emails.')->group(function () {
        Route::get('/inbox', [MessageController::class, 'inbox'])->name('inbox');
        Route::delete('/{message}/attachments/{attachmentIndex}', [MessageController::class, 'deleteAttachment'])->name('deleteAttachment');
        Route::get('/{message}/attachments/{attachmentIndex}/download', [MessageController::class, 'downloadAttachment'])->name('downloadAttachment');
    });

    // Reputation
    Route::prefix('/reputation')->name('reputation.')->group(function () {
        Route::post('/store', [ReputationController::class, 'store'])->name('store');
        // Altre route per la gestione delle valutazioni
    });

    /**
     * Character, Profile, Preferences
     */
    Route::prefix('/preferences')->name('preferences.')->group(function () {
        Route::get('/', [PreferenceController::class, 'index'])->name('index');
        Route::get('/edit', [PreferenceController::class, 'edit'])->name('edit');
        Route::put('/update', [PreferenceController::class, 'update'])->name('update');
    });

    Route::prefix('/games')->name('games.')->group(function () {
        // Route::post('/anthalian/draw-card', [AnthalianGameController::class, 'drawCard']);
    });
});


// Route::get('/pronunciation', [PronunciationController::class, 'convert'])->name('pronunciation');
// Route::post('/pronunciation', [PronunciationController::class, 'convert']);

// Route::get('/login', function () {
//     return view('anthaleja.frontier.login', ['title' => 'Login']);
// })->name('frontier.login');
// Route::post('/register', [FrontierController::class, 'register'])->name('frontier.register.submit');
// Route::get('/register', function () {
//     return view('anthaleja.frontier.register', ['title' => 'Register']);
// })->name('frontier.register');
// Route::post('/register', [FrontierController::class, 'register'])->name('frontier.register.submit');

// Route::get('/anthaleja/messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
// Route::get('/anthaleja/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
// Route::get('/anthaleja/messages/trashed', [MessageController::class, 'trashed'])->name('messages.trashed');
// Route::get('/anthaleja/messages/compose', [MessageController::class, 'create'])->name('messages.compose');
// Route::post('/anthaleja/messages/store', [MessageController::class, 'store'])->name('messages.store');
// Route::get('/anthaleja/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
// Route::delete('/anthaleja/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
// Route::patch('/anthaleja/messages/{id}/restore', [MessageController::class, 'restore'])->name('messages.restore');
// Route::delete('/anthaleja/messages/{id}/force', [MessageController::class, 'forceDelete'])->name('messages.forceDelete');

// Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
// Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');
// Route::get('/lessons/{lesson_id}/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
// Route::post('/lessons/{lesson_id}/exercises/{exercise_id}/submit', [ExerciseController::class, 'submit'])->name('exercises.submit');
// Route::get('/lessons/{lesson_id}/exercises/next', [ExerciseController::class, 'showNextQuestion'])->name('exercises.next');
// Route::post('/lessons/{lesson_id}/exercises/{exercise_id}/submit', [ExerciseController::class, 'submitAnswer'])->name('exercises.submit');
// Route::get('/lessons/{lesson_id}/exercises/completed', [ExerciseController::class, 'lessonCompleted'])->name('exercises.completed');

// Route::resource('characters', CharacterController::class);
// Route::get('characters/{character}/relationships', [RelationshipController::class, 'showRelationships'])->name('characters.relationships');
// Route::post('characters/{character}/relationships/add', [RelationshipController::class, 'addRelationship'])->name('characters.add-relationship');
// Route::post('characters/{character}/relationships/{relationship}/improve', [RelationshipController::class, 'improveRelationship'])->name('characters.improve-relationship');
// Route::post('characters/{character}/relationships/{relationship}/worsen', [RelationshipController::class, 'worsenRelationship'])->name('characters.worsen-relationship');
// Route::get('characters/{character}/social-events', [SocialEventController::class, 'showSocialEvents'])->name('characters.social-events');
// Route::get('characters/{character}/trade', [TradeController::class, 'showTradeForm'])->name('characters.trade');
// Route::post('characters/{character}/trade', [TradeController::class, 'executeTrade'])->name('characters.execute-trade');
// Route::get('characters/{character}/social-mission', [SocialMissionController::class, 'showMissionForm'])->name('characters.social-mission');
// Route::post('characters/{character}/social-mission', [SocialMissionController::class, 'assignMission'])->name('characters.assign-mission');
// Route::post('social-mission/{mission}/complete', [SocialMissionController::class, 'completeMission'])->name('characters.complete-mission');
// Route::get('characters/{character}/notifications', [NotificationController::class, 'show'])->name('characters.notifications');
// Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
// Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
// Route::get('characters/{character}/activities', [ActivityController::class, 'index'])->name('activities.index');
// Route::post('characters/{character}/activities', [ActivityController::class, 'planActivity'])->name('activities.plan');
// Route::patch('activities/{activity}/execute', [ActivityController::class, 'executeActivity'])->name('activities.execute');
// Route::get('regions', [RegionController::class, 'index'])->name('regions.index');
// Route::post('regions', [RegionController::class, 'store'])->name('regions.store');
// Route::post('characters/{character}/travel', [TravelController::class, 'start'])->name('travel.start');
// Route::patch('travel/{travelLog}/complete', [TravelController::class, 'complete'])->name('travel.complete');
// Route::get('travel/{travelLog}/status', [TravelController::class, 'showStatus'])->name('travel.status');
// Route::post('characters/{character}/travel/rest', [TravelController::class, 'restDuringTravel'])->name('travel.rest');
// Route::post('characters/{character}/travel/eat', [FoodController::class, 'eatDuringTravel'])->name('travel.eat');
// Route::get('characters/{character}/inventory', [CharacterController::class, 'showInventory'])->name('characters.inventory');
// // Route::post('/characters/{character}/buy-object/{objekt}', [ObjectController::class, 'buy'])->name('objects.buy');
// Route::post('/characters/{character}/apply-effects/{objekt}', [ObjectController::class, 'applyEffects'])->name('objects.applyEffects');
// Route::post('character/{character}/eat', [DailyActionController::class, 'eat'])->name('character.eat');
// Route::post('character/{character}/work', [DailyActionController::class, 'work'])->name('character.work');
// Route::post('character/{character}/clean', [DailyActionController::class, 'cleanHouse'])->name('character.clean');
// Route::post('character/{character}/rest', [DailyActionController::class, 'rest'])->name('character.rest');
// Route::post('/daily_actions/perform', [DailyActionController::class, 'performAction'])->name('daily_actions.perform');
// Route::post('/daily-actions/eat', [DailyActionController::class, 'eat'])->name('daily_actions.eat');
// Route::post('/daily-actions/work', [DailyActionController::class, 'work'])->name('daily_actions.work');
// Route::post('/daily-actions/clean-house', [DailyActionController::class, 'cleanHouse'])->name('daily_actions.clean_house');

// Route::get('crafting', [CraftingController::class, 'index'])->name('crafting.index');
// Route::post('crafting/{recipe}', [CraftingController::class, 'craft'])->name('crafting.craft');
// Route::get('logs', [LogController::class, 'index'])->name('logs.index');
// Route::get('work', [WorkController::class, 'index'])->name('work.index');
// Route::post('work/start', [WorkController::class, 'startWork'])->name('work.start');
// Route::post('work/specialize', [WorkController::class, 'specialize'])->name('work.specialize');
// Route::get('missions', [MissionController::class, 'index'])->name('missions.index');
// Route::post('missions/assign', [MissionController::class, 'assignMission'])->name('missions.assign');
// Route::get('missions/completed', [MissionController::class, 'completedMissions'])->name('missions.completed');
// Route::get('bills', [BillController::class, 'index'])->name('bills.index');
// Route::post('bills/{bill}/pay', [BillController::class, 'pay'])->name('bills.pay');

// Route::prefix('characters/{character}/objects')->group(function () {
//     Route::get('/', [ObjectController::class, 'index'])->name('objects.index');
//     Route::post('/{objekt}/buy', [ObjectController::class, 'buy'])->name('objects.buy');
// });
