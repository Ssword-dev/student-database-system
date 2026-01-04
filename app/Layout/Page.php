<?php
namespace App\Layout;

use \Closure;
use App\Traits\Singleton;

final class Page
{
    use Singleton;
    private Closure $renderFn;
    private bool $isStatic;

    protected static function initInstance(Page $instance, Closure $renderFn, bool $isStatic = false): void
    {
        $instance->renderFn = $renderFn;
        $instance->isStatic = $isStatic;
    }

    public function render()
    {
        $cache = PageCache::getInstance();

        if ($this->isStatic) {
            $cachedContent = $cache->renderCachedPage($_SERVER['REQUEST_URI']);
            if ($cachedContent !== null) {
                return $cachedContent;
            }

            $content = ($this->renderFn)();
            $cache->cacheCurrentPage($content);
            return $content;
        }

        return ($this->renderFn)();
    }
}