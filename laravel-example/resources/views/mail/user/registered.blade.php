@component('mail::message')
# !Hola, {{ $user->name }}!
Tu registro fue tan exitoso como tu destino!
Ya puedes usar nuestra API, Crack, Idolo.

@component('mail::button', ['url' => config('app.url')])
Ir a la app
@endcomponent

Gracias, con mucho cariño,
{{ config('app.name') }}
@endcomponent