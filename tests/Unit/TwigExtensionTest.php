<?php

declare(strict_types=1);

namespace Slim4\Vite\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim4\Vite\TwigExtension;
use Slim4\Vite\ViteService;
use Twig\TwigFunction;

class TwigExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $viteServiceMock;
    private $twigExtension;

    protected function setUp(): void
    {
        $this->viteServiceMock = Mockery::mock(ViteService::class);
        $this->twigExtension = new TwigExtension($this->viteServiceMock);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->twigExtension->getFunctions();

        $this->assertIsArray($functions);
        $this->assertContainsOnlyInstancesOf(TwigFunction::class, $functions);

        $functionNames = array_map(function (TwigFunction $function) {
            return $function->getName();
        }, $functions);

        $this->assertContains('vite_asset', $functionNames);
        $this->assertContains('vite_entry_link_tags', $functionNames);
        $this->assertContains('vite_entry_script_tags', $functionNames);
        $this->assertContains('vite_image', $functionNames);
        $this->assertContains('vite_font', $functionNames);
        $this->assertContains('vite_css', $functionNames);
        $this->assertContains('vite_js', $functionNames);
    }

    public function testAsset(): void
    {
        $this->viteServiceMock->shouldReceive('asset')
            ->once()
            ->with('resources/js/app.js')
<<<<<<< HEAD
            ->andReturn('/assets/assets/app-ABC123.js');

        $result = $this->twigExtension->asset('resources/js/app.js');

        $this->assertEquals('/assets/assets/app-ABC123.js', $result);
=======
            ->andReturn('/build/assets/app-ABC123.js');

        $result = $this->twigExtension->asset('resources/js/app.js');

        $this->assertEquals('/build/assets/app-ABC123.js', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }

    public function testEntryLinkTags(): void
    {
        $this->viteServiceMock->shouldReceive('entryLinkTags')
            ->once()
            ->with('resources/js/app.js')
<<<<<<< HEAD
            ->andReturn('<link rel="stylesheet" href="/assets/assets/app-DEF456.css">');

        $result = $this->twigExtension->entryLinkTags('resources/js/app.js');

        $this->assertEquals('<link rel="stylesheet" href="/assets/assets/app-DEF456.css">', $result);
=======
            ->andReturn('<link rel="stylesheet" href="/build/assets/app-DEF456.css">');

        $result = $this->twigExtension->entryLinkTags('resources/js/app.js');

        $this->assertEquals('<link rel="stylesheet" href="/build/assets/app-DEF456.css">', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }

    public function testEntryScriptTags(): void
    {
        $this->viteServiceMock->shouldReceive('entryScriptTags')
            ->once()
            ->with('resources/js/app.js')
<<<<<<< HEAD
            ->andReturn('<script type="module" src="/assets/assets/app-ABC123.js"></script>');

        $result = $this->twigExtension->entryScriptTags('resources/js/app.js');

        $this->assertEquals('<script type="module" src="/assets/assets/app-ABC123.js"></script>', $result);
=======
            ->andReturn('<script type="module" src="/build/assets/app-ABC123.js"></script>');

        $result = $this->twigExtension->entryScriptTags('resources/js/app.js');

        $this->assertEquals('<script type="module" src="/build/assets/app-ABC123.js"></script>', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }

    public function testImage(): void
    {
        $this->viteServiceMock->shouldReceive('image')
            ->once()
            ->with('logo.png', 'resources/images', null)
<<<<<<< HEAD
            ->andReturn('/assets/assets/logo-MNO345.png');

        $result = $this->twigExtension->image('logo.png', 'resources/images');

        $this->assertEquals('/assets/assets/logo-MNO345.png', $result);
=======
            ->andReturn('/build/assets/logo-MNO345.png');

        $result = $this->twigExtension->image('logo.png', 'resources/images');

        $this->assertEquals('/build/assets/logo-MNO345.png', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }

    public function testImageWithPlaceholder(): void
    {
        $this->viteServiceMock->shouldReceive('image')
            ->once()
            ->with('logo.png', 'resources/images', 'half-sphere.svg')
<<<<<<< HEAD
            ->andReturn('/assets/assets/logo-MNO345.png');

        $result = $this->twigExtension->image('logo.png', 'resources/images', 'half-sphere.svg');

        $this->assertEquals('/assets/assets/logo-MNO345.png', $result);
=======
            ->andReturn('/build/assets/logo-MNO345.png');

        $result = $this->twigExtension->image('logo.png', 'resources/images', 'half-sphere.svg');

        $this->assertEquals('/build/assets/logo-MNO345.png', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }

    public function testImageNotFound(): void
    {
        $this->viteServiceMock->shouldReceive('image')
            ->once()
            ->with('non-existent.png', 'resources/images', null)
            ->andReturn(null);

        $result = $this->twigExtension->image('non-existent.png', 'resources/images');

        $this->assertNull($result);
    }

    public function testFont(): void
    {
        $this->viteServiceMock->shouldReceive('font')
            ->once()
            ->with('custom.woff2')
<<<<<<< HEAD
            ->andReturn('/assets/assets/custom-PQR678.woff2');

        $result = $this->twigExtension->font('custom.woff2');

        $this->assertEquals('/assets/assets/custom-PQR678.woff2', $result);
=======
            ->andReturn('/build/assets/custom-PQR678.woff2');

        $result = $this->twigExtension->font('custom.woff2');

        $this->assertEquals('/build/assets/custom-PQR678.woff2', $result);
>>>>>>> 5b53ad4c3b27791786b4c9ae79697f3f047fce50
    }
}
