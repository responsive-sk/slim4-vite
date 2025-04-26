# Examples and Recipes

## Basic Examples

### 1. Simple Asset Loading

```twig
{# templates/layout.twig #}
<!DOCTYPE html>
<html>
<head>
    {{ vite_css('css/app.css') }}
</head>
<body>
    {% block content %}{% endblock %}
    {{ vite_js('js/app.js') }}
</body>
</html>
```

### 2. Dynamic Imports

```javascript
// resources/js/app.js
const loadAdminPanel = async () => {
    const { default: AdminPanel } = await import('./components/AdminPanel.js');
    new AdminPanel('#admin-container');
};

if (document.querySelector('#admin-container')) {
    loadAdminPanel();
}
```

## Advanced Examples

### 1. Theme System Integration

```php
// src/Theme/ThemeManager.php
class ThemeManager
{
    private ViteService $vite;
    
    public function getThemeAssets(string $themeName): array
    {
        return [
            'css' => $this->vite->getAssetUrl("themes/{$themeName}/style.css"),
            'js' => $this->vite->getAssetUrl("themes/{$themeName}/main.js")
        ];
    }
}
```

```twig
{# templates/theme.twig #}
{% set theme = theme_manager.getThemeAssets('modern') %}

<link rel="stylesheet" href="{{ theme.css }}">
<script src="{{ theme.js }}" defer></script>
```

### 2. Module Federation

```javascript
// vite.config.js
import federation from '@originjs/vite-plugin-federation';

export default defineConfig({
    plugins: [
        federation({
            name: 'host-app',
            remotes: {
                remote_app: 'http://localhost:3001/assets/remoteEntry.js',
            },
            shared: ['vue']
        })
    ]
});
```

## Real-World Recipes

### 1. Image Processing Pipeline

```php
// src/Asset/ImageProcessor.php
class ImageProcessor
{
    private ViteService $vite;
    
    public function getProcessedImage(string $path, array $options = []): string
    {
        $assetPath = "images/{$path}";
        
        if ($this->vite->isDevMode()) {
            return $this->vite->getAssetUrl($assetPath);
        }
        
        return $this->vite->getAssetUrl($this->processImage($assetPath, $options));
    }
}
```

```twig
{# Usage in template #}
<img src="{{ image_processor.getProcessedImage('hero.jpg', {
    width: 1200,
    format: 'webp'
}) }}" alt="Hero">
```

### 2. Custom Components with HMR

```javascript
// resources/js/components/Counter.js
export default class Counter {
    constructor(selector) {
        this.element = document.querySelector(selector);
        this.count = 0;
        this.render();
        this.attachEvents();
    }

    render() {
        this.element.innerHTML = `
            <div class="counter">
                <button class="decrement">-</button>
                <span class="value">${this.count}</span>
                <button class="increment">+</button>
            </div>
        `;
    }

    attachEvents() {
        this.element.querySelector('.increment').addEventListener('click', () => {
            this.count++;
            this.render();
        });

        this.element.querySelector('.decrement').addEventListener('click', () => {
            this.count--;
            this.render();
        });
    }
}

if (import.meta.hot) {
    import.meta.hot.accept((newModule) => {
        // Handle hot update
    });
}
```

### 3. API Integration with TypeScript

```typescript
// resources/ts/api/ArtService.ts
interface ArtItem {
    id: string;
    title: string;
    type: 'digital' | 'physical';
    price: number;
}

export class ArtService {
    private baseUrl: string;

    constructor(baseUrl: string) {
        this.baseUrl = baseUrl;
    }

    async getArtItems(): Promise<ArtItem[]> {
        const response = await fetch(`${this.baseUrl}/api/art`);
        return response.json();
    }
}
```

### 4. Lazy Loading with Route-Based Code Splitting

```javascript
// resources/js/router.js
const routes = {
    '/': () => import('./pages/Home.js'),
    '/gallery': () => import('./pages/Gallery.js'),
    '/contact': () => import('./pages/Contact.js')
};

async function router() {
    const path = window.location.pathname;
    const page = routes[path];
    
    if (page) {
        const module = await page();
        module.default();
    }
}
```

### 5. Custom Asset Loading Strategy

```php
// src/Asset/AssetStrategy.php
class AssetStrategy
{
    private ViteService $vite;
    
    public function loadPageAssets(string $pageName): array
    {
        return [
            'critical' => $this->vite->getAssetUrl("css/{$pageName}/critical.css"),
            'main' => $this->vite->getAssetUrl("css/{$pageName}/main.css"),
            'js' => $this->vite->getAssetUrl("js/{$pageName}.js")
        ];
    }
}
```

```twig
{# templates/page.twig #}
{% set assets = asset_strategy.loadPageAssets('home') %}

<style>{{ include(assets.critical) }}</style>
<link rel="preload" href="{{ assets.main }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<script src="{{ assets.js }}" type="module"></script>
```

These examples demonstrate various integration patterns and real-world usage scenarios. Each example can be customized and extended based on your specific needs.