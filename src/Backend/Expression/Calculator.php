<?php
namespace Backend\Expression;

/**
 * This class calculates an expression.
 */
class Calculator
{
    /**
     * Calculating parser result.
     *
     * @param ParserResult $parserResult Parser result
     *
     * @return mixed
     */
    public function calculate(ParserResult $parserResult)
    {
        $tokens = $parserResult->getTokens();

        if (count($tokens) != 3) {
            throw new \InvalidArgumentException;
        }

        // Big first number
        if (!filter_var($tokens[0], FILTER_VALIDATE_INT)
            && !filter_var($tokens[0], FILTER_VALIDATE_FLOAT)
        ) {
            throw new EvaluateException('Big first number.');
        }

        // Big second number
        if (!filter_var($tokens[2], FILTER_VALIDATE_INT)
            && !filter_var($tokens[2], FILTER_VALIDATE_FLOAT)
        ) {
            throw new EvaluateException('Big second number.');
        }

        switch($tokens[1]) {
            case '+':
                return $tokens[0] + $tokens[2];
            case '-':
                return $tokens[0] - $tokens[2];
            case '*':
                return $tokens[0] * $tokens[2];
            case '/':
                if ($tokens[2] == 0) {
                    throw new EvaluateException('Division by zero');
                }

                return $tokens[0] / $tokens[2];
        }

        throw new \InvalidArgumentException;
    }
}

