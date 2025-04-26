# API Reference

## ViteService

Main service class that handles Vite integration.

### Constructor

```php
public function __construct(
    PathsInterface $paths,
    string $buildDir = 'build',
    bool $isDev = false,
    string $devServerUrl = 'http://localhost:5173',
    array $assetDirs = []
)
```

#### Parameters:
- `$paths`: PathsInterface instance for resolving application paths
- `$buildDir`: Directory where Vite builds assets (default: 'build')
- `$isDev`: Development mode flag (default: false)
- `$devServerUrl`: Vite dev server URL (default: 'http://localhost:5173')
- `$assetDirs`: Asset directories configuration (default: [])

### Methods

#### getAssetUrl
```php
public function getAssetUrl(string $path): string
```
Returns the URL for an asset, handling both development and production environments.

#### isDevMode
```php
public function isDevMode(): bool
```
Returns whether the service is running in development mode.

## Twig Extensions

### ViteExtension

Provides Twig functions for asset loading.

#### Available Functions:

##### vite_js
```twig
{{ vite_js(string $path, array $attributes = []) }}
```
Loads JavaScript files with optional attributes.

Example:
```twig
{{ vite_js('js/app.js', {
    'defer': true,
    'data-module': 'main'
}) }}
```

##### vite_css
```twig
{{ vite_css(string $path, array $attributes = []) }}
```
Loads CSS files with optional attributes.

Example:
```twig
{{ vite_css('css/app.css', {
    'media': 'screen',
    'id': 'main-css'
}) }}
```

##### vite_asset
```twig
{{ vite_asset(string $path) }}
```
Returns the URL for any asset type.

Example:
```twig
<img src="{{ vite_asset('images/logo.png') }}" alt="Logo">
```

## Middleware

### ViteMiddleware

Middleware for handling Vite development server integration.

```php
use Slim4\Vite\Middleware\ViteMiddleware;

$app->add(ViteMiddleware::class);
```

## Configuration

### ViteConfig

Class for handling Vite configuration.

```php
use Slim4\Vite\Config\ViteConfig;

$config = new ViteConfig([
    'buildDir' => 'build',
    'devServerUrl' => 'http://localhost:5173',
    'manifest' => 'manifest.json',
    'assetDirs' => [
        'images' => 'assets/images',
        'fonts' => 'assets/fonts'
    ]
]);
```

## Events

### ViteEvents

Available events that can be listened to:

```php
use Slim4\Vite\Events\ViteEvents;

// Before asset resolution
ViteEvents::BEFORE_ASSET_RESOLVE

// After asset resolution
ViteEvents::AFTER_ASSET_RESOLVE

// On development server error
ViteEvents::DEV_SERVER_ERROR
```

## Exceptions

### ViteException
Base exception class for all Vite-related exceptions.

### DevServerConnectionException
Thrown when unable to connect to Vite dev server.

### ManifestNotFoundException
Thrown when the Vite manifest file is not found.