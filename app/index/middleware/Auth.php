<?php


namespace app\index\middleware;


class Auth
{
    public function handle($request, \Closure $next)
    {
        $controller = $request->controller();
        $action = $request->action();
        echo $controller.'_'.$action;
        return $next($request);
    }
}