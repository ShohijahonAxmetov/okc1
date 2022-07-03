<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Tashkent',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Js' => Illuminate\Support\Js::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Image' => Intervention\Image\Facades\Image::class,

    ],

    // constants
    'REGIONS' => [
        [
            'value' => 1,
            'uz' => 'Toshkent shahri',
            'ru' => 'Город Ташкент'
        ],
        [   
            'value' => 2,
            'uz' => 'Toshkent viloyati',
            'ru' => 'Ташкентская область'
        ],
        [   
            'value' => 3,
            'uz' => 'Andijon',
            'ru' => 'Андижанская область'
        ],
        [   
            'value' => 4,
            'uz' => 'Buxoro',
            'ru' => 'Бухарская область'
        ],
        [   
            'value' => 5,
            'uz' => 'Fargona',
            'ru' => 'Ферганская область'
        ],
        [   
            'value' => 6,
            'uz' => 'Jizzax',
            'ru' => 'Джизакская область'
        ],
        [   
            'value' => 7,
            'uz' => 'Namangan',
            'ru' => 'Наманганская область'
        ],
        [   
            'value' => 8,
            'uz' => 'Navoiy',
            'ru' => 'Навоийская область'
        ],
        [   
            'value' => 9,
            'uz' => 'Qashqadaryo',
            'ru' => 'Кашкадарьинская область'
        ],
        [   
            'value' => 10,
            'uz' => 'Qoraqalpog’iston',
            'ru' => 'Республика Каракалпакстан'
        ],
        [   
            'value' => 11,
            'uz' => 'Samarqand',
            'ru' => 'Самаркандская область'
        ],
        [   
            'value' => 12,
            'uz' => 'Sirdaryo',
            'ru' => 'Сырдарьинская область'
        ],
        [   
            'value' => 13,
            'uz' => 'Xorazm',
            'ru' => 'Хорезмская область'
        ],
        [   
            'value' => 14,
            'uz' => 'Surxondaryo',
            'ru' => 'Сурхандарьинская область'
        ]
    ],

    'DISTRICTS' => [
        [
          'value' => 1,
          'parent_id' => 1,
          'title' => 'Olmazor tumani'
        ],
        [
          'value' => 2,
          'parent_id' => 1,
          'title' => 'Bektemir tumani'
        ],
        [
          'value' => 3,
          'parent_id' => 1,
          'title' => 'Mirobod tumani'
        ],
        [
            'value' => 4,
            'parent_id' => 1,
            'title' => 'Mirzo Ulug`bek tumani'
        ],
        [
          'value' => 5,
          'parent_id' => 1,
          'title' => 'Sirg’ali tumani'
        ],
        [
            'value' => 6,
            'parent_id' => 1,
            'title' => 'Uchtepa tumani'
        ],
        [
          'value' => 7,
          'parent_id' => 1,
          'title' => 'Xamza tumani'
        ],
        [
          'value' => 8,
          'parent_id' => 1,
          'title' => 'Chilonzor tumani'
        ],
        [
          'value' => 9,
          'parent_id' => 1,
          'title' => 'Shayxontoxur tumani'
        ],
        [
          'value' => 10,
          'parent_id' => 1,
          'title' => 'Yunusobod tumani'
        ],
        [
          'value' => 11,
          'parent_id' => 1,
          'title' => 'Yakkasaroy tumani'
        ],
        [
          'value' => 12,
          'parent_id' => 2,
          'title' => 'Yakkasaroy tumani'
        ],
        [
          'value' => 13,
          'parent_id' => 2,
          'title' => 'Bekobod tumani'
        ],
        [
          'value' => 14,
          'parent_id' => 2,
          'title' => 'Boʻstonliq tumani'
        ],
        [
          'value' => 15,
          'parent_id' => 2,
          'title' => 'Boʻka tumani'
        ],
        [
          'value' => 16,
          'parent_id' => 2,
          'title' => 'Zangiota tumani'
        ],
        [
          'value' => 17,
          'parent_id' => 2,
          'title' => 'Qibray tumani'
        ],
        [
          'value' => 18,
          'parent_id' => 2,
          'title' => 'Quyichirchiq tumani'
        ],
        [
          'value' => 19,
          'parent_id' => 2,
          'title' => 'Parkent tumani'
        ],
        [
          'value' => 20,
          'parent_id' => 2,
          'title' => 'Piskent tumani'
        ],
        [
          'value' => 21,
          'parent_id' => 2,
          'title' => 'Oʻrtachirchiq tumani'
        ],
        [
          'value' => 22,
          'parent_id' => 2,
          'title' => 'Toshkent tumani'
        ],
        [
          'value' => 23,
          'parent_id' => 2,
          'title' => 'Oʻrtachirchiq tumani'
        ],
        [
          'value' => 24,
          'parent_id' => 2,
          'title' => 'Chinoz tumani'
        ],
        [
          'value' => 25,
          'parent_id' => 2,
          'title' => 'Yuqorichirchiq tumani'
        ],
        [
          'value' => 26,
          'parent_id' => 2,
          'title' => 'Yangiyoʻl tumani'
        ],
        [
          'value' => 27,
          'parent_id' => 2,
          'title' => 'Olmaliq'
        ],
        [
          'value' => 28,
          'parent_id' => 2,
          'title' => 'Angren'
        ],
        [
          'value' => 29,
          'parent_id' => 2,
          'title' => 'Bekobod'
        ],
        [
          'value' => 30,
          'parent_id' => 2,
          'title' => 'Chirchiq'
        ],

        [
          'value' => 31,
          'parent_id' => 3,
          'title' => 'Oltinko`l tumani'
        ],
        [
          'value' => 32,
          'parent_id' => 3,
          'title' => 'Andijon tumani'
        ],
        [
          'value' => 33,
          'parent_id' => 3,
          'title' => 'Asaka tumani'
        ],
        [
          'value' => 34,
          'parent_id' => 3,
          'title' => 'Baliqchi tumani'
        ],
        [
          'value' => 35,
          'parent_id' => 3,
          'title' => 'Bo’z tumani'
        ],
        [
          'value' => 36,
          'parent_id' => 3,
          'title' => 'Buloqboshi tumani'
        ],
        [
          'value' => 37,
          'parent_id' => 3,
          'title' => 'Jalaquduq tumani'
        ],
        [
          'value' => 38,
          'parent_id' => 3,
          'title' => 'Izboskan tumani'
        ],
        [
          'value' => 39,
          'parent_id' => 3,
          'title' => 'Qo’rg’ontepa tumani'
        ],
        [
          'value' => 40,
          'parent_id' => 3,
          'title' => 'Marhamat tumani'
        ],
        [
          'value' => 41,
          'parent_id' => 3,
          'title' => 'Paxtaobod tumani'
        ],
        [
          'value' => 42,
          'parent_id' => 3,
          'title' => 'Qo’rg’ontepa tumani'
        ],
        [
          'value' => 43,
          'parent_id' => 3,
          'title' => 'Xo’jaobod tumani'
        ],
        [
          'value' => 44,
          'parent_id' => 3,
          'title' => 'Shahrixon tumani'
        ],
        [
          'value' => 45,
          'parent_id' => 3,
          'title' => 'Andijon shahri'
        ],
        [
          'value' => 46,
          'parent_id' => 3,
          'title' => 'Xonobod shahri'
        ],
        [
          'value' => 47,
          'parent_id' => 4,
          'title' => 'Olot tumani'
        ],
        [
          'value' => 48,
          'parent_id' => 4,
          'title' => 'Buxoro tumani'
        ],
        [
          'value' => 49,
          'parent_id' => 4,
          'title' => 'Vobkent tumani'
        ],
        [
          'value' => 50,
          'parent_id' => 4,
          'title' => 'G’ijduvon tumani'
        ],
        [
          'value' => 51,
          'parent_id' => 4,
          'title' => 'Jondor tumani'
        ],
        [
          'value' => 52,
          'parent_id' => 4,
          'title' => 'Kogon tumani'
        ],
        [
          'value' => 53,
          'parent_id' => 4,
          'title' => 'Jondor tumani'
        ],
        [
          'value' => 54,
          'parent_id' => 4,
          'title' => 'Qorako’l tumani'
        ],
        [
          'value' => 55,
          'parent_id' => 4,
          'title' => 'Qorovulbozor tumani'
        ],
        [
          'value' => 56,
          'parent_id' => 4,
          'title' => 'Peshku tumani'
        ],
        [
          'value' => 57,
          'parent_id' => 4,
          'title' => 'Romitan tumani'
        ],
        [
          'value' => 58,
          'parent_id' => 4,
          'title' => '  Buxoro shahri'
        ],
        [
          'value' => 59,
          'parent_id' => 4,
          'title' => 'Kogon shahri'
        ],
        [
          'value' => 60,
          'parent_id' => 5,
          'title' => 'Oltiariq tumani'
        ],
        [
          'value' => 61,
          'parent_id' => 5,
          'title' => 'Bagʻdod tumani'
        ],
        [
          'value' => 62,
          'parent_id' => 5,
          'title' => 'Beshariq tumani'
        ],
        [
          'value' => 63,
          'parent_id' => 5,
          'title' => 'Buvayda tumani'
        ],
        [
          'value' => 64,
          'parent_id' => 5,
          'title' => 'Dangʻara tumani'
        ],
        [
          'value' => 65,
          'parent_id' => 5,
          'title' => 'Quva tumani'
        ],
        [
          'value' => 66,
          'parent_id' => 5,
          'title' => 'Qoʻshtepa tumani'
        ],
        [
          'value' => 67,
          'parent_id' => 5,
          'title' => 'Rishton tumani'
        ],
        [
          'value' => 68,
          'parent_id' => 5,
          'title' => 'Soʻx tumani'
        ],
        [
          'value' => 79,
          'parent_id' => 5,
          'title' => 'Toshloq tumani'
        ],
        [
          'value' => 70,
          'parent_id' => 5,
          'title' => 'Oʻzbekiston tumani'
        ],
        [
          'value' => 71,
          'parent_id' => 5,
          'title' => 'Uchkoʻprik tumani'
        ],
        [
          'value' => 72,
          'parent_id' => 5,
          'title' => 'Fargʻona tumani'
        ],
        [
          'value' => 73,
          'parent_id' => 5,
          'title' => 'Yozyovon tumani'
        ],
        [
          'value' => 74,
          'parent_id' => 5,
          'title' => 'Qoʻqon shahri'
        ],
        [
          'value' => 75,
          'parent_id' => 5,
          'title' => 'Quvasoy shahri'
        ],
        [
          'value' => 76,
          'parent_id' => 5,
          'title' => 'Marg’ilon shahri'
        ],
        [
          'value' => 77,
          'parent_id' => 5,
          'title' => 'Fargʻona shahri'
        ],
        [
          'value' => 78,
          'parent_id' => 6,
          'title' => 'Arnasoy tumani'
        ],
        [
          'value' => 79,
          'parent_id' => 6,
          'title' => 'Baxmal tumani'
        ],
        [
          'value' => 80,
          'parent_id' => 6,
          'title' => 'G`allaorol tumani'
        ],
        [
          'value' => 81,
          'parent_id' => 6,
          'title' => 'Do`stlik tumani'
        ],
        [
          'value' => 82,
          'parent_id' => 6,
          'title' => 'Zomin tumani'
        ],
        [
          'value' => 83,
          'parent_id' => 6,
          'title' => 'Zarbdor tumani'
        ],
        [
          'value' => 84,
          'parent_id' => 6,
          'title' => 'Zafarobod tumani'
        ],
        [
          'value' => 85,
          'parent_id' => 6,
          'title' => 'Paxtakor tumani'
        ],
        [
          'value' => 86,
          'parent_id' => 6,
          'title' => 'Forish tumani'
        ],
        [
          'value' => 87,
          'parent_id' => 6,
          'title' => 'Sharof Rashidov tumani'
        ],
        [
          'value' => 88,
          'parent_id' => 6,
          'title' => 'Yangiobod tumani'
        ],
        [
          'value' => 89,
          'parent_id' => 6,
          'title' => 'Jizzax shahri'
        ],
        [
          'value' => 90,
          'parent_id' => 7,
          'title' => 'Kosonsoy tumani'
        ],
        [
          'value' => 91,
          'parent_id' => 7,
          'title' => 'Mingbuloq tumani'
        ],
        [
          'value' => 92,
          'parent_id' => 7,
          'title' => 'Namangan tumani'
        ],
        [
          'value' => 93,
          'parent_id' => 7,
          'title' => 'Norin tumani'
        ],
        [
          'value' => 94,
          'parent_id' => 7,
          'title' => 'Pop tumani'
        ],
        [
          'value' => 95,
          'parent_id' => 7,
          'title' => 'To’raqo’rg’on tumani'
        ],
        [
          'value' => 96,
          'parent_id' => 7,
          'title' => 'Uychi tumani'
        ],
        [
          'value' => 97,
          'parent_id' => 7,
          'title' => 'Chortoq tumani'
        ],

        [
          'value' => 98,
          'parent_id' => 7,
          'title' => 'Chust tumani'
        ],
        [
          'value' => 99,
          'parent_id' => 7,
          'title' => 'Yangiqo’rg’on tumani'
        ],
        [
          'value' => 100,
          'parent_id' => 7,
          'title' => 'Namangan'
        ],
        [
          'value' => 101,
          'parent_id' => 8,
          'title' => 'Konimex tumani'
        ],
        [
          'value' => 102,
          'parent_id' => 8,
          'title' => 'Karman tumani'
        ],
        [
          'value' => 103,
          'parent_id' => 8,
          'title' => 'Qiziltepa tumani'
        ],
        [
          'value' => 104,
          'parent_id' => 8,
          'title' => 'Navbahor tumani'
        ],
        [
          'value' => 105,
          'parent_id' => 8,
          'title' => 'Tomdi tumani'
        ],
        [
          'value' => 106,
          'parent_id' => 8,
          'title' => 'Uchquduq tumani'
        ],
        [
          'value' => 107,
          'parent_id' => 8,
          'title' => 'Xatirchi tumani'
        ],
        [
          'value' => 108,
          'parent_id' => 8,
          'title' => 'Zarafshon'
        ],
        [
          'value' => 109,
          'parent_id' => 8,
          'title' => 'Navoiy'
        ],
        [
          'value' => 110,
          'parent_id' => 9,
          'title' => 'G’uzor tumani'
        ],
        [
          'value' => 111,
          'parent_id' => 9,
          'title' => 'Dehqonobod tumani'
        ],
        [
          'value' => 112,
          'parent_id' => 9,
          'title' => 'Qamashi tumani'
        ],
        [
          'value' => 113,
          'parent_id' => 9,
          'title' => 'Qarshi tumani'
        ],
        [
          'value' => 114,
          'parent_id' => 9,
          'title' => 'Koson tumani'
        ],
        [
          'value' => 115,
          'parent_id' => 9,
          'title' => 'Kasbi tumani'
        ],
        [
          'value' => 116,
          'parent_id' => 9,
          'title' => 'Kitob tumani'
        ],
        [
          'value' => 117,
          'parent_id' => 9,
          'title' => 'Mirishkor tumani'
        ],
        [
          'value' => 118,
          'parent_id' => 9,
          'title' => 'Nishon tumani'
        ],
        [
          'value' => 119,
          'parent_id' => 9,
          'title' => 'Shahrisabz tumani'
        ],
        [
          'value' => 120,
          'parent_id' => 9,
          'title' => 'Chiroqchi tumani'
        ],
        [
          'value' => 121,
          'parent_id' => 9,
          'title' => 'Yakkabog’ tumani'
        ],
        [
          'value' => 122,
          'parent_id' => 9,
          'title' => 'Qarshi'
        ],
        [
          'value' => 123,
          'parent_id' => 10,
          'title' => 'Amudaryo tumani'
        ],
        [
          'value' => 124,
          'parent_id' => 10,
          'title' => 'Beruniy tumani'
        ],
        [
          'value' => 125,
          'parent_id' => 10,
          'title' => 'Qoraoʻzak tumani'
        ],
        [
          'value' => 126,
          'parent_id' => 10,
          'title' => 'Kegeyli tumani'
        ],
        [
          'value' => 127,
          'parent_id' => 10,
          'title' => 'Qoʻngʻirot tumani'
        ],
        [
          'value' => 128,
          'parent_id' => 10,
          'title' => 'Qanlikoʻl tumani'
        ],
        [
          'value' => 129,
          'parent_id' => 10,
          'title' => 'Moʻynoq tumani'
        ],
        [
          'value' => 130,
          'parent_id' => 10,
          'title' => 'Taxiatosh tumani'
        ],
        [
          'value' => 131,
          'parent_id' => 10,
          'title' => 'Taxtakoʻpir tumani'
        ],
        [
          'value' => 132,
          'parent_id' => 10,
          'title' => 'Toʻrtkoʻl tumani'
        ],
        [
          'value' => 133,
          'parent_id' => 10,
          'title' => 'Xoʻjayli tumani'
        ],
        [
          'value' => 134,
          'parent_id' => 10,
          'title' => 'Chimboy tumani'
        ],
        [
          'value' => 135,
          'parent_id' => 10,
          'title' => 'Shumanay tumani'
        ],
        [
          'value' => 136,
          'parent_id' => 10,
          'title' => 'Ellikqala tumani'
        ],
        [
          'value' => 137,
          'parent_id' => 10,
          'title' => 'Nukus'
        ],
        [
          'value' => 138,
          'parent_id' => 11,
          'title' => 'Oqdaryo tumani'
        ],
        [
          'value' => 139,
          'parent_id' => 11,
          'title' => 'Bulung’ur tumani'
        ],
        [
          'value' => 140,
          'parent_id' => 11,
          'title' => 'Jomboy tumani'
        ],
        [
          'value' => 141,
          'parent_id' => 11,
          'title' => 'Ishtixon tumani'
        ],
        [
          'value' => 142,
          'parent_id' => 11,
          'title' => 'Kattaqoʻrgʻon tumani'
        ],
        [
          'value' => 143,
          'parent_id' => 11,
          'title' => 'Qoʻshrabot tumani'
        ],
        [
          'value' => 144,
          'parent_id' => 11,
          'title' => 'Narpay tumani'
        ],
        [
          'value' => 145,
          'parent_id' => 11,
          'title' => 'Nurobod tumani'
        ],
        [
          'value' => 146,
          'parent_id' => 11,
          'title' => 'Payariq tumani'
        ],
        [
          'value' => 147,
          'parent_id' => 11,
          'title' => 'Pastdargʻom tumani'
        ],
        [
          'value' => 148,
          'parent_id' => 11,
          'title' => 'Paxtachi tumani'
        ],
        [
          'value' => 149,
          'parent_id' => 11,
          'title' => 'Samarqand tumani'
        ],
        [
          'value' => 150,
          'parent_id' => 11,
          'title' => 'Toyloq tumani'
        ],
        [
          'value' => 151,
          'parent_id' => 11,
          'title' => 'Urgut tumani'
        ],
        [
          'value' => 152,
          'parent_id' => 11,
          'title' => 'Kattaqo’rg’on'
        ],
        [
          'value' => 153,
          'parent_id' => 11,
          'title' => 'Samarqand'
        ],
        [
          'value' => 154,
          'parent_id' => 12,
          'title' => 'Oqoltin tumani'
        ],
        [
          'value' => 155,
          'parent_id' => 12,
          'title' => 'Boyovut tumani'
        ],
        [
          'value' => 156,
          'parent_id' => 12,
          'title' => 'Guliston tumani'
        ],
        [
          'value' => 157,
          'parent_id' => 12,
          'title' => 'Mirzaobod tumani'
        ],
        [
          'value' => 158,
          'parent_id' => 12,
          'title' => 'Sayxunobod tumani'
        ],
        [
          'value' => 159,
          'parent_id' => 12,
          'title' => 'Sardoba tumani'
        ],
        [
          'value' => 160,
          'parent_id' => 12,
          'title' => 'Sirdaryo tumani'
        ],
        [
          'value' => 161,
          'parent_id' => 12,
          'title' => 'Xovos tumani'
        ],
        [
          'value' => 162,
          'parent_id' => 12,
          'title' => 'Guliston'
        ],
        [
          'value' => 163,
          'parent_id' => 12,
          'title' => 'Shirin'
        ],
        [
          'value' => 164,
          'parent_id' => 12,
          'title' => 'Yangiyer'
        ],
        [
          'value' => 165,
          'parent_id' => 13,
          'title' => 'Oltinsoy tumani'
        ],
        [
          'value' => 166,
          'parent_id' => 13,
          'title' => 'Angor tumani'
        ],
        [
          'value' => 167,
          'parent_id' => 13,
          'title' => 'Boysun tumani'
        ],
        [
          'value' => 168,
          'parent_id' => 13,
          'title' => 'Bandihon tumani'
        ],
        [
          'value' => 169,
          'parent_id' => 13,
          'title' => 'Denov tuman'
        ],
        [
          'value' => 170,
          'parent_id' => 13,
          'title' => 'Jarqoʻrgʻon tumani'
        ],
        [
          'value' => 171,
          'parent_id' => 13,
          'title' => 'Qiziriq tumani'
        ],
        [
          'value' => 172,
          'parent_id' => 13,
          'title' => 'Qumqoʻrgʻon tumani'
        ],
        [
          'value' => 173,
          'parent_id' => 13,
          'title' => 'Muzrabot tumani'
        ],
        [
          'value' => 174,
          'parent_id' => 13,
          'title' => 'Sariosiyo tumani'
        ],
        [
          'value' => 175,
          'parent_id' => 13,
          'title' => 'Termiz tumani'
        ],
        [
          'value' => 176,
          'parent_id' => 13,
          'title' => 'Uzun tumani'
        ],

        [
          'value' => 177,
          'parent_id' => 13,
          'title' => 'Sherobod tumani'
        ],

        [
          'value' => 178,
          'parent_id' => 13,
          'title' => 'Shoʻrchi tumani'
        ],

        [
          'value' => 179,
          'parent_id' => 13,
          'title' => 'Termiz'
        ],

        [
          'value' => 180,
          'parent_id' => 14,
          'title' => 'Bogʻot tumani'
        ],

        [
          'value' => 181,
          'parent_id' => 14,
          'title' => 'Gurlan tumani'
        ],
        [
          'value' => 182,
          'parent_id' => 14,
          'title' => 'Qoʻshkoʻpir tumani'
        ],
        [
          'value' => 183,
          'parent_id' => 14,
          'title' => 'Urganch tumani'
        ],
        [
          'value' => 184,
          'parent_id' => 14,
          'title' => 'Xazorasp tumani'
        ],
        [
          'value' => 185,
          'parent_id' => 14,
          'title' => 'Xonqa tumani'
        ],
        [
          'value' => 186,
          'parent_id' => 14,
          'title' => 'Xiva tumani'
        ],
        [
          'value' => 187,
          'parent_id' => 14,
          'title' => 'Shovot tumani'
        ],
        [
          'value' => 188,
          'parent_id' => 14,
          'title' => 'Yangiariq tumani'
        ],
        [
          'value' => 189,
          'parent_id' => 14,
          'title' => 'Yangibozor tumani'
        ],
        [
          'value' => 190,
          'parent_id' => 14,
          'title' => 'Urganch'
        ],
    ],

];
