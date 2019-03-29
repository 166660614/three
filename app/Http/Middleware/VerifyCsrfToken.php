<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/goods/*',
        '/user/*',
        'cart/*',
        '/collect/*',
        '/order/*',
<<<<<<< HEAD
        '/end/list',
=======
        '/friend/*',
>>>>>>> 15da329cc8481d3ca24477e8671ffda33acc8d8b
    ];
}
