{{-- resources/views/anthaleja/sonet/partials/filetypes.blade.php --}}
@if (!empty($sonet['media']))
    @php
        $media = $sonet['media'];
    @endphp

    @if (in_array($sonet['icon'], ['filetype-img']))
        <img src="{{ asset($media) }}" alt="media" class="img-fluid">
    @elseif ($sonet['icon'] === 'filetype-video')
        <video controls>
            <source src="{{ asset($media) }}" type="video/mp4">
        </video>
    @else
        {!! getIcon($sonet['icon'], 'bi', 'File media') !!}
        <a href="{{ asset($media) }}" target="_blank">Visualizza file</a>
    @endif
@endif
