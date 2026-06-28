<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cross-Origin Resource Sharing (CORS) Configuration
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
 */
class Cors extends BaseConfig
{
    /**
     * The default CORS configuration.
     *
     * @var array{
     *      allowedOrigins: list<string>,
     *      allowedOriginsPatterns: list<string>,
     *      supportsCredentials: bool,
     *      allowedHeaders: list<string>,
     *      exposedHeaders: list<string>,
     *      allowedMethods: list<string>,
     *      maxAge: int,
     *  }
     */
    public array $default = [
        'allowedOrigins'         => ['https://gad-ams-2.vercel.app', 'https://gadams.vercel.app', 'http://localhost:5173', 'http://localhost:5174'],
        'allowedOriginsPatterns' => [
            'https://[a-zA-Z0-9\-]+-5173\.(preview\.)?app\.github\.dev',
            'http://(localhost|127\.0\.0\.1)(:\d+)?'
        ],
        'allowedHeaders'         => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'X-User-Id'],
        'allowedMethods'         => ['GET', 'POST', 'OPTIONS', 'PUT', 'DELETE'],
        'exposedHeaders'         => [],
        'maxAge'                 => 7200,
        'hosts'                  => [],
    ];
}
