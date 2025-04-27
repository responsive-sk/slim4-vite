<?php

declare(strict_types=1);

namespace ResponsiveSk\Vite\Tests\Unit;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Slim4\Root\PathsInterface;
use ResponsiveSk\Vite\ViteService;

class ViteServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $pathsMock;
    private $manifestPath;
    private $publicPath;

    protected function setUp(): void
    {
        $this->pathsMock = Mockery::mock(PathsInterface::class);
        $this->publicPath = sys_get_temp_dir() . '/vite-test-' . uniqid();
        $this->manifestPath = $this->publicPath . '/build/manifest.json';

        // Create fixtures directory if it doesn't exist
        if (!is_dir($this->publicPath . '/build')) {
            mkdir($this->publicPath . '/build', 0775, true);
        }

        // Create a sample manifest.json file for testing
        $manifest = [
            'resources/js/app.js' => [
                'file' => 'assets/app-ABC123.js',
                'css' => ['assets/app-DEF456.css'],
                'imports' => ['_chunk-GHI789.js']
            ],
            '_chunk-GHI789.js' => [
                'file' => 'assets/_chunk-GHI789-JKL012.js'
            ],
            'resources/images/logo.png' => [
                'file' => 'assets/logo-MNO345.png'
            ],
            'resources/fonts/custom.woff2' => [
                'file' => 'assets/custom-PQR678.woff2'
            ]
        ];

        file_put_contents(
            $this->manifestPath,
            json_encode($manifest)
        );

        // Mock the paths service
        $this->pathsMock->shouldReceive('getPublicPath')
            ->andReturn($this->publicPath);
    }

    protected function tearDown(): void
    {
        // Clean up the test manifest file
        if (file_exists($this->manifestPath)) {
            unlink($this->manifestPath);
        }

        // Remove the build directory if it exists
        if (is_dir($this->publicPath . '/build')) {
            rmdir($this->publicPath . '/build');
        }

        // Remove the fixtures directory if it exists
        if (is_dir($this->publicPath)) {
            rmdir($this->publicPath);
        }

        Mockery::close();
    }

    public function testAssetInProductionMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->asset('resources/js/app.js');

        $this->assertEquals('/build/assets/app-ABC123.js', $result);
    }

    public function testAssetInDevelopmentMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', true);

        $result = $viteService->asset('resources/js/app.js');

        $this->assertEquals('http://localhost:5173/resources/js/app.js', $result);
    }

    public function testEntryLinkTagsInProductionMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->entryLinkTags('resources/js/app.js');

        $this->assertStringContainsString('<link rel="stylesheet" href="/build/assets/app-DEF456.css">', $result);
    }

    public function testEntryLinkTagsInDevelopmentMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', true);

        $result = $viteService->entryLinkTags('resources/js/app.js');

        $this->assertEquals('', $result);
    }

    public function testEntryScriptTagsInProductionMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->entryScriptTags('resources/js/app.js');

        $this->assertStringContainsString('<script type="module" src="/build/assets/_chunk-GHI789-JKL012.js"></script>', $result);
        $this->assertStringContainsString('<script type="module" src="/build/assets/app-ABC123.js"></script>', $result);
    }

    public function testEntryScriptTagsInDevelopmentMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', true);

        $result = $viteService->entryScriptTags('resources/js/app.js');

        $this->assertEquals('<script type="module" src="http://localhost:5173/resources/js/app.js"></script>', $result);
    }

    public function testImageInProductionMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->image('logo.png', 'resources/images');

        $this->assertEquals('/build/assets/logo-MNO345.png', $result);
    }

    public function testImageInDevelopmentMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', true);

        $result = $viteService->image('logo.png', 'resources/images');

        $this->assertEquals('http://localhost:5173/resources/images/logo.png', $result);
    }

    public function testImageWithPlaceholder(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        // Test with a non-existent image but with placeholder
        $result = $viteService->image('non-existent.png', 'resources/images', 'half-sphere.svg');

        $this->assertEquals('/assets/images/half-sphere.svg', $result);
    }

    public function testImageWithoutPlaceholder(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        // Test with a non-existent image and no placeholder
        $result = $viteService->image('non-existent.png', 'resources/images', null);

        $this->assertNull($result);
    }

    public function testFontInProductionMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->font('custom.woff2');

        $this->assertEquals('/build/assets/custom-PQR678.woff2', $result);
    }

    public function testFontInDevelopmentMode(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', true);

        $result = $viteService->font('custom.woff2');

        $this->assertEquals('http://localhost:5173/resources/fonts/custom.woff2', $result);
    }

    public function testGetManifest(): void
    {
        $viteService = new ViteService($this->pathsMock, 'build', false);

        $result = $viteService->getManifest();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('resources/js/app.js', $result);
    }
}
