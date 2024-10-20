        <!-- Fixed buttons under the title, now horizontally aligned -->
        <div class="d-flex justify-content-center mb-3"
            style="position: sticky; top: 0; z-index: 1000; background-color: var(--white);">
            <form action="{{ route('messages.bulkDelete') }}" method="POST" id="bulk-actions-form" class="d-flex">
                @csrf
                <button type="button" class="btn btn-danger me-2"
                    onclick="bulkAction('{{ route('messages.bulkDelete') }}')">
                    {!! getIcon('trash', 'bi', 'Delete Selected') !!}
                </button>
                <button type="button" class="btn btn-secondary me-2"
                    onclick="bulkAction('{{ route('messages.bulkArchive') }}')">
                    {!! getIcon('archive', 'bi', 'Archive Selected') !!}
                </button>
                <button type="button" class="btn btn-success"
                    onclick="bulkAction('{{ route('messages.bulkRestore') }}')">
                    {!! getIcon('arrow-repeat', 'bi', 'Restore Selected') !!}
                </button>
            </form>
        </div>
