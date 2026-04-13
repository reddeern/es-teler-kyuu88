<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlwaysJsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya proses jika request ke URL /api/*
        if ($request->is('api/*')) {
            // Memaksa sistem untuk selalu memberikan respon JSON
            $request->headers->set('Accept', 'application/json');

            // Untuk method POST/PUT/PATCH, ijinkan JSON atau upload file (multipart/form-data)
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
                $contentType = $request->header('Content-Type', '');
                
                $isJson = str_contains($contentType, 'application/json');
                $isMultipart = str_contains($contentType, 'multipart/form-data');

                if (!$isJson && !$isMultipart) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format Content-Type harus application/json (Atau multipart/form-data jika ada gambar)'
                    ], 415);
                }
            }
        }

        return $next($request);
    }
}
