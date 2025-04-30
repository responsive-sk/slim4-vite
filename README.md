# ResponsiveSk Vite Integration

A simple and lightweight integration of Vite with Slim 4 framework.

## Installation

```bash
composer require responsive-sk/vite
```

## Usage

### Register the service in your container

```php
use ResponsiveSk\Vite\ViteService;
use ResponsiveSk\Vite\TwigExtension;
use Slim4\Root\PathsInterface;

// In your container definitions
$container->set(ViteService::class, function ($container) {
    $paths = $container->get(PathsInterface::class);
    $isDev = $_ENV['APP_ENV'] === 'development';

    // Basic configuration
    return new ViteService($paths, 'build', $isDev);

    // Advanced configuration with custom asset directories
    /*
    return new ViteService(
        $paths,
        'build',
        $isDev,
        'http://localhost:5173',
        [
            'images' => 'assets/images',
            'fonts' => 'assets/fonts',
        ]
    );
    */
});

// Register Twig extension
$container->extend(Twig::class, function ($twig, $container) {
    $viteService = $container->get(ViteService::class);
    $twig->addExtension(new TwigExtension($viteService));
    return $twig;
});
```

### Use in Twig templates

```twig
{# Load CSS for an entry point #}
{{ vite_entry_link_tags('css') }}

{# Load JavaScript for an entry point #}
{{ vite_entry_script_tags('app') }}

{# Get URL for an asset #}
<img src="{{ vite_asset('resources/images/logo.png') }}" alt="Logo">

{# Get URL for an image #}
<img src="{{ vite_image('logo.png') }}" alt="Logo">

{# Get URL for an image with custom resource path #}
<img src="{{ vite_image('hero.jpg', 'resources/assets/images') }}" alt="Hero">

{# Get URL for an image with custom resource path and placeholder (fallback if image not found) #}
<img src="{{ vite_image('hero.jpg', 'resources/assets/images', 'half-sphere.svg') }}" alt="Hero">

{# Handle missing images gracefully #}
{% set image_url = vite_image('missing.jpg') %}
{% if image_url %}
    <img src="{{ image_url }}" alt="Image">
{% else %}
    <div class="placeholder">Image not found</div>
{% endif %}

{# Get URL for a font #}
<style>
    @font-face {
        font-family: 'CustomFont';
        src: url('{{ vite_font('custom.woff2') }}') format('woff2');
    }
</style>
```

## Configuration

The `ViteService` constructor accepts the following parameters:

- `PathsInterface $paths`: The paths service from slim4/root
- `string $buildDirectory = 'build'`: The build directory relative to public path
- `bool $isDev = false`: Whether to use dev server
- `string $devServerUrl = 'http://localhost:5173'`: Dev server URL
- `array $assetDirectories = ['images' => 'assets/images', 'fonts' => 'assets/fonts']`: Directories where assets are stored

## Available Methods

### ViteService

- `asset(string $entry): string` - Get the path to an asset
- `entryLinkTags(string $entry): string` - Generate link tags for CSS files from an entry
- `entryScriptTags(string $entry): string` - Generate script tags for JS files from an entry
- `image(string $path, string $resourcePath = 'resources/images', ?string $placeholder = null): ?string` - Get the path to an image, returns null if image not found and no placeholder specified
- `font(string $path): string` - Get the path to a font
- `getManifest(): array` - Get the raw manifest data

### TwigExtension

- `vite_asset(string $entry): string` - Get the path to an asset
- `vite_entry_link_tags(string $entry): string` - Generate link tags for CSS files from an entry
- `vite_entry_script_tags(string $entry): string` - Generate script tags for JS files from an entry
- `vite_image(string $path, string $resourcePath = 'resources/images', ?string $placeholder = null): ?string` - Get the path to an image, returns null if image not found and no placeholder specified
- `vite_font(string $path): string` - Get the path to a font
- `vite_css(string $entry): string` - Alias for vite_entry_link_tags (legacy)
- `vite_js(string $entry): string` - Alias for vite_entry_script_tags (legacy)

## License

MIT

