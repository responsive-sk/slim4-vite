# Getting Started with Slim4 Vite

## Overview

Slim4 Vite provides seamless integration between Vite.js and Slim 4 Framework, offering:

- Hot Module Replacement (HMR) in development
- Asset versioning in production
- Twig integration for easy asset loading
- Support for TypeScript, Vue.js, React, and other frameworks
- Optimized asset loading strategies

## Quick Start

1. Install the package:
```bash
composer require slim4/vite
```

2. Install required npm packages:
```bash
npm install vite @vitejs/plugin-vue @types/node --save-dev
```

3. Configure Vite middleware in your Slim application:
```php
use Slim4\Vite\Middleware\ViteMiddleware;

return function (App $app) {
    $app->add(ViteMiddleware::class);
};
```

4. Add Vite Twig extension:
```php
use Slim4\Vite\TwigExtension\ViteExtension;

$twig->addExtension(new ViteExtension());
```

## Basic Usage

In your Twig templates:
```twig
{# Load JavaScript #}
{{ vite_js('js/app.js') }}

{# Load CSS #}
{{ vite_css('css/app.css') }}

{# Load other assets #}
{{ vite_asset('images/logo.png') }}
```