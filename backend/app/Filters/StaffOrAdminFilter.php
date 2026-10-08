<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Staff-or-Admin Filter
 *
 * Runs AFTER JwtFilter has verified the token and set $request->jwtPayload.
 * Blocks anyone whose role is not 'admin' or 'gad_staff' with a 403.
 * Apply to routes that both GAD Staff and the Director can access:
 *   - Approving / rejecting documents
 *   - Viewing all submissions
 *   - Managing contact inquiries
 *   - Activity logs
 *   - User management (view + create + update — suspend/delete is admin-only)
 */
class StaffOrAdminFilter implements FilterInterface
{
    private const ALLOWED_ROLES = ['admin', 'gad_staff', 'staff', 'superadmin'];

    public function before(RequestInterface $request, $arguments = null)
    {
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return null;
        }

        $payload = $request->jwtPayload ?? null;

        if (!$payload) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['status' => 401, 'message' => 'Unauthorized. [StaffOrAdminFilter]']);
        }

        if (!in_array($payload['role'] ?? '', self::ALLOWED_ROLES, true)) {
            $method = strtoupper($request->getMethod());
            $uriPath = method_exists($request, 'getPath') ? $request->getPath() : trim($request->getUri()->getPath(), '/');

            // Allow read-only (GET) requests for system resources used globally:
            // - holidays (used by datepickers across all roles)
            // - venues (used by proposal forms across all roles)
            // - settings (used by proposal forms for baseline amounts and submission limits)
            // - plan (used by read-only college GAD plan view)
            if ($method === 'GET') {
                if (
                    str_contains($uriPath, 'holidays') ||
                    str_contains($uriPath, 'venues') ||
                    str_contains($uriPath, 'settings') ||
                    str_ends_with($uriPath, 'plan') ||
                    $uriPath === 'api/plan' ||
                    $uriPath === 'plan'
                ) {
                    return null;
                }
            }

            return service('response')
                ->setStatusCode(403)
                ->setJSON(['status' => 403, 'message' => 'Forbidden: Staff or Administrator access required.']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
