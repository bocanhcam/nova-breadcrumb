<?php

namespace Hoangdv\NovaBreadcrumb;

use Laravel\Nova\Makeable;

class Icon
{
    use Makeable;

    /**
     * @var string
     */
    public string $icon;

    /**
     * @var string|null
     */
    public ?string $prepend = null;

    /**
     * @var string|null
     */
    public ?string $append = null;

    /**
     * @param string $icon
     */
    public function __construct(string $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function prepend(string $text): static
    {
        $this->prepend = $text;

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function append(string $text): static
    {
        $this->append = $text;

        return $this;
    }
}
