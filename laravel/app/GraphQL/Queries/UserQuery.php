<?php

namespace App\GraphQL\Queries;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Pagination\PaginatorField;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserQuery
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
    public function all($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $users = new User;

        if (isset($args['id'])) {
            $users = $users->where('id',$args['id']);
        }

        if (isset($args['name'])) {
            $users = $users->where('name',$args['name']);
        }

        if (isset($args['email'])) {
            $users = $users->where('email',$args['email']);
        }

        if (isset($args['username'])) {
            $users = $users->where('username',$args['username']);
        }

        if (isset($args['mobile'])) {
            $users = $users->where('mobile',$args['mobile']);
        }

        $users = $users->paginate(
            isset($args['first']) ? $args['first'] : 3,
            ['*'],
            'page',
            isset($args['page']) ? $args['page'] : 1
        );

        $response['data']          = $this->paginatorField->dataResolver($users);
        $response['paginatorInfo'] = $this->paginatorField->paginatorInfoResolver($users);


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
    public function mostFollower($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $users    = User::getUsersHaveMustFollower(5);
        $response = [];
        foreach ($users as $user) {
            $response[] = [
                'name'            => $user->name,
                'countFollowers'  => $user->count_followers,
                'countFollowings' => $user->count_followings,
                'countPosts'      => $user->count_posts,
                'avatar'          => $user->avatar ? Storage::url($user->avatar) : '/images/userPhoto.png',
                'url'             => route('users.show',[
                    'id'   => $user->id,
                    'name' => $user->name,
                ]),
            ];
        }
        return $response;
    }

}
