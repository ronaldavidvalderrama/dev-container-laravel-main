<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    
    // POLICIA DE LAS POLITICAS
    protected $policies = [

    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Definir tockens

        Passport::tokensExpireIn(now()->addHours(2));
        Passport::refreshTokensExpireIn(now()->addDay(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));


        //Scopes
        //recurso.accion
        Passport::tokensCan ([
            'posts.read' => 'leer posts',
            'posts.write' => 'Cread o Editar posts',
            'posts.delete' => 'puede eliminar el posts',
            'posts.admin' => 'Acceso VIP',
        ]);

        Passport::defaultScopes([
            'posts.read',
        ]);
    }
}
