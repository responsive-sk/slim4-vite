<?php

declare(strict_types=1);

namespace Slim4\Vite\Tests\Unit;

use Illuminate\View\Factory;
use PHPUnit\Framework\TestCase;
use Slim4\Vite\BladeExtension;
use Slim4\Vite\ViteService;

class BladeExtensionTest extends TestCase
{
    public function testRegister(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Create a mock of Factory
        $blade = $this->createMock(Factory::class);
        
        // Expect directive to be called for each directive
        $blade->expects($this->exactly(7))
            ->method('directive')
            ->withConsecutive(
                ['viteAsset', $this->anything()],
                ['viteEntryLinkTags', $this->anything()],
                ['viteEntryScriptTags', $this->anything()],
                ['viteImage', $this->anything()],
                ['viteFont', $this->anything()],
                ['viteCss', $this->anything()],
                ['viteJs', $this->anything()]
            );
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
        // Register extension
        $extension->register($blade);
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
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
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
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
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
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
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
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
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
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
        // Call font method
        $result = $extension->font('font.woff2');
        
        // Assert result
        $this->assertEquals('/build/assets/fonts/font.woff2', $result);
    }
    
    public function testDirectives(): void
    {
        // Create a mock of ViteService
        $viteService = $this->createMock(ViteService::class);
        
        // Create BladeExtension
        $extension = new BladeExtension($viteService);
        
        // Test directives
        $this->assertEquals('<?php echo $__vite->asset(\'app.js\'); ?>', $extension->assetDirective('\'app.js\''));
        $this->assertEquals('<?php echo $__vite->entryLinkTags(\'app\'); ?>', $extension->entryLinkTagsDirective('\'app\''));
        $this->assertEquals('<?php echo $__vite->entryScriptTags(\'app\'); ?>', $extension->entryScriptTagsDirective('\'app\''));
        $this->assertEquals('<?php echo $__vite->image(\'logo.png\'); ?>', $extension->imageDirective('\'logo.png\''));
        $this->assertEquals('<?php echo $__vite->font(\'font.woff2\'); ?>', $extension->fontDirective('\'font.woff2\''));
    }
}
