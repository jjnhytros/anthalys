{{-- resources/views/anthaleja/warehouse/partials/grid.blade.php --}}
<style>
    .table td {
        width: 0.1cm;
        height: 0.1cm;
        padding: 0;
        /* Rimuove il padding interno per mantenere le celle più compatte */
        text-align: center;
        /* Facoltativo: centra il testo nelle celle */
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered">
        @for ($i = 0; $i < 36; $i++)
            <tr>
                @for ($j = 0; $j < 36; $j++)
                    <td>
                        <a href="javascript:void(0)"
                            onclick="loadCellData({{ $level->id }}, {{ $i }}, {{ $j }})">
                            .
                        </a>
                    </td>
                @endfor
            </tr>
        @endfor
    </table>
</div>
