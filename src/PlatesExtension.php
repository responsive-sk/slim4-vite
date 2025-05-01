<?php

declare(strict_types=1);

namespace Slim4\Vite;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

/**
 * Plates extension for Vite integration.
 */
class PlatesExtension implements ExtensionInterface
{
    /**
     * @var ViteService The Vite service
     */
    private ViteService $viteService;

    /**
     * Constructor.
     *
     * @param ViteService $viteService The Vite service
     */
    public function __construct(ViteService $viteService)
    {
        $this->viteService = $viteService;
    }

    /**
     * Register the extension with the Plates engine.
     *
     * @param Engine $engine The Plates engine
     * @return void
     */
    public function register(Engine $engine): void
    {
        // Register functions
        $engine->registerFunction('vite_asset', [$this, 'asset']);
        $engine->registerFunction('vite_entry_link_tags', [$this, 'entryLinkTags']);
        $engine->registerFunction('vite_entry_script_tags', [$this, 'entryScriptTags']);
        $engine->registerFunction('vite_image', [$this, 'image']);
        $engine->registerFunction('vite_font', [$this, 'font']);
        
        // Legacy aliases
        $engine->registerFunction('vite_css', [$this, 'entryLinkTags']);
        $engine->registerFunction('vite_js', [$this, 'entryScriptTags']);
    }

    /**
     * Get the path to an asset.
     *
     * @param string $entry Entry point name
     * @return string Asset path
     */
    public function asset(string $entry): string
    {
        return $this->viteService->asset($entry);
    }

    /**
     * Generate link tags for CSS files from an entry.
     *
     * @param string $entry Entry point name
     * @return string HTML link tags
     */
    public function entryLinkTags(string $entry): string
    {
        return $this->viteService->entryLinkTags($entry);
    }

    /**
     * Generate script tags for JS files from an entry.
     *
     * @param string $entry Entry point name
     * @return string HTML script tags
     */
    public function entryScriptTags(string $entry): string
    {
        return $this->viteService->entryScriptTags($entry);
    }

    /**
     * Get the path to an image.
     *
     * @param string $path Image path
     * @param string $resourcePath Resource path (default: 'resources/images')
     * @param string|null $placeholder Placeholder image path (default: null)
     * @return string|null Image path or null if not found and no placeholder
     */
    public function image(string $path, string $resourcePath = 'resources/images', ?string $placeholder = null): ?string
    {
        return $this->viteService->image($path, $resourcePath, $placeholder);
    }

    /**
     * Get the path to a font.
     *
     * @param string $path Font path
     * @return string Font path
     */
    public function font(string $path): string
    {
        return $this->viteService->font($path);
    }
}
