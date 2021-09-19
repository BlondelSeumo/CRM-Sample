<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;

    class PublicAccessBehavior
    {
        /**
         * Handle an incoming request.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Closure $next
         * @return mixed
         */
        public function handle(Request $request, Closure $next)
        {
            $canAccess = 'no';
            if (auth()->user()->can('manage_public_access')) {
                $canAccess = 'yes';
            } else if (auth()->user()->can('client_access')) {
                $canAccess = 'client';
            }
            if ($canAccess == 'client'){
                $request->merge(['clientRoleAccess' => $canAccess]);
            }else{
                $request->merge(['publicRoleAccess' => $canAccess]);
            }
            return $next($request);
        }
    }
