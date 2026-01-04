<?php
namespace App\Templating;

use App\Router\Request;
use App\Router\Response;
use Lazervel\Path\Path;

final class ViewMiddleware
{
    private string $viewDir;

    public function __construct(string $viewDir)
    {
        $this->viewDir = $viewDir;
    }

    public function __invoke(Request $request, ?Response $response): Response|null
    {
        $response = $response ?? new Response();
        $relPath = ltrim($request->path, '/');

        $candidates = [
            $relPath . '.php',
            Path::join($relPath, 'index.php')
        ];

        foreach ($candidates as $candidate) {
            $viewFile = Path::join($this->viewDir, $candidate);
            if (file_exists($viewFile)) {
                $response->setContentType('text/html');
                $response->setStatusCode(200);
                $html = (new View($viewFile, []))->render();
                $response->setContent($html);
                $response->finalize();
                return $response;
            }
        }

        return $response;
    }

}