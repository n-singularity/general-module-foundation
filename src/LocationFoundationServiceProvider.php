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
                __DIR__ . '/_publishFiles/Entities/Locations/Country.txt'                 => app_path('Entities/Modules/Locations/Country.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/Province.txt'                => app_path('Entities/Modules/Locations/Province.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/City.txt'                    => app_path('Entities/Modules/Locations/City.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/District.txt'                => app_path('Entities/Modules/Locations/District.php'),
                __DIR__ . '/_publishFiles/Entities/Locations/Village.txt'                 => app_path('Entities/Modules/Locations/Village.php'),
                //Repository
                __DIR__ . '/_publishFiles/Repositories/Locations/CountryRepository.txt'   => app_path('Repositories/Locations/CountryRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/Locations/ProvinceRepository.txt'  => app_path('Repositories/Locations/ProvinceRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/Locations/CityRepository.txt'      => app_path('Repositories/Locations/CityRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/Locations/DistrictRepository.txt'  => app_path('Repositories/Locations/DistrictRepository.php'),
                __DIR__ . '/_publishFiles/Repositories/Locations/VillageRepository.txt'   => app_path('Repositories/Locations/VillageRepository.php'),
                //Transformer
                __DIR__ . '/_publishFiles/Transformers/Locations/CountryTransformer.txt'  => app_path('Transformers/Locations/CountryTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/Locations/ProvinceTransformer.txt'  => app_path('Transformers/Locations/ProvinceTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/Locations/CityTransformer.txt'     => app_path('Transformers/Locations/CityTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/Locations/DistrictTransformer.txt' => app_path('Transformers/Locations/DistrictTransformer.php'),
                __DIR__ . '/_publishFiles/Transformers/Locations/VillageTransformer.txt'  => app_path('Transformers/Locations/VillageTransformer.php'),
                //seeder
                __DIR__ . '/_publishFiles/database/seeds/LocationSubdivisionSeeder.txt'   => base_path('database/seeds/LocationSubdivisionSeeder.php'),


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
