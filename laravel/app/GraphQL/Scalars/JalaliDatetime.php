<?php

namespace App\GraphQL\Scalars;

use GraphQL\Type\Definition\ScalarType;
use Morilog\Jalali\jDate;

/**
 * Read more about scalars here http://webonyx.github.io/graphql-php/type-system/scalar-types/
 */
class JalaliDatetime extends ScalarType
{
    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     */
    public function serialize($value)
    {
        return jDate::forge($value)
                    ->format('%Y-%m-%d %H:%M:%S');
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param mixed $value
     * @return mixed
     */
    public function parseValue($value)
    {
        return jDate::forge($value)
                    ->format('%Y-%m-%d %H:%M:%S');
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * E.g.
     * {
     *   user(email: "user@example.com")
     * }
     *
     * @param \GraphQL\Language\AST\Node $valueNode
     * @param mixed[]|null $variables
     * @return mixed
     */
    public function parseLiteral($valueNode,?array $variables = NULL)
    {
        return jDate::forge($valueNode->value)
                    ->format('%Y-%m-%d %H:%M:%S');
    }
}
