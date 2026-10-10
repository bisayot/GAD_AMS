<?php

namespace App\Filters;

use App\Libraries\JwtHelper;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * JWT Auth Filter
 *
 * Apply this filter to any route that requires a logged-in user.
 * Reads the Bearer token from the Authorization header, verifies it,
 * and injects the decoded payload into the request so controllers
 * can read the real user id / role from the token — not from a
 * client-supplied header.
 */
class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Allow OPTIONS preflight requests through (handled by CORS filter)
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return null;
        }

        $authHeader = $request->getHeaderLine('Authorization');
        $token = null;

        if (!empty($authHeader) && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7); // strip "Bearer "
        } elseif (!empty($request->getGet('token'))) {
            $token = $request->getGet('token');
        }

        if (empty($token)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'message' => 'Unauthorized: No token provided.']);
        }
        $payload = JwtHelper::verify($token);

        if ($payload === null) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'message' => 'Unauthorized: Token is invalid or expired.']);
        }

        // ----------------------------------------------------------------
        // Inject verified identity into a global so controllers can read
        // the REAL user id and role from the signed token — never from
        // a spoofable client header.
        // ----------------------------------------------------------------
        $request->jwtPayload = $payload;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
