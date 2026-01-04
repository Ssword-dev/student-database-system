<?php

use App\Auth;
use App\Router\Request;
use App\Router\Response;

return function (Request $request, Response $response): Response {
    try {
        Auth::signup(
            $request->request->all(),
            '/student-database-system/'
        );
    } catch (\App\Exceptions\AuthException $e) {
        $response->setStatusCode(400);
        $response->setContent($e->getMessage());
    }
    return $response;
};