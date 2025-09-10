
@php
    use Iluminate\Support\Facades\Storage;
    use Iluminate\Support\Facades\File;



    $cid = null;
    if(!@empty($post->cover_image)) {
        $absPath = Storage::disk('public')->path($post->cover_image);
        if(File::exists($absPath)); {
            $cid = $message->embed($absPath);
        }
    }
@endphp
<x-mail::message>
# Nueva publicacion: Creada



**Titulo**: {{ $post->title }}

**Autor**: {{ $autor }}

**Fecha de publicacion**: {{ $published_at ?? 'No definida' }}
____


{{ Str::limit($post->content, 200) }}

<x-mail::button :url="''">
Ver publicacion Completa
</x-mail::button>
___

> Nota: La mala para santiago

___

@if ($cid)
<p style="text-align: center; margin: 0 0 16px">
    <img src="{{ $cid }}" alt="Portada del post" style="max-width: 100%; height:auto; border-radius:8px">
</p>
@endif

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
