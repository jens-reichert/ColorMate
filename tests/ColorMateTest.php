<?php
use ColorMate\ColorMate;

/**
 * Created by PhpStorm.
 * User: jens
 * Date: 13.08.16
 * Time: 10:09
 */
class ColorMateTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        require_once 'vendor/autoload.php';
    }

    public function test_it_converts_hex_to_rgba()
    {
        $hexColor  = '#ff0000';
        $rgbaColor = 'rgba(255,0,0,1)';
        $this->assertEquals($rgbaColor, ColorMate::make($hexColor)->toRgba());
    }

    public function test_it_converts_hex_to_rgb()
    {
        $hexColor  = '#ff0000';
        $rgbColor  = 'rgb(255,0,0)';
        $this->assertEquals($rgbColor, ColorMate::make($hexColor)->toRgb());
    }

    public function test_it_converts_hex_to_colorname()
    {
        $hexColor  = '#ff0000';
        $colorName = 'red';
        $this->assertEquals($colorName, ColorMate::make($hexColor)->toColorname());
    }

    public function test_it_converts_rgb_to_hex_from_array_integer_args()
    {
        $hexColor  = '#ff0000';
        $r = 255;
        $g = 0;
        $b = 0;
        $this->assertEquals($hexColor, ColorMate::make([$r, $g, $b])->toHex());
    }

    public function test_it_converts_rgb_to_colorname_from_array_integer_args()
    {
        $colorName = 'red';
        $r = 255;
        $g = 0;
        $b = 0;
        $this->assertEquals($colorName, ColorMate::make([$r, $g, $b])->toColorname());
    }

    public function test_it_converts_colorname_to_rgb()
    {
        $colorName = 'red';
        $rgbColor  = 'rgb(255,0,0)';
        $this->assertEquals($rgbColor, ColorMate::make($colorName)->toRgb());
    }

    public function test_it_converts_colorname_to_rgba()
    {
        $colorName  = 'red';
        $rgbaColor  = 'rgba(255,0,0,1)';
        $this->assertEquals($rgbaColor, ColorMate::make($colorName)->toRgba());
    }

    public function test_it_converts_colorname_to_hex()
    {
        $colorName  = 'red';
        $hexColor   = '#ff0000';
        $this->assertEquals($hexColor, ColorMate::make($colorName)->toHex());
    }

    public function test_it_lightens_a_color_by_steps()
    {
        $Color = new ColorMate([100,100,100]);
        $Color->lightenBySteps(10);
        $this->assertEquals('rgb(110,110,110)', $Color->getFormat('rgb'));
    }

    public function test_it_lightens_a_color_by_percent()
    {
        $Color = new ColorMate([200,200,200]);
        $Color->lightenByPercent(10);
        $this->assertEquals('rgb(220,220,220)', $Color->getFormat('rgb'));
    }

    public function test_it_limits_lightning()
    {
        $Color = new ColorMate([200,200,200]);
        $Color->lightenBySteps(56);
        $this->assertEquals('rgb(255,255,255)', $Color->getFormat('rgb'));
    }

    public function test_it_darkens_a_color_by_steps()
    {
        $Color = new ColorMate([100,100,100]);
        $Color->darkenBySteps(10);
        $this->assertEquals('rgb(90,90,90)', $Color->getFormat('rgb'));
    }

    public function test_it_darken_a_color_by_percent()
    {
        $Color = new ColorMate([200,200,200]);
        $Color->darkenByPercent(10);
        $this->assertEquals('rgb(180,180,180)', $Color->getFormat('rgb'));
    }

    public function test_it_limits_darkning()
    {
        $Color = new ColorMate([50,50,50]);
        $Color->darkenBySteps(51);
        $this->assertEquals('rgb(0,0,0)', $Color->getFormat('rgb'));
    }

    public function test_it_greyscales_a_color()
    {
        $Color = new ColorMate([100,50,150]);
        $Color->greyscale();
        $this->assertEquals('rgb(100,100,100)', $Color->getFormat('rgb'));
    }

    public function test_it_sets_alpha_for_rgba()
    {
        $hexColor  = '#ff0000';
        $rgbaColor = 'rgba(255,0,0,0.5)';
        $this->assertEquals($rgbaColor, ColorMate::make($hexColor)->setAlpha(0.5)->toRgba());
    }

    public function test_get_complementary_color()
    {
        $sourceColor = '#ff0000';
        $targetColor = '#00ffff';
        $this->assertEquals($targetColor, ColorMate::make($sourceColor)->flip()->toHex());

        $sourceColor = '#0030ff';
        $targetColor = '#ffcf00';
        $this->assertEquals($targetColor, ColorMate::make($sourceColor)->flip()->toHex());

        $sourceColor = '#b08c29';
        $targetColor = '#4f73d6';
        $this->assertEquals($targetColor, ColorMate::make($sourceColor)->flip()->toHex());
    }
}
