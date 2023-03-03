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
            'ru' => 'Город Ташкент',
            'fargo_id' => '1216279901'
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
          'title' => 'Olmazor tumani',
          'fargo_id' => '1216494670'
        ],
        [
          'value' => 2,
          'parent_id' => 1,
          'title' => 'Bektemir tumani',
          'fargo_id' => '1216494666'
        ],
        [
          'value' => 3,
          'parent_id' => 1,
          'title' => 'Mirobod tumani',
          'fargo_id' => '1216494668'
        ],
        [
            'value' => 4,
            'parent_id' => 1,
            'title' => 'Mirzo Ulug`bek tumani',
          'fargo_id' => '1216494669'
        ],
        [
          'value' => 5,
          'parent_id' => 1,
          'title' => 'Sirg’ali tumani',
          'fargo_id' => '1216494671'
        ],
        [
            'value' => 6,
            'parent_id' => 1,
            'title' => 'Uchtepa tumani',
          'fargo_id' => '1216494673'
        ],
        [
          'value' => 7,
          'parent_id' => 1,
          'title' => 'Xamza tumani',
          'fargo_id' => '1216494675'
        ],
        [
          'value' => 8,
          'parent_id' => 1,
          'title' => 'Chilonzor tumani',
          'fargo_id' => '1216494667'
        ],
        [
          'value' => 9,
          'parent_id' => 1,
          'title' => 'Shayxontoxur tumani',
          'fargo_id' => '1216404884'
        ],
        [
          'value' => 10,
          'parent_id' => 1,
          'title' => 'Yunusobod tumani',
          'fargo_id' => '1216494676'
        ],
        [
          'value' => 11,
          'parent_id' => 1,
          'title' => 'Yakkasaroy tumani',
          'fargo_id' => '1216494672'
        ],









        [
          'value' => 12,
          'parent_id' => 2,
          'title' => 'Bekobod tumani',
          'fargo_id' => '263947468'
        ],
        [
          'value' => 13,
          'parent_id' => 2,
          'title' => 'Boʻstonliq tumani',
          'fargo_id' => '263947472'
        ],
        [
          'value' => 14,
          'parent_id' => 2,
          'title' => 'Boʻka tumani',
          'fargo_id' => '263947470'
        ],
        [
          'value' => 15,
          'parent_id' => 2,
          'title' => 'Zangiota tumani',
          'fargo_id' => '263947509'
        ],
        [
          'value' => 16,
          'parent_id' => 2,
          'title' => 'Qibray tumani',
          'fargo_id' => '263947489'
        ],
        [
          'value' => 17,
          'parent_id' => 2,
          'title' => 'Quyichirchiq tumani',
          'fargo_id' => '263947492'
        ],
        [
          'value' => 18,
          'parent_id' => 2,
          'title' => 'Parkent tumani',
          'fargo_id' => '263947485'
        ],
        [
          'value' => 19,
          'parent_id' => 2,
          'title' => 'Piskent tumani',
          'fargo_id' => '263947487'
        ],
        [
          'value' => 20,
          'parent_id' => 2,
          'title' => 'Oʻrtachirchiq tumani',
          'fargo_id' => '263947477'
        ],
        [
          'value' => 21,
          'parent_id' => 2,
          'title' => 'Chinoz tumani',
          'fargo_id' => '263947474'
        ],
        [
          'value' => 22,
          'parent_id' => 2,
          'title' => 'Yuqorichirchiq tumani',
          'fargo_id' => '263947507'
        ],
        [
          'value' => 23,
          'parent_id' => 2,
          'title' => 'Yangiyoʻl tumani',
          'fargo_id' => '263947505'
        ],
        [
          'value' => 24,
          'parent_id' => 2,
          'title' => 'Olmaliq',
          'fargo_id' => '1113695725'
        ],
        [
          'value' => 25,
          'parent_id' => 2,
          'title' => 'Angren',
          'fargo_id' => '501056439'
        ],
        [
          'value' => 26,
          'parent_id' => 2,
          'title' => 'Bekobod',
          'fargo_id' => '263947468'
        ],
        [
          'value' => 27,
          'parent_id' => 2,
          'title' => 'Chirchiq',
          'fargo_id' => '501067990'
        ],















        [
          'value' => 28,
          'parent_id' => 3,
          'title' => 'Oltinko`l tumani',
          'fargo_id' => '1295884726'
        ],
        [
          'value' => 29,
          'parent_id' => 3,
          'title' => 'Andijon tumani',
          'fargo_id' => '1125206270'
        ],
        [
          'value' => 30,
          'parent_id' => 3,
          'title' => 'Asaka tumani',
          'fargo_id' => '263947002'
        ],
        [
          'value' => 31,
          'parent_id' => 3,
          'title' => 'Baliqchi tumani',
          'fargo_id' => '263947005'
        ],
        [
          'value' => 32,
          'parent_id' => 3,
          'title' => 'Buloqboshi tumani',
          'fargo_id' => '263947009'
        ],
        [
          'value' => 33,
          'parent_id' => 3,
          'title' => 'Jaloquduq tumani',
          'fargo_id' => '263947015'
        ],
        [
          'value' => 34,
          'parent_id' => 3,
          'title' => 'Izboskan tumani',
          'fargo_id' => '263947013'
        ],
        [
          'value' => 35,
          'parent_id' => 3,
          'title' => 'Qo’rg’ontepa tumani',
          'fargo_id' => '263947038'
        ],
        [
          'value' => 36,
          'parent_id' => 3,
          'title' => 'Marhamat tumani',
          'fargo_id' => '263947028'
        ],
        [
          'value' => 37,
          'parent_id' => 3,
          'title' => 'Paxtaobod tumani',
          'fargo_id' => '263947033'
        ],
        [
          'value' => 38,
          'parent_id' => 3,
          'title' => 'Xo’jaobod tumani',
          'fargo_id' => '263947047'
        ],
        [
          'value' => 39,
          'parent_id' => 3,
          'title' => 'Shahrixon tumani',
          'fargo_id' => '263947043'
        ],
        [
          'value' => 40,
          'parent_id' => 3,
          'title' => 'Andijon shahri',
          'fargo_id' => '1125210871'
        ],
        [
          'value' => 41,
          'parent_id' => 3,
          'title' => 'Xonobod shahri',
          'fargo_id' => '1125248321'
        ],

















        [
          'value' => 42,
          'parent_id' => 4,
          'title' => 'Olot tumani',
          'fargo_id' => '263947057'
        ],
        [
          'value' => 43,
          'parent_id' => 4,
          'title' => 'Buxoro tumani',
          'fargo_id' => '263947049'
        ],
        [
          'value' => 44,
          'parent_id' => 4,
          'title' => 'Vobkent tumani',
          'fargo_id' => '263947069'
        ],
        [
          'value' => 45,
          'parent_id' => 4,
          'title' => 'G’ijduvon tumani',
          'fargo_id' => '263947051'
        ],
        [
          'value' => 46,
          'parent_id' => 4,
          'title' => 'Jondor tumani',
          'fargo_id' => '263947053'
        ],
        [
          'value' => 47,
          'parent_id' => 4,
          'title' => 'Kogon tumani',
          'fargo_id' => '263947055'
        ],
        [
          'value' => 48,
          'parent_id' => 4,
          'title' => 'Qorako’l tumani',
          'fargo_id' => '263947061'
        ],
        [
          'value' => 49,
          'parent_id' => 4,
          'title' => 'Qorovulbozor tumani',
          'fargo_id' => '263947063'
        ],
        [
          'value' => 50,
          'parent_id' => 4,
          'title' => 'Peshku tumani',
          'fargo_id' => '263947059'
        ],
        [
          'value' => 51,
          'parent_id' => 4,
          'title' => 'Romitan tumani',
          'fargo_id' => '263947065'
        ],













        [
          'value' => 52,
          'parent_id' => 5,
          'title' => 'Oltiariq tumani',
          'fargo_id' => '263947086'
        ],
        [
          'value' => 53,
          'parent_id' => 5,
          'title' => 'Bogʻdod tumani',
          'fargo_id' => '263947073'
        ],
        [
          'value' => 54,
          'parent_id' => 5,
          'title' => 'Beshariq tumani',
          'fargo_id' => '263947071'
        ],
        [
          'value' => 55,
          'parent_id' => 5,
          'title' => 'Buvayda tumani',
          'fargo_id' => '263947075'
        ],
        [
          'value' => 56,
          'parent_id' => 5,
          'title' => 'Dangʻara tumani',
          'fargo_id' => '503897289'
        ],
        [
          'value' => 57,
          'parent_id' => 5,
          'title' => 'Quva tumani',
          'fargo_id' => '263947096'
        ],
        [
          'value' => 58,
          'parent_id' => 5,
          'title' => 'Qoʻshtepa tumani',
          'fargo_id' => '1383137605'
        ],
        [
          'value' => 59,
          'parent_id' => 5,
          'title' => 'Rishton tumani',
          'fargo_id' => '263947098'
        ],
        [
          'value' => 60,
          'parent_id' => 5,
          'title' => 'Soʻx tumani',
          'fargo_id' => '263947101'
        ],
        [
          'value' => 61,
          'parent_id' => 5,
          'title' => 'Toshloq tumani',
          'fargo_id' => '1226735408'
        ],
        [
          'value' => 62,
          'parent_id' => 5,
          'title' => 'Oʻzbekiston tumani',
          'fargo_id' => '263947083'
        ],
        [
          'value' => 63,
          'parent_id' => 5,
          'title' => 'Uchkoʻprik tumani',
          'fargo_id' => '263947108'
        ],
        [
          'value' => 64,
          'parent_id' => 5,
          'title' => 'Fargʻona tumani',
          'fargo_id' => '263947079'
        ],
        [
          'value' => 65,
          'parent_id' => 5,
          'title' => 'Yozyovon tumani',
          'fargo_id' => '263947110'
        ],
        [
          'value' => 66,
          'parent_id' => 5,
          'title' => 'Qoʻqon shahri',
          'fargo_id' => '503895913'
        ],
        [
          'value' => 67,
          'parent_id' => 5,
          'title' => 'Marg’ilon shahri',
          'fargo_id' => '1216487557'
        ],
        [
          'value' => 68,
          'parent_id' => 5,
          'title' => 'Fargʻona shahri',
          'fargo_id' => '263947079'
        ],






















        [
          'value' => 69,
          'parent_id' => 6,
          'title' => 'Arnasoy tumani',
          'fargo_id' => '263947112'
        ],
        [
          'value' => 70,
          'parent_id' => 6,
          'title' => 'Baxmal tumani',
          'fargo_id' => '263947115'
        ],
        [
          'value' => 71,
          'parent_id' => 6,
          'title' => 'G`allaorol tumani',
          'fargo_id' => '263947122'
        ],
        [
          'value' => 72,
          'parent_id' => 6,
          'title' => 'Do`stlik tumani',
          'fargo_id' => '263947118'
        ],
        [
          'value' => 73,
          'parent_id' => 6,
          'title' => 'Zomin tumani',
          'fargo_id' => '263947144'
        ],
        [
          'value' => 74,
          'parent_id' => 6,
          'title' => 'Zarbdor tumani',
          'fargo_id' => '263947138'
        ],
        [
          'value' => 75,
          'parent_id' => 6,
          'title' => 'Zafarobod tumani',
          'fargo_id' => '263947135'
        ],
        [
          'value' => 76,
          'parent_id' => 6,
          'title' => 'Paxtakor tumani',
          'fargo_id' => '263947129'
        ],
        [
          'value' => 77,
          'parent_id' => 6,
          'title' => 'Forish tumani',
          'fargo_id' => '263947120'
        ],
        [
          'value' => 78,
          'parent_id' => 6,
          'title' => 'Sharof Rashidov tumani',
          'fargo_id' => '263947419'
        ],
        [
          'value' => 79,
          'parent_id' => 6,
          'title' => 'Yangiobod tumani',
          'fargo_id' => '263947133'
        ],
        [
          'value' => 80,
          'parent_id' => 6,
          'title' => 'Jizzax shahri',
          'fargo_id' => '263947124'
        ],




























        [
          'value' => 81,
          'parent_id' => 7,
          'title' => 'Kosonsoy tumani',
          'fargo_id' => '263947336'
        ],
        [
          'value' => 82,
          'parent_id' => 7,
          'title' => 'Mingbuloq tumani',
          'fargo_id' => '263947338'
        ],
        [
          'value' => 83,
          'parent_id' => 7,
          'title' => 'Namangan tumani',
          'fargo_id' => '263947340'
        ],
        [
          'value' => 84,
          'parent_id' => 7,
          'title' => 'Norin tumani',
          'fargo_id' => '263947343'
        ],
        [
          'value' => 85,
          'parent_id' => 7,
          'title' => 'Pop tumani',
          'fargo_id' => '263947345'
        ],
        [
          'value' => 86,
          'parent_id' => 7,
          'title' => 'To’raqo’rg’on tumani',
          'fargo_id' => '263947349'
        ],
        [
          'value' => 87,
          'parent_id' => 7,
          'title' => 'Uychi tumani',
          'fargo_id' => '263947353'
        ],
        [
          'value' => 88,
          'parent_id' => 7,
          'title' => 'Chortoq tumani',
          'fargo_id' => '263947331'
        ],

        [
          'value' => 89,
          'parent_id' => 7,
          'title' => 'Chust tumani',
          'fargo_id' => '263947333'
        ],
        [
          'value' => 90,
          'parent_id' => 7,
          'title' => 'Yangiqo’rg’on tumani',
          'fargo_id' => '263947355'
        ],
        [
          'value' => 91,
          'parent_id' => 7,
          'title' => 'Namangan',
          'fargo_id' => '263947340'
        ],


























        [
          'value' => 92,
          'parent_id' => 8,
          'title' => 'Konimex tumani',
          'fargo_id' => '263947360'
        ],
        [
          'value' => 93,
          'parent_id' => 8,
          'title' => 'Karmana tumani',
          'fargo_id' => '263947357'
        ],
        [
          'value' => 94,
          'parent_id' => 8,
          'title' => 'Qiziltepa tumani',
          'fargo_id' => '263947369'
        ],
        [
          'value' => 95,
          'parent_id' => 8,
          'title' => 'Navbahor tumani',
          'fargo_id' => '501503052'
        ],
        [
          'value' => 96,
          'parent_id' => 8,
          'title' => 'Tomdi tumani',
          'fargo_id' => '263947373'
        ],
        [
          'value' => 97,
          'parent_id' => 8,
          'title' => 'Uchquduq tumani',
          'fargo_id' => '263947376'
        ],
        [
          'value' => 98,
          'parent_id' => 8,
          'title' => 'Xatirchi tumani',
          'fargo_id' => '263947378'
        ],
        [
          'value' => 99,
          'parent_id' => 8,
          'title' => 'Zarafshon',
          'fargo_id' => '501057523'
        ],
        [
          'value' => 100,
          'parent_id' => 8,
          'title' => 'Navoiy',
          'fargo_id' => '501053511'
        ],
















        [
          'value' => 101,
          'parent_id' => 9,
          'title' => 'G’uzor tumani',
          'fargo_id' => '263947218'
        ],
        [
          'value' => 102,
          'parent_id' => 9,
          'title' => 'Dehqonobod tumani',
          'fargo_id' => '263947216'
        ],
        [
          'value' => 103,
          'parent_id' => 9,
          'title' => 'Qamashi tumani',
          'fargo_id' => '263947231'
        ],
        [
          'value' => 104,
          'parent_id' => 9,
          'title' => 'Qarshi tumani',
          'fargo_id' => '263947233'
        ],
        [
          'value' => 105,
          'parent_id' => 9,
          'title' => 'Koson tumani',
          'fargo_id' => '263947225'
        ],
        [
          'value' => 106,
          'parent_id' => 9,
          'title' => 'Kasbi tumani',
          'fargo_id' => '263947220'
        ],
        [
          'value' => 107,
          'parent_id' => 9,
          'title' => 'Kitob tumani',
          'fargo_id' => '263947223'
        ],
        [
          'value' => 108,
          'parent_id' => 9,
          'title' => 'Mirishkor tumani',
          'fargo_id' => '1383137604'
        ],
        [
          'value' => 109,
          'parent_id' => 9,
          'title' => 'Nishon tumani',
          'fargo_id' => '263947229'
        ],
        [
          'value' => 110,
          'parent_id' => 9,
          'title' => 'Shahrisabz tumani',
          'fargo_id' => '263947235'
        ],
        [
          'value' => 111,
          'parent_id' => 9,
          'title' => 'Chiroqchi tumani',
          'fargo_id' => '263947214'
        ],
        [
          'value' => 112,
          'parent_id' => 9,
          'title' => 'Yakkabog’ tumani',
          'fargo_id' => '263947250'
        ],
        [
          'value' => 113,
          'parent_id' => 9,
          'title' => 'Qarshi',
          'fargo_id' => '263947233'
        ],


















        [
          'value' => 114,
          'parent_id' => 10,
          'title' => 'Amudaryo tumani',
          'fargo_id' => '263947147'
        ],
        [
          'value' => 115,
          'parent_id' => 10,
          'title' => 'Beruniy tumani',
          'fargo_id' => '263947171'
        ],
        [
          'value' => 116,
          'parent_id' => 10,
          'title' => 'Qoraoʻzak tumani',
          'fargo_id' => '263947202'
        ],
        [
          'value' => 117,
          'parent_id' => 10,
          'title' => 'Kegeyli tumani',
          'fargo_id' => '263947188'
        ],
        [
          'value' => 118,
          'parent_id' => 10,
          'title' => 'Qoʻngʻirot tumani',
          'fargo_id' => '263947200'
        ],
        [
          'value' => 119,
          'parent_id' => 10,
          'title' => 'Qanlikoʻl tumani',
          'fargo_id' => '263947197'
        ],
        [
          'value' => 120,
          'parent_id' => 10,
          'title' => 'Moʻynoq tumani',
          'fargo_id' => '263947190'
        ],
        [
          'value' => 121,
          'parent_id' => 10,
          'title' => 'Taxiatosh tumani',
          'fargo_id' => '1383137607'
        ],
        [
          'value' => 122,
          'parent_id' => 10,
          'title' => 'Taxtakoʻpir tumani',
          'fargo_id' => '263947208'
        ],
        [
          'value' => 123,
          'parent_id' => 10,
          'title' => 'Toʻrtkoʻl tumani',
          'fargo_id' => '263947210'
        ],
        [
          'value' => 124,
          'parent_id' => 10,
          'title' => 'Xoʻjayli tumani',
          'fargo_id' => '263947212'
        ],
        [
          'value' => 125,
          'parent_id' => 10,
          'title' => 'Chimboy tumani',
          'fargo_id' => '263947174'
        ],
        [
          'value' => 126,
          'parent_id' => 10,
          'title' => 'Shumanay tumani',
          'fargo_id' => '263947206'
        ],
        [
          'value' => 127,
          'parent_id' => 10,
          'title' => 'Ellikqala tumani',
          'fargo_id' => '263947177'
        ],
        [
          'value' => 128,
          'parent_id' => 10,
          'title' => 'Nukus',
          'fargo_id' => '263947194'
        ],













        [
          'value' => 129,
          'parent_id' => 11,
          'title' => 'Oqdaryo tumani',
          'fargo_id' => '263947393'
        ],
        [
          'value' => 130,
          'parent_id' => 11,
          'title' => 'Bulung’ur tumani',
          'fargo_id' => '263947380'
        ],
        [
          'value' => 131,
          'parent_id' => 11,
          'title' => 'Jomboy tumani',
          'fargo_id' => '263947385'
        ],
        [
          'value' => 132,
          'parent_id' => 11,
          'title' => 'Ishtixon tumani',
          'fargo_id' => '263947383'
        ],
        [
          'value' => 133,
          'parent_id' => 11,
          'title' => 'Kattaqoʻrgʻon tumani',
          'fargo_id' => '263947387'
        ],
        [
          'value' => 134,
          'parent_id' => 11,
          'title' => 'Qoʻshrabot tumani',
          'fargo_id' => '263947401'
        ],
        [
          'value' => 135,
          'parent_id' => 11,
          'title' => 'Narpay tumani',
          'fargo_id' => '263947389'
        ],
        [
          'value' => 136,
          'parent_id' => 11,
          'title' => 'Nurobod tumani',
          'fargo_id' => '263947391'
        ],
        [
          'value' => 137,
          'parent_id' => 11,
          'title' => 'Payariq tumani',
          'fargo_id' => '263947399'
        ],
        [
          'value' => 138,
          'parent_id' => 11,
          'title' => 'Pastdargʻom tumani',
          'fargo_id' => '263947395'
        ],
        [
          'value' => 139,
          'parent_id' => 11,
          'title' => 'Paxtachi tumani',
          'fargo_id' => '263947397'
        ],
        [
          'value' => 140,
          'parent_id' => 11,
          'title' => 'Samarqand tumani',
          'fargo_id' => '263947403'
        ],
        [
          'value' => 141,
          'parent_id' => 11,
          'title' => 'Toyloq tumani',
          'fargo_id' => '263947405'
        ],
        [
          'value' => 142,
          'parent_id' => 11,
          'title' => 'Urgut tumani',
          'fargo_id' => '263947407'
        ],























        [
          'value' => 143,
          'parent_id' => 12,
          'title' => 'Oqoltin tumani',
          'fargo_id' => '263947415'
        ],
        [
          'value' => 144,
          'parent_id' => 12,
          'title' => 'Boyovut tumani',
          'fargo_id' => '263947409'
        ],
        [
          'value' => 145,
          'parent_id' => 12,
          'title' => 'Guliston tumani',
          'fargo_id' => '263947411'
        ],
        [
          'value' => 146,
          'parent_id' => 12,
          'title' => 'Mirzaobod tumani',
          'fargo_id' => '263947413'
        ],
        [
          'value' => 147,
          'parent_id' => 12,
          'title' => 'Sayxunobod tumani',
          'fargo_id' => '263947417'
        ],
        [
          'value' => 148,
          'parent_id' => 12,
          'title' => 'Sardoba tumani',
          'fargo_id' => '1124551016'
        ],
        [
          'value' => 149,
          'parent_id' => 12,
          'title' => 'Sirdaryo tumani',
          'fargo_id' => '263947426'
        ],
        [
          'value' => 150,
          'parent_id' => 12,
          'title' => 'Xovos tumani',
          'fargo_id' => '1124655691'
        ],
        [
          'value' => 151,
          'parent_id' => 12,
          'title' => 'Shirin',
          'fargo_id' => '1124630236'
        ],
        [
          'value' => 152,
          'parent_id' => 12,
          'title' => 'Yangiyer',
          'fargo_id' => '1124633996'
        ],

















        [
          'value' => 153,
          'parent_id' => 13,
          'title' => 'Oltinsoy tumani',
          'fargo_id' => '263947445'
        ],
        [
          'value' => 154,
          'parent_id' => 13,
          'title' => 'Angor tumani',
          'fargo_id' => '263947430'
        ],
        [
          'value' => 155,
          'parent_id' => 13,
          'title' => 'Boysun tumani',
          'fargo_id' => '263947437'
        ],
        [
          'value' => 156,
          'parent_id' => 13,
          'title' => 'Bandihon tumani',
          'fargo_id' => '263947433'
        ],
        [
          'value' => 157,
          'parent_id' => 13,
          'title' => 'Denov tuman',
          'fargo_id' => '263947439'
        ],
        [
          'value' => 158,
          'parent_id' => 13,
          'title' => 'Jarqoʻrgʻon tumani',
          'fargo_id' => '263947441'
        ],
        [
          'value' => 159,
          'parent_id' => 13,
          'title' => 'Qiziriq tumani',
          'fargo_id' => '263947448'
        ],
        [
          'value' => 160,
          'parent_id' => 13,
          'title' => 'Qumqoʻrgʻon tumani',
          'fargo_id' => '263947456'
        ],
        [
          'value' => 161,
          'parent_id' => 13,
          'title' => 'Muzrabot tumani',
          'fargo_id' => '263947443'
        ],
        [
          'value' => 162,
          'parent_id' => 13,
          'title' => 'Sariosiyo tumani',
          'fargo_id' => '263947458'
        ],
        [
          'value' => 163,
          'parent_id' => 13,
          'title' => 'Termiz tumani',
          'fargo_id' => '263947464'
        ],
        [
          'value' => 164,
          'parent_id' => 13,
          'title' => 'Uzun tumani',
          'fargo_id' => '263947466'
        ],

        [
          'value' => 165,
          'parent_id' => 13,
          'title' => 'Sherobod tumani',
          'fargo_id' => '263947460'
        ],

        [
          'value' => 166,
          'parent_id' => 13,
          'title' => 'Shoʻrchi tumani',
          'fargo_id' => '263947462'
        ],



















        [
          'value' => 167,
          'parent_id' => 14,
          'title' => 'Bogʻot tumani',
          'fargo_id' => '263947253'
        ],
        [
          'value' => 168,
          'parent_id' => 14,
          'title' => 'Gurlan tumani',
          'fargo_id' => '263947258'
        ],
        [
          'value' => 169,
          'parent_id' => 14,
          'title' => 'Qoʻshkoʻpir tumani',
          'fargo_id' => '263947277'
        ],
        [
          'value' => 170,
          'parent_id' => 14,
          'title' => 'Urganch tumani',
          'fargo_id' => '263947299'
        ],
        [
          'value' => 171,
          'parent_id' => 14,
          'title' => 'Hazorasp tumani',
          'fargo_id' => '263947267'
        ],
        [
          'value' => 172,
          'parent_id' => 14,
          'title' => 'Xonqa tumani',
          'fargo_id' => '263947319'
        ],
        [
          'value' => 173,
          'parent_id' => 14,
          'title' => 'Xiva tumani',
          'fargo_id' => '263947311'
        ],
        [
          'value' => 174,
          'parent_id' => 14,
          'title' => 'Shovot tumani',
          'fargo_id' => '263947287'
        ],
        [
          'value' => 175,
          'parent_id' => 14,
          'title' => 'Yangiariq tumani',
          'fargo_id' => '263947327'
        ],
        [
          'value' => 176,
          'parent_id' => 14,
          'title' => 'Yangibozor tumani',
          'fargo_id' => '263947329'
        ],
    ],

];
