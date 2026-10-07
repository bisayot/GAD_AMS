<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Admin-Only Filter
 *
 * Runs AFTER JwtFilter has verified the token and set $request->jwtPayload.
 * Blocks anyone whose role is not 'admin' with a 403 Forbidden response.
 * Apply to routes that only the Director (admin) should access:
 *   - User suspension / restore / delete
 *   - System settings
 *   - Admin-panel-only routes
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return null;
        }

        $payload = $request->jwtPayload ?? null;

        // Payload missing means JwtFilter didn't run first (config error)
        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'message' => 'Unauthorized. [AdminFilter]']);
        }

        if (($payload['role'] ?? '') !== 'admin') {
            return service('response')
                ->setStatusCode(403)
                ->setJSON(['status' => 403, 'message' => 'Forbidden: Administrator access required.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
