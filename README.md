## Overview

This package provides an easy way to define and manage breadcrumbs for Laravel Nova resources. By implementing simple functions within your Nova resources, you can customize the breadcrumbs for various resource actions like `Index`, `Detail`, `Create`, `Update`, and more.

![Breadcrumbs with icon](/screenshots/icon.png)

![Breadcrumbs with html](/screenshots/html.png)

## Installation
Install the package via Composer:

```shell
composer require bocanhcam/nova-breadcrumb
```
After installation, run the following command:

```shell
php artisan nova:breadcrumbs
```

This command publishes the necessary configuration and setup files for the breadcrumbs functionality like `Icon` and `HTML`.

## Usage

First you need to invoke the `Nova::withBreadcrumbs` method. This method should be invoked from within the boot method of your application's `App\Providers\NovaServiceProvider` class:

```php
use Laravel\Nova\Nova;

/**
 * Boot any application services.
 *
 * @return void
 */
public function boot()
{
    parent::boot();

    Nova::withBreadcrumbs();
}
```

Define Breadcrumbs in Your Nova Resources
To define breadcrumbs for a Nova resource, implement the following functions in your resource class `(App\Nova\YourResource)`:

```php
use Bocanhcam\NovaBreadcrumb\Breadcrumb;
use Bocanhcam\NovaBreadcrumb\Breadcrumbs;
use Bocanhcam\NovaBreadcrumb\HTML;
use Bocanhcam\NovaBreadcrumb\Icon;
use Bocanhcam\Nova\Http\Requests\NovaRequest;

class YourResource extends Resource
{
    public function breadcrumbsForIndex(NovaRequest $request): Breadcrumbs
    {
        return Breadcrumbs::make([
            Breadcrumb::make(Icon::make('home')->append(__('Home')), '/'),
            Breadcrumb::make(__('Resource Index'), '/nova/resources/events'),
        ]);
    }

    public function breadcrumbsForDetail(NovaRequest $request): Breadcrumbs
    {
        return Breadcrumbs::make([
            Breadcrumb::make(HTML::make(view('partials.home')->render()), '/'),
            Breadcrumb::make(__('Resource Index'), '/resources/events'),
            Breadcrumb::make(__('Events Detail')),
        ]);
    }
}
```

## Breadcrumb Structure

`Breadcrumbs::make(array $breadcrumbs)` 

Creates a collection of breadcrumbs.

`Breadcrumb::make(string|Icon|HTML $name, string|null $path = null)`

Defines a single breadcrumb. Accepts:

`$name`: The label for the breadcrumb. Can include raw text, icons, or HTML.

`$url`: The optional URL the breadcrumb links to.

## Advanced Features

### Icons
Use the `Icon` helper to add icons to your breadcrumbs. For example:

```php
Breadcrumb::make(Icon::make('home')->append(__('Home')), '/'),
```

### HTML
Use the `HTML` helper for custom HTML views or fragments in breadcrumbs:

```php
Breadcrumb::make(HTML::make(view('partials.home')->render()), '/');
```

## Supported Actions

| Function Name        |              Action               |
| ------------- |:---------------------------------:|
| `breadcrumbsForIndex`      |    For listing all resources.     |
| `breadcrumbsForDetail`      |  For viewing a single resource.   |
| `breadcrumbsForCreate` |   For creating a new resource.    |
| `breadcrumbsForUpdate` | For editing an existing resource. |
| `breadcrumbsForAttach` |   For attaching relationships.    |
| `breadcrumbsForReplicate` |    For replicating a resource.    |
| `breadcrumbsForLens` |   For viewing a resource lens.    |
| `breadcrumbsForDashboard` |     For viewing a dashboard.      |

## License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/license/MIT).
