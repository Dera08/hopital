  <?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'patients' => [
            'driver' => 'session',
            'provider' => 'patients',
        ],
    ],

    'providers' => [
       'users' => [
          'driver' => 'eloquent',
           'model' => App\Models\User::class,
        ],

       'patients' => [
          'driver' => 'patient', // ✅ Utiliser le provider personnalisé
          'model' => App\Models\Patient::class,
        ], 
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'patients' => [
            'provider' => 'patients',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    

    'password_timeout' => 10800,


];