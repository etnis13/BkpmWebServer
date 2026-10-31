<?php
class Auth {
    // Ganti secret ini dengan nilai acak panjang sebelum dipakai di lingkungan nyata.
    private const SECRET = 'acara20-demo-secret-ganti-dengan-random-secret-panjang';

    public static function createToken(array $payload): string {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload['iat'] = time();
        $payload['exp'] = time() + 300;
        $head = self::base64UrlEncode(json_encode($header));
        $body = self::base64UrlEncode(json_encode($payload));
        $signature = self::base64UrlEncode(hash_hmac('sha256', "$head.$body", self::SECRET, true));
        return "$head.$body.$signature";
    }

    public static function verifyToken(string $token): array|false {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;
        [$head, $body, $signature] = $parts;
        $expected = self::base64UrlEncode(hash_hmac('sha256', "$head.$body", self::SECRET, true));
        if (!hash_equals($expected, $signature)) return false;
        $payload = json_decode(self::base64UrlDecode($body), true);
        if (!is_array($payload) || !isset($payload['exp']) || $payload['exp'] < time()) return false;
        return $payload;
    }

    public static function requireToken(): array {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer\s+(.+)$/i', trim($authorization), $matches)) {
            Response::json(401, false, 'Token autentikasi wajib disertakan');
        }
        $payload = self::verifyToken($matches[1]);
        if ($payload === false) Response::json(401, false, 'Token tidak valid atau sudah kedaluwarsa');
        return $payload;
    }

    private static function base64UrlEncode(string $value): string { return rtrim(strtr(base64_encode($value), '+/', '-_'), '='); }
    private static function base64UrlDecode(string $value): string|false {
        return base64_decode(strtr($value, '-_', '+/') . str_repeat('=', (4 - strlen($value) % 4) % 4), true);
    }
}
