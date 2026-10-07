<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Rate Limit Filter
 *
 * Applied to public auth endpoints (login, register, forgot-password).
 * Limits to 10 attempts per minute per IP address using CI4's Throttler
 * (file-based cache — no Redis or extra setup required).
 */
class RateLimitFilter implements FilterInterface
{
    // Max attempts allowed within the window
    private const MAX_ATTEMPTS = 10;
    // Time window in seconds (1 minute)
    private const WINDOW_SECONDS = 60;

    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = \Config\Services::throttler();
        $ip        = $request->getIPAddress();
        $key       = 'auth_ratelimit_' . md5($ip);

        if ($throttler->check($key, self::MAX_ATTEMPTS, self::WINDOW_SECONDS) === false) {
            $retryAfter = $throttler->getTokentime();

            return service('response')
                ->setStatusCode(429)
                ->setHeader('Retry-After', (string) $retryAfter)
                ->setJSON([
                    'status'  => 429,
                    'message' => 'Too many attempts. Please wait ' . $retryAfter . ' second(s) before trying again.',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
