<?php

namespace Nsingularity\GeneralModule\Foundation;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\ApiDebug;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\AuthenticateApiToken;
use Nsingularity\GeneralModule\Foundation\Supports\DecodeRequest;

class GeneralFoundationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('auth.api', AuthenticateApiToken::class);
        $this->app['router']->aliasMiddleware('api.debug', ApiDebug::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->isLumen()) {
            $this->publishes([
                //Config
                __DIR__ . '/_publishFiles/config'                                            => config_path(''),
                //Entity
                __DIR__ . '/_publishFiles/Entities/EntityChangeLog.txt'                      => app_path('Entities/EntityChangeLog.php'),
                __DIR__ . '/_publishFiles/Entities/User.txt'                                 => app_path('Entities/User.php'),
                __DIR__ . '/_publishFiles/Auth.txt'                                          => app_path('Auth.php'),
                //Controller
                __DIR__ . '/_publishFiles/Controllers/Api/Controller.txt'                    => app_path('Http/Controllers/Api/Controller.php'),
                __DIR__ . '/_publishFiles/Controllers/Api/Auth/ForgotPasswordController.txt' => app_path('Http/Controllers/Api/Auth/ForgotPasswordController.php'),
                __DIR__ . '/_publishFiles/Controllers/Api/Auth/LoginController.txt'          => app_path('Http/Controllers/Api/Auth/LoginController.php'),
                __DIR__ . '/_publishFiles/Controllers/Api/Auth/RegisterController.txt'       => app_path('Http/Controllers/Api/Auth/RegisterController.php'),
                //Service
                __DIR__ . '/_publishFiles/Services/AuthService.txt'                          => app_path('Services/AuthService.php'),
                //Repository
                __DIR__ . '/_publishFiles/Repositories/UserRepository.txt'                   => app_path('Repositories/UserRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/UserSessionRepository.txt'            => app_path('Repositories/UserSessionRepository.php'),
                //Transformer
                __DIR__ . '/_publishFiles/Transformers/UserTransformer.txt'                  => app_path('Transformers/UserTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/UserSessionTransformer.txt'           => app_path('Transformers/UserSessionTransformer.php'),
                //Exception
                __DIR__ . '/_publishFiles/Exceptions/Handler.txt'                            => app_path('Exceptions/Handler.php'),
                //route
                __DIR__ . '/_publishFiles/routes/api/foundation.php'                         => base_path('routes/api/api.php'),
                //provider
                __DIR__ . '/_publishFiles/Providers/RouteServiceProvider.txt'                => app_path('Providers/RouteServiceProvider.php'),


            ], 'ns-module-foundation');
        }

        $this->app->bind(DecodeRequest::class, function ($app) {
            return new DecodeRequest();
        });

        $request    = app(Request::class);
        $authCookie = $request->cookie("auth");
        if ($authCookie) {
            $session     = $request->header("Authorization");
            $authService = new AuthService();
            $authService->loadUserSession($session, $authCookie);
        }
    }

    /**
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen');
    }
}
