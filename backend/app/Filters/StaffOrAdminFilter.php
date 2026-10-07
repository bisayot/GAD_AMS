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
