<?php
namespace App\Layout;

use Closure;

final class PageBuilder
{
    private ?string $content;
    private ?Closure $templateFn;
    private array $attributes;
    private bool $isStatic;

    public function __construct()
    {
        $this->attributes = [];
        $this->content = null;
        $this->templateFn = null;
        $this->isStatic = false;
    }

    /**
     * Set page attributes (title, nav links, etc.)
     */
    public function withAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Set page content
     */
    public function withContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set template rendering function
     */
    public function withTemplate(Closure $templateFn): self
    {
        $this->templateFn = $templateFn;
        return $this;
    }

    /**
     * Enable static page caching
     */
    public function useStatic(): self
    {
        $this->isStatic = true;
        return $this;
    }

    /**
     * Finalize and return rendered page
     */
    public function finalize()
    {
        return Page::getInstance(
            function () {
                if ($this->templateFn !== null) {
                    $templateFn = $this->templateFn;
                    return $templateFn($this->attributes, $this->content);
                }

                throw new \Exception("No template function provided for rendering.");
            },
            $this->isStatic
        )->render();
    }
}