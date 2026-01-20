<?php

namespace App\Http\Middleware;

use App\Models\SecurityEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectSecurityThreats
{
    /**
     * SQL injection patterns to detect
     */
    protected array $sqlInjectionPatterns = [
        '/\bUNION\s+SELECT\b/i',
        '/\bSELECT\s+.*\s+FROM\b/i',
        '/\bDROP\s+(TABLE|DATABASE)\b/i',
        '/\bDELETE\s+FROM\b/i',
        '/\bINSERT\s+INTO\b/i',
        '/\bUPDATE\s+.*\s+SET\b/i',
        '/\bOR\s+1\s*=\s*1\b/i',
        '/\bOR\s+\'1\'\s*=\s*\'1\'/i',
        '/\bAND\s+1\s*=\s*1\b/i',
        '/--\s*$/m',
        '/;\s*DROP\b/i',
        '/\bEXEC(\s+|\()/i',
        '/\bXP_\w+/i',
        '/\bSLEEP\s*\(/i',
        '/\bBENCHMARK\s*\(/i',
        '/\bWAITFOR\s+DELAY\b/i',
        '/\bLOAD_FILE\s*\(/i',
        '/\bINTO\s+(OUT|DUMP)FILE\b/i',
    ];

    /**
     * XSS (Cross-Site Scripting) patterns to detect
     */
    protected array $xssPatterns = [
        '/<script\b[^>]*>/i',
        '/<\/script>/i',
        '/javascript\s*:/i',
        '/on(click|error|load|mouseover|mouseout|keyup|keydown|submit|focus|blur)\s*=/i',
        '/<iframe\b/i',
        '/<object\b/i',
        '/<embed\b/i',
        '/<img\b[^>]*\s+onerror\s*=/i',
        '/expression\s*\(/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/<svg\b[^>]*\s+onload\s*=/i',
        '/<body\b[^>]*\s+onload\s*=/i',
    ];

    /**
     * Suspicious patterns (directory traversal, null bytes, etc.)
     */
    protected array $suspiciousPatterns = [
        '/\.\.\//', // Directory traversal
        '/\.\.\\\\/', // Directory traversal (Windows)
        '/%00/', // Null byte
        '/%2e%2e/i', // Encoded directory traversal
        '/\/etc\/passwd/i', // Unix passwd file
        '/\/etc\/shadow/i', // Unix shadow file
        '/\/proc\/self/i', // Proc filesystem
        '/cmd\.exe/i', // Windows command execution
        '/powershell/i', // PowerShell
        '/\$\{.*\}/i', // Template injection
        '/\{\{.*\}\}/i', // Template injection (Blade/Twig)
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Collect all input values recursively
        $allInputs = $this->flattenArray($request->all());

        // Also check query string, path, and headers
        $allInputs[] = $request->path();
        $allInputs[] = $request->getQueryString() ?? '';

        // Check user agent
        if ($request->hasHeader('User-Agent')) {
            $allInputs[] = $request->header('User-Agent');
        }

        foreach ($allInputs as $value) {
            if (!is_string($value) || empty($value)) {
                continue;
            }

            // Check for SQL injection
            foreach ($this->sqlInjectionPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $this->logSecurityEvent('sql_injection', $value, $pattern, $request);
                    break 2; // Log only once per request
                }
            }

            // Check for XSS
            foreach ($this->xssPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $this->logSecurityEvent('xss_attempt', $value, $pattern, $request);
                    break 2;
                }
            }

            // Check for suspicious patterns
            foreach ($this->suspiciousPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $this->logSecurityEvent('suspicious_input', $value, $pattern, $request);
                    break 2;
                }
            }
        }

        return $next($request);
    }

    /**
     * Flatten a multi-dimensional array into a single-level array of values
     */
    protected function flattenArray(array $array): array
    {
        $result = [];

        array_walk_recursive($array, function ($value) use (&$result) {
            if (is_string($value)) {
                $result[] = $value;
            }
        });

        return $result;
    }

    /**
     * Log a security event (without blocking the request)
     */
    protected function logSecurityEvent(string $eventType, string $maliciousInput, string $pattern, Request $request): void
    {
        // Truncate malicious input for storage (avoid huge payloads)
        $truncatedInput = mb_substr($maliciousInput, 0, 500);

        SecurityEvent::log(
            $eventType,
            "Detected potential {$eventType}: matched pattern. Input sample: " . $truncatedInput,
            false, // blocked = false (we only log, don't block)
            [
                'matched_pattern' => $pattern,
                'input_sample' => $truncatedInput,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->header('User-Agent'),
            ]
        );
    }
}
