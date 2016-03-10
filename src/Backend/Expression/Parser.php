<?php
namespace Backend\Expression;

/**
 * Expression Parser.
 */
class Parser
{
    const INT_REGEXP = '(?:[+-]?[1-9][0-9]*)';
    const FLOAT_REGEXP = '(?:[+-]?0?\.[0-9]+)';
    const NUMBER_REGEXP = '(?:0|%INT_REGEXP%|%FLOAT_REGEXP%)';
    const EXPRESSION_REGEXP = '/(%NUMBER_REGEXP%)([\+\-\*\/])(%NUMBER_REGEXP%)/';

    /**
     * Keeps regexp with template replacement.
     *
     * @var string
     */
    private $preparedRegexp;

    /**
     * Get expression regexp.
     *
     * @return string
     */
    protected function getRegexp()
    {
        if (!$this->preparedRegexp) {
            $regexp = self::EXPRESSION_REGEXP;

            $regexp = str_replace('%NUMBER_REGEXP%', self::NUMBER_REGEXP, $regexp);
            $regexp = str_replace(
                array('%INT_REGEXP%', '%FLOAT_REGEXP%'),
                array(self::INT_REGEXP, self::FLOAT_REGEXP),
                $regexp
            );

            $this->preparedRegexp = $regexp;
        }

        return $this->preparedRegexp;
    }

    /**
     * Parses an expression.
     *
     * @param string $expression Any expression.
     *
     * @return $this
     */
    public function parse($expression)
    {
        if (preg_match($this->getRegexp(), $expression, $m)) {
            return ParserResult::create()
                ->addToken($m[1])
                ->addToken($m[2])
                ->addToken($m[3]);
        }

        throw new ParseException;
    }
}

