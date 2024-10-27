<?php

namespace LucNham\LunarCalendar\Terms;

use LucNham\LunarCalendar\Attributes\SolarTermAttribute;

#[SolarTermAttribute(
    key: 'begin_spring',
    name: 'Beginning of Spring',
    position: 0,
    ls: 315.0
)]
#[SolarTermAttribute(
    key: 'rain_water',
    name: 'Rain Water',
    position: 1,
    ls: 330.0
)]
#[SolarTermAttribute(
    key: 'awaken_insects',
    name: 'Awakening of Insects',
    position: 2,
    ls: 345.0
)]
#[SolarTermAttribute(
    key: 'spring_equinox',
    name: 'Spring Equinox',
    position: 3,
    ls: 0.0
)]
#[SolarTermAttribute(
    key: 'pure_brightness',
    name: 'Pure Brightness',
    position: 4,
    ls: 15.0
)]
#[SolarTermAttribute(
    key: 'grain_rain',
    name: 'Grain Rain',
    position: 5,
    ls: 30.0
)]
#[SolarTermAttribute(
    key: 'begin_summer',
    name: 'Beginning of Summer',
    position: 6,
    ls: 45.0
)]
#[SolarTermAttribute(
    key: 'grain_buds',
    name: 'Grain Buds',
    position: 7,
    ls: 60.0
)]
#[SolarTermAttribute(
    key: 'grain_in_ear',
    name: 'Grain in Ear',
    position: 8,
    ls: 75.0
)]
#[SolarTermAttribute(
    key: 'summer_solstice',
    name: 'Summer Solstice',
    position: 9,
    ls: 90.0
)]
#[SolarTermAttribute(
    key: 'minor_heat',
    name: 'Minor Heat',
    position: 10,
    ls: 105.0
)]
#[SolarTermAttribute(
    key: 'major_heat',
    name: 'Major heat',
    position: 11,
    ls: 120.0
)]
#[SolarTermAttribute(
    key: 'begin_autumn',
    name: 'Beginning of Autumn',
    position: 12,
    ls: 135.0
)]
#[SolarTermAttribute(
    key: 'end_of_heat',
    name: 'End of Heat',
    position: 13,
    ls: 150.0
)]
#[SolarTermAttribute(
    key: 'white_dew',
    name: 'White Dew',
    position: 14,
    ls: 165.0
)]
#[SolarTermAttribute(
    key: 'autumn_equinox',
    name: 'Autumn Equinox',
    position: 15,
    ls: 180.0
)]
#[SolarTermAttribute(
    key: 'cold_dew',
    name: 'Cold Dew',
    position: 16,
    ls: 195.0
)]
#[SolarTermAttribute(
    key: 'frost_descent',
    name: "Frost's Descent",
    position: 17,
    ls: 210.0
)]
#[SolarTermAttribute(
    key: 'begin_winter',
    name: 'Beginning of Winter',
    position: 18,
    ls: 225.0
)]
#[SolarTermAttribute(
    key: 'minor_snow',
    name: 'Minor Snow',
    position: 19,
    ls: 240.0
)]
#[SolarTermAttribute(
    key: 'major_snow',
    name: 'Major Snow',
    position: 20,
    ls: 255.0
)]
#[SolarTermAttribute(
    key: 'winter_solstice',
    name: 'Winter Solstice',
    position: 21,
    ls: 270.0
)]
#[SolarTermAttribute(
    key: 'minor_cold',
    name: 'Minor Cold',
    position: 22,
    ls: 285.0
)]
#[SolarTermAttribute(
    key: 'major_cold',
    name: 'Major Cold',
    position: 23,
    ls: 300.0
)]
/**
 * Store a Solar term
 */
readonly class SolarTermIdentifier
{
    /**
     * Additional classification
     *
     * @var string
     */
    public string $type;

    /**
     * Create a solar term
     *
     * @param string $key       Key of term
     * @param string $name      Display name
     * @param int $order        Position of the term in term group
     * @param float $ls         The solar longitude angle corresponds to the starting point
     */
    public function __construct(
        public string $key,
        public string $name,
        public int $position,
        public float $ls,
    ) {
        $this->type = $position % 2 === 0 ? 'J' : 'Z';
    }
}
