### Add To config/app.php
        Nsingularity\GeneralModule\Foundation\GeneralFoundationServiceProvider::class,
        LaravelDoctrine\ORM\DoctrineServiceProvider::class,
        LaravelDoctrine\Migrations\MigrationsServiceProvider::class,
        LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class,
        LaravelDoctrine\Extensions\BeberleiExtensionsServiceProvider::class,

### Execute this command
        php artisan vendor:publish --tag=ns-module-foundation --force
        php artisan vendor:publish --tag=ns-module-location-foundation --force

