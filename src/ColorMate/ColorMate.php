<?php

namespace ColorMate;

/**
 * Class ColorMate
 * @package ColorMate
 */
class ColorMate
{

    /**
     * @var
     */
    private $r;
    /**
     * @var
     */
    private $g;
    /**
     * @var
     */
    private $b;
    /**
     * @var int
     */
    private $alpha = 1;
    /**
     * @var string
     */
    private $outputFormat;
    /**
     * @var string
     */
    private $outputType;

    /**
     * ColorMate constructor.
     * @param null $color
     * @param string $outputFormat
     * @param string $outputType
     */
    public function __construct($color = NULL, $outputFormat = 'rgb', $outputType = 'string')
    {
        $this->outputFormat = $outputFormat;
        $this->outputType   = $outputType;

        $this->initColor($color);
    }

    /**
     * @param null $color
     * @param string $outputFormat
     * @param string $outputType
     * @return static
     */
    public static function make($color = NULL, $outputFormat = 'rgb', $outputType = 'string')
    {
        return new static($color, $outputFormat, $outputType);
    }

    /**
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->getFormat($this->outputFormat);
    }

    /**
     * @param $format
     * @return mixed|string
     */
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

    /**
     * @return string
     */
    public function toRgba()
    {
        return "rgba({$this->r},{$this->b},{$this->g},{$this->alpha})";
    }

    /**
     * @return string
     */
    public function toRgb()
    {
        return "rgb({$this->r},{$this->b},{$this->g})";
    }

    /**
     * @return mixed
     */
    public function toColorname()
    {
        return ColorMap::find([$this->r,$this->g,$this->b]);
    }

    /**
     * @return string
     */
    public function toHex()
    {
        $r = sprintf('%02s', dechex($this->r));
        $g = sprintf('%02s', dechex($this->g));
        $b = sprintf('%02s', dechex($this->b));
        return "#".$r.$g.$b;
    }

    /**
     * @param $color
     * @return $this
     */
    public function initColor($color)
    {
        if ($this->isHexColor($color)) {
            $color = trim($color, '#');
            $color = [
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2)),
            ];
        } elseif ($this->isRgbColor($color)) {
            $color = explode(',', trim(end(explode('(', $color)), ')'));
        }elseif ($this->isColorname($color)) {
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

    /**
     * @param $int
     * @return $this
     */
    public function lightenBySteps($int)
    {
        $this->r += $int;
        $this->g += $int;
        $this->b += $int;
        $this->checkMaxLimits();
        return $this;
    }

    /**
     * @param $int
     * @return $this
     */
    public function darkenBySteps($int)
    {
        $this->r -= $int;
        $this->g -= $int;
        $this->b -= $int;
        $this->checkMinLimits();
        return $this;
    }

    /**
     *
     */
    private function checkMaxLimits()
    {
        $this->r = $this->r > 255 ? 255 : $this->r;
        $this->g = $this->g > 255 ? 255 : $this->g;
        $this->b = $this->b > 255 ? 255 : $this->b;
    }

    /**
     *
     */
    private function checkMinLimits()
    {
        $this->r = $this->r < 0 ? 0 : $this->r;
        $this->g = $this->g < 0 ? 0 : $this->g;
        $this->b = $this->b < 0 ? 0 : $this->b;
    }

    /**
     * @param $int
     * @return $this
     */
    public function lightenByPercent($int)
    {
        $this->r += $this->r / 100 * $int;
        $this->g += $this->g / 100 * $int;
        $this->b += $this->b / 100 * $int;
        $this->checkMaxLimits();
        return $this;
    }

    /**
     * @param $int
     * @return $this
     */
    public function darkenByPercent($int)
    {
        $this->r -= $this->r / 100 * $int;
        $this->g -= $this->g / 100 * $int;
        $this->b -= $this->b / 100 * $int;
        $this->checkMinLimits();
        return $this;
    }

    /**
     * @return $this
     */
    public function greyscale()
    {
        $avg = ($this->r + $this->g + $this->b) / 3;
        $this->r = $avg;
        $this->g = $avg;
        $this->b = $avg;
        return $this;
    }

    /**
     * @param $alphaValue
     * @return $this
     */
    public function setAlpha($alphaValue)
    {
        $this->alpha = floatval($alphaValue);
        return $this;
    }

    /**
     * @return $this
     */
    public function flip()
    {
        $this->r = 255 - $this->r;
        $this->g = 255 - $this->g;
        $this->b = 255 - $this->b;
        $this->checkMinLimits();
        return $this;
    }

    /**
     * @param $color
     * @return bool
     */
    private function isHexColor($color)
    {
        return is_string($color) && strpos($color, '#') !== false;
    }

    /**
     * @param $color
     * @return bool
     */
    private function isRgbColor($color)
    {
        return is_string($color) && strpos($color, 'rgb') !== false;
    }

    /**
     * @param $color
     * @return bool
     */
    private function isColorname($color)
    {
        return is_string($color);
    }
}
