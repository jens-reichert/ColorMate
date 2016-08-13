# ColorMate

This simple PHP API allows you to convert colors from hex, rgb, rgba and CSS colornames from one to each other.

## Converting colors

###### Convert a hex color into rgb, rgba, names
ColorMate::make('#ff0000')->toRgb()
ColorMate::make('#ff0000')->toRgba()
ColorMate::make('#ff0000')->toColorname()

###### Convert a rgb color into hex, rgba, names
$red   = 255;
$green = 0;
$blue  = 0;
ColorMate::make([$red, $green, $blue])->toHex()
ColorMate::make([$red, $green, $blue])->toRgba()
ColorMate::make([$red, $green, $blue])->toColorname()

###### Convert a color name into hex, rgb, rgba
ColorMate::make('red')->toHex()
ColorMate::make('red')->toRgb()
ColorMate::make('red')->toRgba()

## Modify colors

###### Lighten a color by steps
ColorMate::make('red')->lightenBySteps(10)->getFormat('rgb')

###### Lighten a color by percent
ColorMate::make('red')->lightenByPercent(10)->getFormat('rgb')

###### Darken a color by steps
ColorMate::make('red')->darkenBySteps(10)->getFormat('rgb')

###### Darken a color by percent
ColorMate::make('red')->darkenByPercent(10)->getFormat('rgb')

###### Remove saturation of a color
ColorMate::make('red')->greyscale()->getFormat('rgb')

###### Modify the opacity of a color (for rgba format)
ColorMate::make('red')->setAlpha(0.5)->getFormat('rgba')

###### Get the complementary color
ColorMate::make('red')->flip()->getFormat('rgb')
