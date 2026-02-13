<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Terminal;
use Symfony\Component\HttpFoundation\Response;

class TerminalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Terminal-Token');

        if (!$token) {
            return response()->json(['message' => 'Terminal token missing'], 401);
        }

        // In a real app, we'd use hash_equals and store hashed tokens.
        // For this implementation, we'll assume the token in DB is the one sent or a simple hash.
        $terminal = Terminal::where('is_active', true)
            ->where('token_hash', $token) // In production, hash the incoming token first
            ->first();

        if (!$terminal) {
            return response()->json(['message' => 'Invalid or inactive terminal'], 401);
        }

        // Validate IP if set
        if ($terminal->allowed_ip && $terminal->allowed_ip !== $request->ip()) {
            return response()->json(['message' => 'Terminal access denied from this IP'], 403);
        }

        // Attach terminal to request
        $request->attributes->set('terminal', $terminal);

        return $next($request);
    }
}
