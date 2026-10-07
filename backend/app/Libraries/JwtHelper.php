<?php

namespace App\Libraries;

/**
 * Pure-PHP JWT Helper (HS256)
 * No external libraries needed — uses PHP built-in hash_hmac + base64.
 */
class JwtHelper
{
    private static function getSecret(): string
    {
        $secret = env('JWT_SECRET') ?: getenv('JWT_SECRET');
        if (empty($secret)) {
            throw new \RuntimeException('JWT_SECRET is not set in .env');
        }
        return $secret;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }

    /**
     * Generate a signed JWT token.
     *
     * @param array $payload  Data to embed (user id, role, etc.)
     * @param int   $ttl      Time-to-live in seconds (default 8 hours)
     */
    public static function generate(array $payload, int $ttl = 28800): string
    {
        $header = self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;

        $encodedPayload = self::base64UrlEncode(json_encode($payload));

        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$encodedPayload", self::getSecret(), true)
        );

        return "$header.$encodedPayload.$signature";
    }

    /**
     * Verify a JWT token and return its payload.
     * Returns null if the token is invalid or expired.
     *
     * @param string $token
     * @return array|null
     */
    public static function verify(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $encodedPayload, $signature] = $parts;

        // Recalculate signature and compare (timing-safe)
        $expectedSig = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$encodedPayload", self::getSecret(), true)
        );

        if (!hash_equals($expectedSig, $signature)) {
            return null; // Tampered token
        }

        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);

        if (!$payload || !isset($payload['exp'])) {
            return null;
        }

        if (time() > $payload['exp']) {
            return null; // Expired
        }

        return $payload;
    }
}
