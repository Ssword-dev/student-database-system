<?php
namespace App\Layout;

/**
 * Base layout class for page rendering
 * 
 * Subclasses should override render() to return HTML
 * and can access $this->attributes and $this->content
 */
abstract class Layout
{
    protected array $attributes;
    protected ?string $content;

    public function __construct(array $attributes = [], ?string $content = null)
    {
        $this->attributes = $attributes;
        $this->content = $content;
    }

    /**
     * Get an attribute value
     */
    public function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Check if attribute exists
     */
    public function hasAttribute(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Render the layout and return HTML
     */
    abstract public function render(): string;

    /**
     * Allow invoking the layout as a function
     */
    public function __invoke()
    {
        return $this->render();
    }
}