<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'profiler' => true,

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', 'b1fc9364bab593622974123dd304c8d65dbc5ce7e06e7c89274f64605561a4cc'),
    ],

    'SSO' => [
        // 'baseurl' => env('SSO_BASEURL', 'https://migration.vmuv.eifaws.com'),
        'baseurl' => env('SSO_BASEURL', 'https://migration.vmdv.eifaws.com'),
        'baseentityurl' => env('SSO_ENTITYURL','https://app-eu.onelogin.com'),
        'endpointurl' => env('SSO_ENDPOINTURL','https://eif-til.onelogin.com'),
        // 'ssoserial' => env('SSO_SERIAL', '29919d0c-0cdd-4631-ab3c-4d02cc93183c'),
        'ssoserial' => env('SSO_SERIAL', '551e9524-7b8e-43f4-ab3e-72dd896c8453'),
        // 'slo' => env('SSO_SLO','405127'),
        'slo' => env('SSO_SLO','386613'),
    ],
    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'debug_kit' => [
            'host' => '127.0.0.1',
            'port' => '3306',
            'username' => 'cakephp',
            'password' => 'djkeOR58crK',
            'database' => 'damsv2',
            'url' => env('DATABASE_URL', null),
        ],

        'default' => [
            /*
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',

  
			
			// 'host' => 'eif-uat.csopdlj8hksj.eu-west-1.rds.amazonaws.com',
			'host' => '127.0.0.1',
			'username' => 'cakephp',
			// 'password' => 'jdieVIR687xREO',
			'password' => 'djkeOR58crK',
			'database' => 'damsv2',
            /*
             * If not using the default 'public' schema with the PostgreSQL driver
             * set it here.
             */
            //'schema' => 'myapp',

            /*
             * You can use a DSN string to set the entire configuration
             */
            'url' => env('DATABASE_URL', null),
        ],
        
        'analytics' => [
			// 'host' => 'eif-uat.csopdlj8hksj.eu-west-1.rds.amazonaws.com',
			'host' => '127.0.0.1',
            'username' => 'cakephp',
			// 'password' => 'jdieVIR687xREO',
			'password' => 'djkeOR58crK',
            'database' => 'analytics',
            'url' => env('DATABASE_URL', null),
        ],
        'eif' => [
			// 'host' => 'eif-uat.csopdlj8hksj.eu-west-1.rds.amazonaws.com',
			'host' => '127.0.0.1',
            'username' => 'cakephp',
			// 'password' => 'jdieVIR687xREO',
			'password' => 'djkeOR58crK',
            'database' => 'eif',
            'url' => env('DATABASE_URL', null),
        ],
        'ecb' => [
			// 'host' => 'eif-uat.csopdlj8hksj.eu-west-1.rds.amazonaws.com',
			'host' => '127.0.0.1',
            'username' => 'cakephp',
			// 'password' => 'jdieVIR687xREO',
			'password' => 'djkeOR58crK',
            'database' => 'ecb',
            'url' => env('DATABASE_URL', null),
        ],

        /*
         * The test connection is used during the test suite.
         */
        'test' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'my_app',
            'password' => 'secret',
            'database' => 'test_myapp',
            //'schema' => 'myapp',
            'url' => env('DATABASE_TEST_URL', null),
        ],
    ],

    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
