<?php

namespace App\GraphQL\Queries;

use App\Tag;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Carbon;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use function route;

class TrendQuery
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $trends = Tag::trends(Carbon::now()->subYear(), 10);
        $response = [];
        foreach ($trends as $trend) {
            $response[] = [
                'name'  => "#{$trend->name}",
                'count' => $trend->count,
                'url'   => route('tags.show', [$trend->name]),
            ];
        }
        return $response;
    }
}
