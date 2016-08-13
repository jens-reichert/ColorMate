<?php

namespace ColorMate;

class ColorMate
{

    private $r;
    private $g;
    private $b;
    private $alpha = 1;
    /**
     * @var string
     */
    private $outputFormat;
    /**
     * @var string
     */
    private $outputType;

    public function __construct($color = NULL, $outputFormat = 'rgb', $outputType = 'string')
    {
        $this->outputFormat = $outputFormat;
        $this->outputType   = $outputType;

        $this->initColor($color);
    }

    public static function make($color = NULL, $outputFormat = 'rgb', $outputType = 'string')
    {
        return new static($color, $outputFormat, $outputType);
    }

    public function __toString()
    {
        return $this->getFormat($this->outputFormat);
    }

    public function getFormat($format)
    {
        switch ($format){
            case 'rgb':
                return "rgb({$this->r},{$this->b},{$this->g})";
            case 'hex':
                return $this->rgbaToHex();
            case 'name':
                return $this->toColorname();
        }
    }

    public function toRgba()
    {
        return "rgba({$this->r},{$this->b},{$this->g},{$this->alpha})";
    }
    public function toRgb()
    {
        return "rgb({$this->r},{$this->b},{$this->g})";
    }
    public function toColorname()
    {
        return ColorMap::find([$this->r,$this->g,$this->b]);
    }

    public function toHex()
    {
        $r = sprintf('%02s', dechex($this->r));
        $g = sprintf('%02s', dechex($this->g));
        $b = sprintf('%02s', dechex($this->b));
        return "#".$r.$g.$b;
    }

    public function initColor($color)
    {
        if (is_string($color) && strpos($color, '#') !== false) {
            $color = trim($color, '#');
            $color = [
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2)),
            ];
        } elseif (is_string($color) && strpos($color, 'rgb') !== false) {
            $color = explode(',', trim(end(explode('(', $color)), ')'));
        }elseif (is_string($color)) {
            $color = array_values(ColorMap::get($color));
        }

        $this->r = $color[0];
        $this->g = $color[1];
        $this->b = $color[2];
        $this->alpha = isset($color[3]) ? $color[3] : 1;

        return $this;
    }

    /**
     * @param string $outputFormat
     */
    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }

    /**
     * @param string $outputType
     */
    public function setOutputType($outputType)
    {
        $this->outputType = $outputType;
    }

    public function lightenBySteps($int)
    {
        $this->r += $int;
        $this->g += $int;
        $this->b += $int;
        $this->checkMaxLimits();
        return $this;
    }

    public function darkenBySteps($int)
    {
        $this->r -= $int;
        $this->g -= $int;
        $this->b -= $int;
        $this->checkMinLimits();
        return $this;
    }

    private function checkMaxLimits()
    {
        $this->r = $this->r > 255 ? 255 : $this->r;
        $this->g = $this->g > 255 ? 255 : $this->g;
        $this->b = $this->b > 255 ? 255 : $this->b;
    }

    private function checkMinLimits()
    {
        $this->r = $this->r < 0 ? 0 : $this->r;
        $this->g = $this->g < 0 ? 0 : $this->g;
        $this->b = $this->b < 0 ? 0 : $this->b;
    }

    public function lightenByPercent($int)
    {
        $this->r += $this->r / 100 * $int;
        $this->g += $this->g / 100 * $int;
        $this->b += $this->b / 100 * $int;
        $this->checkMaxLimits();
        return $this;
    }

    public function darkenByPercent($int)
    {
        $this->r -= $this->r / 100 * $int;
        $this->g -= $this->g / 100 * $int;
        $this->b -= $this->b / 100 * $int;
        $this->checkMinLimits();
        return $this;
    }

    public function greyscale()
    {
        $avg = ($this->r + $this->g + $this->b) / 3;
        $this->r = $avg;
        $this->g = $avg;
        $this->b = $avg;
        return $this;
    }

    public function setAlpha($alphaValue)
    {
        $this->alpha = floatval($alphaValue);
        return $this;
    }

    public function flip()
    {
        $this->r = 255 - $this->r;
        $this->g = 255 - $this->g;
        $this->b = 255 - $this->b;
        $this->checkMinLimits();
        return $this;
    }
}
