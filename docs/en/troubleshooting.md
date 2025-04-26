# Troubleshooting Guide

## Common Issues and Solutions

### 1. Assets Not Loading in Development

#### Symptoms
- Assets are not loading
- Console shows 404 errors for assets
- Vite dev server not responding

#### Solutions

1. Check Vite dev server status:
```bash
# Check if Vite server is running
lsof -i :5173
# Or
netstat -an | grep 5173
```

2. Verify configuration:
```php
// config/container/views.php
$container->set(ViteService::class, function ($container) {
    return new ViteService(
        $container->get(PathsInterface::class),
        'build',
        true,  // Make sure isDev is true in development
        'http://localhost:5173'  // Verify correct dev server URL
    );
});
```

3. Check CORS settings:
```javascript
// vite.config.js
export default defineConfig({
    server: {
        cors: true,
        strictPort: true,
        port: 5173
    }
});
```

### 2. Production Build Issues

#### Symptoms
- Missing assets in production
- Incorrect asset paths
- Manifest not found

#### Solutions

1. Verify build output:
```bash
# Clean build directory
rm -rf public/build/*

# Rebuild assets
npm run build
# or
pnpm build
```

2. Check manifest generation:
```javascript
// vite.config.js
export default defineConfig({
    build: {
        manifest: true,  // Ensure manifest is enabled
        outDir: 'public/build',
        assetsDir: ''
    }
});
```

3. Verify file permissions:
```bash
chmod -R 755 public/build
```

### 3. HMR (Hot Module Replacement) Not Working

#### Symptoms
- Changes not reflecting in browser
- Need to manually refresh

#### Solutions

1. Enable HMR explicitly:
```javascript
// resources/js/app.js
if (import.meta.hot) {
    import.meta.hot.accept()
}
```

2. Check WebSocket connection:
```javascript
// vite.config.js
export default defineConfig({
    server: {
        hmr: {
            protocol: 'ws',
            host: 'localhost',
            port: 5173
        }
    }
});
```

### 4. CSS Processing Issues

#### Symptoms
- Styles not applying
- PostCSS plugins not working
- Tailwind not processing

#### Solutions

1. Verify PostCSS configuration:
```javascript
// postcss.config.js
module.exports = {
    plugins: {
        'tailwindcss': {},
        'autoprefixer': {},
    }
}
```

2. Check import order:
```javascript
// resources/js/app.js
import '../css/app.css'  // Make sure CSS is imported
```

### 5. Performance Issues

#### Symptoms
- Slow development server
- Long build times
- Large bundle sizes

#### Solutions

1. Optimize dependencies:
```javascript
// vite.config.js
export default defineConfig({
    optimizeDeps: {
        include: ['alpine', 'gsap'],  // Pre-bundle frequently used deps
    }
});
```

2. Enable build caching:
```bash
# Clear cache if needed
rm -rf node_modules/.vite

# Add to package.json
{
    "scripts": {
        "build": "vite build --emptyOutDir"
    }
}
```

3. Configure chunking strategy:
```javascript
// vite.config.js
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpine', 'gsap'],
                    utils: ['./resources/js/utils/']
                }
            }
        }
    }
});
```

## Debugging Tips

### 1. Enable Verbose Logging

```php
// config/container/views.php
$container->set(ViteService::class, function ($container) {
    $service = new ViteService(...);
    $service->setDebug(true);
    return $service;
});
```

### 2. Check Asset Resolution

```php
// In your controller or middleware
$assetUrl = $vite->getAssetUrl('js/app.js');
error_log("Resolved asset URL: " . $assetUrl);
```

### 3. Verify Manifest

```php
// Debug manifest contents
$manifestPath = $paths->getPublicPath() . '/build/manifest.json';
if (file_exists($manifestPath)) {
    error_log("Manifest contents: " . file_get_contents($manifestPath));
}
```

## Common Error Messages

### 1. "Failed to load manifest.json"

This usually means:
- Production build hasn't been run
- Incorrect build output directory
- File permission issues

### 2. "Cannot find module"

Check:
- Node modules are installed
- Import paths are correct
- Module exists in package.json

### 3. "CORS policy" errors

Solutions:
- Configure CORS in Vite
- Check dev server URL
- Verify proxy settings

## Getting Help

If you're still experiencing issues:

1. Check the [GitHub Issues](https://github.com/your-username/slim4-vite/issues)
2. Create a minimal reproduction
3. Include:
   - Vite config
   - PHP configuration
   - Error messages
   - Environment details

## Reporting Bugs

When reporting bugs, please include:

1. Version information:
   - PHP version
   - Node.js version
   - Vite version
   - Slim4-Vite version

2. Configuration files:
   - vite.config.js
   - PHP container configuration
   - Related middleware setup

3. Steps to reproduce