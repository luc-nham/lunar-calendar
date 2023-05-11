<?php

use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

$lunar = new LunarDateTime('2023-04-30 13:00 +07:00');
$sexagenary = new LunarSexagenary($lunar);