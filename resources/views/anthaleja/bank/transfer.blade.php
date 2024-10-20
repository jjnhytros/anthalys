@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Bonifico - Point of Sale</h2>

        <form id="transferForm" class="mt-3" action="{{ route('bank.processTransfer') }}" method="POST">
            @csrf

            {{-- Campo nascosto per il numero di conto del mittente --}}
            <input type="hidden" name="senderAccount" value="{{ Auth::user()->character->bank_account }}">

            <div class="form-group">
                <label for="recipientAccount">Numero conto (Destinatario)</label>
                <input type="text" name="recipientAccount" id="recipientAccount" class="form-control"
                    placeholder="Inserisci il numero del conto destinatario" required>
            </div>

            <div class="form-group mt-3">
                <label for="amount">Importo</label>
                <input type="number" name="amount" id="amount" class="form-control" placeholder="Inserisci l'importo"
                    required min="1">
            </div>

            {{-- Pulsante per aprire il modale di conferma --}}
            <button type="button" class="btn btn-primary mt-3" onclick="showConfirmationModal()">Invia denaro</button>
        </form>

        {{-- Modale di conferma --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Conferma Bonifico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Stai per inviare <strong id="confirmAmount"></strong> al conto <strong
                                id="confirmRecipient"></strong><br />
                            intestato a <strong id="confirmFullName"></strong>.</p>
                        <p>La commissione per la transazione è di <strong id="confirmCommission"></strong>.</p>
                        <p>Totale dedotto: <strong id="confirmTotal"></strong></p>
                        <p>Vuoi procedere?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" onclick="submitTransfer()">Conferma</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const commission =
        {{ config('ath.bank.commission_fee') }}; // Prendi il valore della commissione dalla configurazione

        async function showConfirmationModal() {
            const amount = parseFloat(document.getElementById('amount').value);
            const recipientAccount = document.getElementById('recipientAccount').value;

            if (isNaN(amount) || recipientAccount === '') {
                alert('Inserisci un importo valido e un numero di conto valido.');
                return;
            }

            // Chiediamo i dettagli del destinatario al server tramite una richiesta AJAX
            const recipientDetails = await fetchRecipientDetails(recipientAccount);

            if (!recipientDetails) {
                alert('Destinatario non trovato.');
                return;
            }

            // Calcola il totale dedotto
            const total = amount + commission;

            // Imposta i valori nel modale
            document.getElementById('confirmAmount').textContent = amount.toFixed(2);
            document.getElementById('confirmRecipient').textContent = recipientAccount;
            document.getElementById('confirmFullName').textContent = recipientDetails.full_name;
            document.getElementById('confirmCommission').textContent = commission.toFixed(2);
            document.getElementById('confirmTotal').textContent = total.toFixed(2);

            // Mostra il modale
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        }

        async function fetchRecipientDetails(accountNumber) {
            try {
                const response = await fetch(`/api/recipients/${accountNumber}`);
                if (!response.ok) {
                    throw new Error('Errore nella richiesta');
                }
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Errore nel recupero dei dettagli del destinatario:', error);
                return null;
            }
        }

        function submitTransfer() {
            const form = document.getElementById('transferForm');

            if (form) {
                form.submit();
            } else {
                console.error('Form non trovato.');
            }
        }
    </script>
@endsection
