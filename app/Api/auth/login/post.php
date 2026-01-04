<?php

use App\Auth;
use App\Router\Request;
use App\Router\Response;

return function (Request $request, Response $response): Response {
    try {
        Auth::login(
            $request->request->all(),
            '/student-database-system/dashboard'
        );
    } catch (\App\Exceptions\AuthException $e) {
        $response->setStatusCode(401);
        $response->setContent($e->getMessage());
    }
    return $response;
};