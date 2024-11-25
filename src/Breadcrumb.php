<?php

namespace Hoangdv\NovaBreadcrumb;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\Breadcrumb as NovaBreadcrumb;

class Breadcrumb extends NovaBreadcrumb
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name instanceof Icon ? $this->name->icon :
                ($this->name instanceof HTML ? $this->name->html : $this->name),
            'path' => $this->authorizedToSee(app(NovaRequest::class)) ? $this->path : null,
            'isHtml' => $this->name instanceof HTML,
            'isIcon' => $this->name instanceof Icon,
            'prepend' => $this->name instanceof Icon ? $this->name->prepend : null,
            'append' => $this->name instanceof Icon ? $this->name->append : null,
        ];
    }
}
