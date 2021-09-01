<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class SexagenaryTermFormatter extends AbstractTermFormatter
{
    protected int $offset;
    protected string $key;
    protected string $label;

    public function __construct(int $offset, string $key = '', string $label = '')
    {
        $this->offset   = $offset;
        $this->key      = $key;
        $this->label    = $label;
    }

    /**
     * Find a attribute with offset
     *
     * @param integer $offset
     * @param array $attrs      custom attributes
     * @return mixed
     */
    protected function _findAttr(int $offset, array $attrs): mixed
    {
        foreach($attrs as $compareOffset => $att) {
            if($offset == $compareOffset) {
                return $att;
            }
        }

        throw new \Exception("No attribute found with offset $offset.");
    }
}