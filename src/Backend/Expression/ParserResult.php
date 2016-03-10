<?php
namespace Backend\Expression;

/**
 * Parse result.
 */
class ParserResult
{
    /**
     * Tokens.
     *
     * @var array Tokens
     */
    private $tokens = array();

    /**
     * Create instance.
     *
     * @return ParserResult
     */
    public static function create()
    {
        return new self;
    }

    /**
     * Add token.
     *
     * @param string $token
     *
     * @return $this
     */
    public function addToken($token) {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * Return tokens.
     *
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
