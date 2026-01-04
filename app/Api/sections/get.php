<?php

use App\Router\Request;
use App\Router\Response;

return function (Request $request, Response $response): Response {
    $db = dbConnect(
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_PASSWORD'],
        $_ENV['MYSQL_DATABASE_NAME']
    );

    $paginationItemCount = $_ENV['MAX_PAGINATION_ITEM'];
    $page = $request->query->get('page', 0);

    $sections = fetchQuery(
        $db,
        'SELECT * FROM `sections` WHERE `id` >= ? LIMIT ?',
        'ii',
        $paginationItemCount,
        $page
    );

    return $response->setContent(json_encode($sections))->setContentType('application/json');
};