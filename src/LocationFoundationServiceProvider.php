<?php

namespace Nsingularity\GeneralModule\Foundation;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\ApiDebug;
use Nsingularity\GeneralModule\Foundation\Http\Middleware\Api\AuthenticateApiToken;
use Nsingularity\GeneralModule\Foundation\Supports\DecodeRequest;

class LocationFoundationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

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
                //Entity
                __DIR__ . '/_publishFiles/Entities/Locations/Country.txt'               => app_path('Entities/Locations/Country.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/City.txt'                  => app_path('Entities/Locations/City.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/District.txt'              => app_path('Entities/Locations/District.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/Village.txt'               => app_path('Entities/Locations/Village.php'),
                //Repository
                __DIR__ . '/_publishFiles/Repositories/CountryRepository.txt'           => app_path('Repositories/CountryRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/CityRepository.txt'              => app_path('Repositories/CityRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/DistrivtRepository.txt'          => app_path('Repositories/DistrivtRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/VillageRepository.txt'           => app_path('Repositories/VillageRepository.php'),
                //Transformer
                __DIR__ . '/_publishFiles/Transformers/CountryTransformer.txt'          => app_path('Transformers/CountryTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/CityTransformer.txt'             => app_path('Transformers/CityTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/DistrictTransformer.txt'         => app_path('Transformers/DistrictTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/VillageTransformer.txt'          => app_path('Transformers/VillageTransformer.php'),
                //seeder
                __DIR__ . '/_publishFiles/database/seeds/LocationSubdivisionSeeder.php' => base_path('database/seeds/LocationSubdivisionSeeder.php'),


            ], 'ns-module-location-foundation');
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
