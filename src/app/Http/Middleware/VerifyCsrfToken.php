<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as LaravelVerifyCsrfToken;


class VerifyCsrfToken extends LaravelVerifyCsrfToken
{
    /**
     * The URIs that should be excluded.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/api/*'
    ];
}
