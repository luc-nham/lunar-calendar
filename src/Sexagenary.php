<?php

namespace LucNham\LunarCalendar;

use Exception;
use LucNham\LunarCalendar\Contracts\LunarDateTime;
use LucNham\LunarCalendar\Contracts\SexagenaryFormattable;
use LucNham\LunarCalendar\Converters\LunarGuaranteedToSexagenary;
use LucNham\LunarCalendar\Formatters\SexagenaryDefaultFormatter;
use LucNham\LunarCalendar\Resolvers\BranchTermResolver;
use LucNham\LunarCalendar\Resolvers\StemTermResolver;
use LucNham\LunarCalendar\Terms\BranchIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryIdentifier;
use LucNham\LunarCalendar\Terms\SexagenaryMilestone;
use LucNham\LunarCalendar\Terms\StemIdentifier;

/**
 * Lunar Sexagenary system
 * 
 * @property StemIdentifier $D      Stem of day
 * @property StemIdentifier $M      Stem of month
 * @property StemIdentifier $Y      Stem of year
 * @property StemIdentifier $H      Stem of hour
 * @property StemIdentifier $N      Stem of fisrt hour of day
 * @property StemIdentifier $W      Stem of week
 * @property BranchIdentifier $d    Branch of day
 * @property BranchIdentifier $m    Branch of month
 * @property BranchIdentifier $y    Branch of year
 * @property BranchIdentifier $h    Branch of current hour
 * @property BranchIdentifier $w    Branch of week
 */
class Sexagenary
{
    /**
     * Store sexagenary terms corresponding to Lunar date time
     *
     * @var SexagenaryMilestone
     */
    private SexagenaryMilestone $terms;

    /**
     * Creat new Sexagenary system
     *
     * @param LunarDateTime $lunar              Lunar date time
     * @param string $stemIdetifier             Stem identifier class, support to localization
     * @param string $branchIdentifier          Branch identifier class, support to localization
     * @param SexagenaryFormattable $formatter  Formatter
     */
    public function __construct(
        private LunarDateTime $lunar,
        private string $stemIdetifier = StemIdentifier::class,
        private string $branchIdentifier = BranchIdentifier::class,
        private SexagenaryFormattable $formatter = new SexagenaryDefaultFormatter,
    ) {
        $this->terms = (new LunarGuaranteedToSexagenary(
            lunar: $this->lunar->getGuaranteedLunarDateTime(),
            offset: $this->lunar->getOffset(),
            stemResolver: $this->createStemResolver(),
            branchResolver: $this->createBranchResolver(),
        ))->getOutput();
    }

    /**
     * Create Stem terms resolver
     *
     * @return StemTermResolver
     */
    protected function createStemResolver(): StemTermResolver
    {
        $resolve = new StemTermResolver();
        $resolve->setTargetTermClass($this->stemIdetifier);

        return $resolve;
    }

    /**
     * Create Branches terms resolver
     *
     * @return BranchTermResolver
     */
    protected function createBranchResolver(): BranchTermResolver
    {
        $resolve = new BranchTermResolver();
        $resolve->setTargetTermClass($this->branchIdentifier);

        return $resolve;
    }

    /**
     * Magic getter
     *
     * @param string $name
     * @return SexagenaryIdentifier
     */
    public function __get(string $name): SexagenaryIdentifier
    {
        if (!property_exists($this->terms, $name)) {
            throw new Exception("Target property '{$name}' dose not exists");
        }

        return $this->terms->{$name};
    }

    /**
     * Returns sexagenary terms formatted according to given format
     *
     * @param string $formatter
     * @return string
     */
    public function format(string $formatter): string
    {
        return $this->formatter->format(
            formatter: $formatter,
            terms: $this->terms
        );
    }
}
