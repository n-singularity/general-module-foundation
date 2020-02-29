<?php

namespace Nsingularity\GeneralModule\Foundation;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\ApiDebug;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\AuthenticateApiToken;
use Nsingularity\GeneralModule\Foundation\Services\MainServices\GeneralAuthService;
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
              __DIR__ . '/_publishFiles/config'                                            => config_path(''),
              //
              __DIR__ . '/_publishFiles/Entities/EntityChangeLog.txt'                      => app_path('Entities/EntityChangeLog.php'),
              __DIR__ . '/_publishFiles/Entities/User.txt'                                 => app_path('Entities/User.php'),
              __DIR__ . '/_publishFiles/Auth.txt'                                          => app_path('Auth.php'),
              //
              __DIR__ . '/_publishFiles/Controllers/Api/Controller.txt'                    => app_path('Http/Controllers/Api/Controller.php'),
              __DIR__ . '/_publishFiles/Controllers/Api/Auth/ForgotPasswordController.txt' => app_path('Http/Controllers/Api/Auth/ForgotPasswordController.php'),
              __DIR__ . '/_publishFiles/Controllers/Api/Auth/LoginController.txt'          => app_path('Http/Controllers/Api/Auth/LoginController.php'),
              __DIR__ . '/_publishFiles/Controllers/Api/Auth/RegisterController.txt'       => app_path('Http/Controllers/Api/Auth/RegisterController.php'),
              //
              __DIR__ . '/_publishFiles/Repositories/UserRepository.txt'                   => app_path('Repositories/UserRepository.php'),
              //
              __DIR__ . '/_publishFiles/Transformers/UserTransformer.txt'                  => app_path('Transformers/UserTransformer.php'),
              __DIR__ . '/_publishFiles/Exceptions/Handler.txt'                            => app_path('Exceptions/Handler.php'),
              __DIR__ . '/_publishFiles/routes/api/foundation.php'                         => base_path('routes/api/api.php'),
            
            ], 'ns-module-foundation');
        }
        
        $this->app->bind(DecodeRequest::class, function ($app) {
            return new DecodeRequest();
        });
        
        $request    = app(Request::class);
        $authCookie = $request->cookie("auth");
        if ($authCookie) {
            $session     = $request->header("Authorization");
            $authService = new GeneralAuthService();
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
