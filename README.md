# lunar-calendar
PHP Lunar Calendar - Library to help calculate Lunar Calendar.

PHP Lunar Calendar includes important features:
    - Convert an Gregorian date time to Lunar date time.
    - Convert Lunar date time to Lunar sexagenary
    - Convert Gregorian date time to Solar term

## Basic usage
```php
// Create custom Gregorian date time input
$dateTimeInput = BaseDateTime::create()
    ->setDate(20, 10, 2021)
    ->setTime(18, 30, 00)
    ->setTimeZone(7);

// Crete date time from string, see https://www.php.net/manual/en/class.datetime.php
// $dateTimeInput = BaseDateTime::createFromString('now');

// Get Lunar date time from input
$lunarDateTime = new LunarDateTime($dateTimeInput);
var_dump($lunarDateTime->datetime());                           // Output object
$lunarDateTime->format($lunarDateTime::BASE_VIETNAMES_FORMAT);  // Readable date time format

// Solar term
$solarTerm = new SolarTerm($dateTimeInput);
var_dump($solarTerm->getTerm());            // Solar term from input
var_dump($solarTerm->getDateTimeBegin());   // Gregorian date time at Begin point of the Term

// Sexagenary
$sexagenary = new LunarSexagenary($lunarDateTime->datetime());
var_dump($sexagenary->getTerm($sexagenary::HEAVENLY_STEM_DAY));  // Heavenly branch for Lunar day
var_dump($sexagenary->getTerm($sexagenary::EARTHLY_BRANCH_DAY)); // Earthly branch for Lunar day

$sexagenary->format($sexagenary::BASE_VIETNAMES_FORMAT);         // Readable format
```

## System Requirements
PHP 8.0 or above

## Reference
https://www.informatik.uni-leipzig.de/~duc/amlich/calrules.html
https://clearskytonight.com/projects/astronomycalculator/sun/sunlongitude.html
http://tutorialspots.com/php-some-method-of-determining-the-suns-longitude-part-2-2479.html


