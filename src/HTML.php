<?php

namespace Bocanhcam\NovaBreadcrumb;

use Laravel\Nova\Makeable;

class HTML
{
    use Makeable;

    /**
     * @var string
     */
    public string $html;

    /**
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html = $html;
    }
}
