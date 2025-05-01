<?php

declare(strict_types=1);

namespace Slim4\Vite\Tests\Unit;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Slim4\Vite\PlatesExtension;
use Slim4\Vite\ViteService;

class PlatesExtensionTest extends TestCase
{
    public function testRegister(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Create a mock of Engine
        $engine = $this->createMock(Engine::class);
        
        // Expect registerFunction to be called for each function
        $engine->expects($this->exactly(7))
            ->method('registerFunction')
            ->withConsecutive(
                ['vite_asset', $this->anything()],
                ['vite_entry_link_tags', $this->anything()],
                ['vite_entry_script_tags', $this->anything()],
                ['vite_image', $this->anything()],
                ['vite_font', $this->anything()],
                ['vite_css', $this->anything()],
                ['vite_js', $this->anything()]
            );
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Register extension
        $extension->register($engine);
    }
    
    public function testAsset(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Configure the mock
        $viteService->expects($this->once())
            ->method('asset')
            ->with('app.js')
            ->willReturn('/build/assets/app.js');
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Call asset method
        $result = $extension->asset('app.js');
        
        // Assert result
        $this->assertEquals('/build/assets/app.js', $result);
    }
    
    public function testEntryLinkTags(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Configure the mock
        $viteService->expects($this->once())
            ->method('entryLinkTags')
            ->with('app')
            ->willReturn('<link rel="stylesheet" href="/build/assets/app.css">');
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Call entryLinkTags method
        $result = $extension->entryLinkTags('app');
        
        // Assert result
        $this->assertEquals('<link rel="stylesheet" href="/build/assets/app.css">', $result);
    }
    
    public function testEntryScriptTags(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Configure the mock
        $viteService->expects($this->once())
            ->method('entryScriptTags')
            ->with('app')
            ->willReturn('<script type="module" src="/build/assets/app.js"></script>');
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Call entryScriptTags method
        $result = $extension->entryScriptTags('app');
        
        // Assert result
        $this->assertEquals('<script type="module" src="/build/assets/app.js"></script>', $result);
    }
    
    public function testImage(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Configure the mock
        $viteService->expects($this->once())
            ->method('image')
            ->with('logo.png', 'resources/images', null)
            ->willReturn('/build/assets/images/logo.png');
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Call image method
        $result = $extension->image('logo.png');
        
        // Assert result
        $this->assertEquals('/build/assets/images/logo.png', $result);
    }
    
    public function testFont(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Configure the mock
        $viteService->expects($this->once())
            ->method('font')
            ->with('font.woff2')
            ->willReturn('/build/assets/fonts/font.woff2');
        
        // Create PlatesExtension
        $extension = new PlatesExtension($viteService);
        
        // Call font method
        $result = $extension->font('font.woff2');
        
        // Assert result
        $this->assertEquals('/build/assets/fonts/font.woff2', $result);
    }
}
