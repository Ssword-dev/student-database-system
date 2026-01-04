<?php
namespace App\Layout;

use App\Traits\Singleton;

final class PageCache
{
    use Singleton;

    private string $cacheDir;

    private function __construct()
    {
        // default cache dir under Layout namespace
        $this->cacheDir = __DIR__ . '/cache/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    /**
     * initializes singleton instance
     *
     * @param PageCache $instance
     * @return void
     */
    public static function initInstance(PageCache $instance): void
    {
        // ensure cache dir exists
        if (!is_dir($instance->cacheDir)) {
            mkdir($instance->cacheDir, 0777, true);
        }
    }

    public function extractPathFromUrl(string $url): string
    {
        $parsedUrl = parse_url($url, PHP_URL_PATH);
        return $parsedUrl ?? '/';
    }

    /**
     * store current page in cache
     *
     * @param string $content
     * @return void
     */
    public function cacheCurrentPage(string $content): void
    {
        $urlPath = $this->extractPathFromUrl($_SERVER['REQUEST_URI']);
        $this->set($urlPath, $content);
    }

    /**
     * render cached page if it exists
     *
     * @param string $pagePath
     * @return string|null
     * @param string $pagePath
     * @return string|null
     */
    public function renderCachedPage(string $pageUri): ?string
    {
        $pagePath = $this->extractPathFromUrl($pageUri);
        $file = $this->getPath($pagePath);
        return file_exists($file) ? file_get_contents($file) : null;
    }

    /**
     * check if cache exists for page
     *
     * @param string $pagePath
     * @return bool
     */
    public function has(string $pagePath): bool
    {
        return file_exists($this->getPath($pagePath));
    }

    /**
     * write cache file
     *
     * @param string $pagePath
     * @param string $content
     * @return void
     */
    public function set(string $pagePath, string $content): void
    {
        file_put_contents($this->getPath($pagePath), $content);
    }

    /**
     * generate file path for cached page
     *
     * @param string $pagePath
     * @return string
     */
    private function getPath(string $pagePath): string
    {
        return $this->cacheDir . md5($pagePath) . '.html';
    }
}
