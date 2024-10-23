<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Store the sexagenary terms at a milestone
 */
readonly class SexagenaryMilestone
{
    /**
     * Create new storage
     *
     * @param StemIdentifier $D     Day stem
     * @param StemIdentifier $M     Month stem
     * @param StemIdentifier $Y     Year stem
     * @param StemIdentifier $H     Hour stem
     * @param StemIdentifier $W     Stem of week leader, always is Giap
     * @param StemIdentifier $N     Stem of fist hour of lunar day
     * @param BranchIdentifier $d   Day branch
     * @param BranchIdentifier $m   Month branch
     * @param BranchIdentifier $y   Year branch
     * @param BranchIdentifier $h   Hour branch    
     * @param BranchIdentifier $w   Week branch
     */
    public function __construct(
        public StemIdentifier $D,
        public StemIdentifier $M,
        public StemIdentifier $Y,
        public StemIdentifier $H,
        public StemIdentifier $W,
        public StemIdentifier $N,
        public BranchIdentifier $d,
        public BranchIdentifier $m,
        public BranchIdentifier $y,
        public BranchIdentifier $h,
        public BranchIdentifier $w
    ) {}
}
