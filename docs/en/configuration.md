# Configuration Guide

## Basic Configuration

### 1. Package Configuration

Configure the Vite service in your container definitions:

```php
use Slim4\Vite\ViteService;
use Slim4\Root\PathsInterface;

$container->set(ViteService::class, function ($container) {
    $paths = $container->get(PathsInterface::class);
    $isDev = $_ENV['APP_ENV'] === 'development';
    
    return new ViteService(
        $paths,
        'build',
        $isDev
    );
});
```

### 2. Vite Configuration

Create `vite.config.js` in your project root:

```javascript
import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
                style: 'resources/css/app.css'
            }
        }
    },
    server: {
        strictPort: true,
        port: 5173
    }
});
```

## Advanced Configuration

### Custom Asset Directories

Configure custom asset directories for better organization:

```php
$viteService = new ViteService(
    $paths,
    'build',
    $isDev,
    'http://localhost:5173',
    [
        'images' => 'assets/images',
        'fonts' => 'assets/fonts',
        'icons' => 'assets/icons'
    ]
);
```

### Development Server Settings

Customize the development server configuration:

```php
use Slim4\Vite\Config\DevServerConfig;

$devConfig = new DevServerConfig([
    'host' => 'localhost',
    'port' => 3000,
    'https' => true,
    'timeout' => 5
]);

$viteService->setDevServerConfig($devConfig);
```

### Asset Processing Options

Configure how assets are processed:

```php
use Slim4\Vite\Config\AssetConfig;

$assetConfig = new AssetConfig([
    'minify' => true,
    'sourcemaps' => false,
    'versioning' => true,
    'preload' => [
        'js/app.js',
        'css/app.css'
    ]
]);

$viteService->setAssetConfig($assetConfig);
```

## Environment-Specific Configuration

### Development

```php
// config/environments/development.php
return [
    'vite' => [
        'isDev' => true,
        'devServerUrl' => 'http://localhost:5173',
        'sourcemaps' => true
    ]
];
```

### Production

```php
// config/environments/production.php
return [
    'vite' => [
        'isDev' => false,
        'minify' => true,
        'sourcemaps' => false,
        'versioning' => true
    ]
];
```

## Working with Multiple Entry Points

Configure multiple entry points for different sections of your application:

```javascript
// vite.config.js
export default defineConfig({
    build: {
        rollupOptions: {
            input: {
                main: 'resources/js/main.js',
                admin: 'resources/js/admin.js',
                shop: 'resources/js/shop.js',
                'main-style': 'resources/css/main.css',
                'admin-style': 'resources/css/admin.css'
            }
        }
    }
});
```

## Event Handling

Configure event listeners for Vite operations:

```php
use Slim4\Vite\Events\ViteEvents;
use Slim4\Vite\Events\AssetResolveEvent;

$viteService->on(ViteEvents::BEFORE_ASSET_RESOLVE, function (AssetResolveEvent $event) {
    $path = $event->getPath();
    // Custom logic before asset resolution
});

$viteService->on(ViteEvents::DEV_SERVER_ERROR, function ($error) {
    // Custom error handling
    error_log("Vite dev server error: " . $error->getMessage());
});
```

## Integration with Other Tools

### TypeScript Configuration

```javascript
// vite.config.js
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        typescript({
            tsconfig: './tsconfig.json'
        })
    ]
});
```

### Vue.js Integration

```javascript
// vite.config.js
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue()
    ]
});
```