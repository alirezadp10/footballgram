<?php

namespace App\GraphQL\Queries;

use App\Image;
use App\News;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Log;
use Nuwave\Lighthouse\Pagination\PaginatorField;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class NewsQuery
{
    public $paginatorField;

    public function __construct(PaginatorField $paginatorField)
    {
        $this->paginatorField = $paginatorField;
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function news($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $news = new News();

        if (isset($args['id'])) {
            $news = $news->where('id',$args['id']);
        }

        if (isset($args['slug'])) {
            $news = $news->where('slug',$args['slug']);
        }

        if (isset($args['tag'])){
            $news = $news->whereHas('tags', function($query) use ($args) {
                $query->where('name', $args['tag']);
            });
        }


        $news = $news->paginate(isset($args['first']) ? $args['first'] : 10,['*'],'page',isset($args['page'])
            ? $args['page'] : 1);

        $response['data']          = $this->paginatorField->dataResolver($news);
        $response['paginatorInfo'] = $this->paginatorField->paginatorInfoResolver($news);

        return $response;
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function hotNews($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $news = News::orderBy('view','DESC')
                    ->where('news.status','release')
                    ->whereNull('news.deleted_at')
                    ->paginate(isset($args['first']) ? $args['first'] : 10,['*'],'page',isset($args['page'])
                        ? $args['page'] : 1);


        $response['data']          = $this->paginatorField->dataResolver($news);
        $response['paginatorInfo'] = $this->paginatorField->paginatorInfoResolver($news);

        return $response;
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function lastNews($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $news = News::orderBy('id','DESC')
                    ->where('news.status','release')
                    ->whereNull('news.deleted_at')
                    ->paginate(isset($args['first']) ? $args['first'] : 10,['*'],'page',isset($args['page'])
                        ? $args['page'] : 1);


        $response['data']          = $this->paginatorField->dataResolver($news);
        $response['paginatorInfo'] = $this->paginatorField->paginatorInfoResolver($news);

        return $response;
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function slider($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        return DB::table('slider')
                 ->orderBy('order','asc')
                 ->orderBy('slider.updated_at','desc')
                 ->select('news_id','main_title','secondary_title','slug','first_tag','second_tag','third_tag','forth_tag')
                 ->get();
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function chiefChoice($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        return DB::table('chief_choices')
                 ->select('main_title','secondary_title','news_id','slug')
                 ->get();
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function image($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        return Image::where('imageable_type','=','App\News')
                    ->where('imageable_id','=',$rootValue->news_id)
                    ->get();
    }

    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function trends($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $trends = $this->tagsRepository->trends(Carbon::now()
                                                      ->subYear(), 10);
        $response['trends'] = [];
        foreach ($trends as $trend) {
            $response['trends'][] = [
                'name'  => "#{$trend->name}",
                'count' => $trend->count,
                'url'   => route('tags.show', [$trend->name]),
            ];
        }

    }
}
