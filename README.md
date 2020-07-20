## Installation
#### Use Composer to install the package:
        $ COMPOSER_MEMORY_LIMIT=-1 composer require n-singularity/general-module-foundation

## AUTH MODUL
#### Add To config/app.php
        Nsingularity\GeneralModule\Foundation\GeneralFoundationServiceProvider::class,
        LaravelDoctrine\ORM\DoctrineServiceProvider::class,
        LaravelDoctrine\Migrations\MigrationsServiceProvider::class,
        LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class,
        LaravelDoctrine\Extensions\BeberleiExtensionsServiceProvider::class,

#### Execute this command
        php artisan vendor:publish --tag=ns-module-foundation --force
      
## LOCATION MODUL
#### Add To config/app.php
        Nsingularity\GeneralModule\Foundation\LocationFoundationServiceProvider::class,

#### Execute this command
        php artisan vendor:publish --tag=ns-module-location-foundation --force

#### Register Seeder at app/databse/seeds/DatabaseSeeder.php
        $this->call(LocationSubdivisionSeeder::class);
