<?php
namespace App\Templating;

function _renderViewInIsolation($view, $data = array()): string
{
    ob_start();
    extract($data, EXTR_SKIP);
    include $view;
    $html = ob_get_clean();
    return $html;
}

final class View
{
    private string $view;

    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    public function __construct($view, $data = array())
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function render()
    {
        $html = _renderViewInIsolation($this->view, $this->data);
        $layoutLocation = dirname($this->view) . '/_layout.php';

        if (file_exists($layoutLocation)) {
            $html = _renderViewInIsolation($layoutLocation, [...$this->data, 'content' => $html]);
        }

        return $html;
    }
}