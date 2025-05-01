<?php

declare(strict_types=1);

namespace Slim4\Vite;

use Illuminate\View\Factory;

/**
 * Blade extension for Vite integration.
 */
class BladeExtension
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
     * Register the extension with the Blade engine.
     *
     * @param Factory $blade The Blade engine
     * @return void
     */
    public function register(Factory $blade): void
    {
        // Register directives
        $blade->directive('viteAsset', [$this, 'assetDirective']);
        $blade->directive('viteEntryLinkTags', [$this, 'entryLinkTagsDirective']);
        $blade->directive('viteEntryScriptTags', [$this, 'entryScriptTagsDirective']);
        $blade->directive('viteImage', [$this, 'imageDirective']);
        $blade->directive('viteFont', [$this, 'fontDirective']);
        
        // Legacy aliases
        $blade->directive('viteCss', [$this, 'entryLinkTagsDirective']);
        $blade->directive('viteJs', [$this, 'entryScriptTagsDirective']);
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

    /**
     * Asset directive.
     *
     * @param string $expression Directive expression
     * @return string PHP code
     */
    public function assetDirective(string $expression): string
    {
        return "<?php echo \$__vite->asset($expression); ?>";
    }

    /**
     * Entry link tags directive.
     *
     * @param string $expression Directive expression
     * @return string PHP code
     */
    public function entryLinkTagsDirective(string $expression): string
    {
        return "<?php echo \$__vite->entryLinkTags($expression); ?>";
    }

    /**
     * Entry script tags directive.
     *
     * @param string $expression Directive expression
     * @return string PHP code
     */
    public function entryScriptTagsDirective(string $expression): string
    {
        return "<?php echo \$__vite->entryScriptTags($expression); ?>";
    }

    /**
     * Image directive.
     *
     * @param string $expression Directive expression
     * @return string PHP code
     */
    public function imageDirective(string $expression): string
    {
        return "<?php echo \$__vite->image($expression); ?>";
    }

    /**
     * Font directive.
     *
     * @param string $expression Directive expression
     * @return string PHP code
     */
    public function fontDirective(string $expression): string
    {
        return "<?php echo \$__vite->font($expression); ?>";
    }
}
