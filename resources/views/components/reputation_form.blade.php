{{-- resources/views/components/reputation_form.blade.php --}}
@if (isset($character) && $character->id !== Auth::user()->character->id)
    <form action="{{ route('reputation.store') }}" method="POST" class="reputation-form my-3">
        @csrf
        <input type="hidden" name="rated_character_id" value="{{ $character->id }}">
        <div class="mb-3">
            <label for="rating" class="form-label">Valutazione (da 1 a 5):</label>
            <select name="rating" class="form-select" required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="feedback" class="form-label">Feedback (opzionale):</label>
            <textarea name="feedback" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Invia valutazione</button>
    </form>
@endif
