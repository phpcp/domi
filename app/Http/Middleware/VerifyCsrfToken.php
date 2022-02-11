<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
    	"admin/prohibit",
    	"admin/modular-status",
        "admin/wlanguage-show",
        "admin/wlanguage-baidu",
        "admin/wcourse-low-video",
        "admin/wcourse-factor",
        "admin/wcountryz-show",
        "admin/wmeeting-show",
        "admin/wanchor-user-show",
        "admin/wagent-show",
        "admin/translate-form",
        "admin/translate-translate",
    ];
}
