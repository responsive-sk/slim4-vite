# Advanced Usage

## Critical CSS Inlining

```php
use Slim4\Vite\ViteService;

$vite = new ViteService($paths, [
    'inlineCriticalCss' => true,
    'criticalCssPath' => 'css/critical.css'
]);
```

## Dynamic Imports

```javascript
// In your JavaScript
const module = await import('./features/module.js');
```

## Asset Preloading

```twig
{# Preload critical assets #}
{{ vite_preload('js/app.js') }}
{{ vite_preload('css/app.css') }}
```

## Development vs Production

The package automatically handles different environments:

- Development: Uses Vite dev server with HMR
- Production: Uses built and optimized assets

## Custom Configuration

```php
use Slim4\Vite\ViteService;

$vite = new ViteService($paths, [
    'devServerUrl' => 'http://localhost:3000',
    'buildDirectory' => 'build',
    'manifestFile' => 'manifest.json',
    'assetDirectories' => [
        'images' => 'assets/images',
        'fonts' => 'assets/fonts'
    ]
]);
```