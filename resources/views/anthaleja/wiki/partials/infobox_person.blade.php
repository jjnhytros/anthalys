<!-- resources/views/wiki/partials/infobox_person.blade.php -->
<div class="infobox"
    style="border: 1px solid #ccc; background-color: #f9f9f9; padding: 10px; width: 250px; float: right; margin-left: 15px;">
    <h3>{{ $title }}</h3>
    <table>
        @foreach ($attributes as $key => $value)
            <tr>
                <th style="text-align: left; padding: 5px;">{{ $key }}</th>
                <td style="text-align: right; padding: 5px;">{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</div>
