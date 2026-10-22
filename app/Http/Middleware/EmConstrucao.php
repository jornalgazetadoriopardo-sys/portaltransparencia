<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enquanto PORTAL_EM_CONSTRUCAO=true, o público vê o aviso "Em construção".
 * Quem está logado no painel continua vendo o portal, para conferir os dados.
 */
class EmConstrucao
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('portal.em_construcao') && ! $request->user()) {
            return response()
                ->view('portal.em-construcao', status: 503)
                ->header('Retry-After', '86400');
        }

        return $next($request);
    }
}
