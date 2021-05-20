<?php

namespace App\GraphQL\Mutations;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserMutation
{
    /**
     * Return a value for the field.
     *
     * @param null $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[] $args The arguments that were passed into the field.
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context Arbitrary data that is shared between all fields of a single query.
     * @param \GraphQL\Type\Definition\ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function create($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $user = User::create([
            'name'     => $args['name'],
            'email'    => $args['email'],
            'password' => Hash::make($args['password']),
        ]);
        $user->userActions()->attach([1,3,6,7]);
        return $user;
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
    public function update($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $user = User::find($args['id']);

        $images = $user->images()->first();

        try {
            DB::beginTransaction();

            $user->update([
                'name'     => $args['info']['name'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('update profile fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . ' in file: ' . $e->getFile());
            return response()->json([
                'message' => 'update profile fails',
            ], 500);
        }

        if (request('avatar') && !is_null($images)) {
            Storage::delete($images->original);
            Storage::delete($images->xs);
            Storage::delete($images->sm);
            Storage::delete($images->md);
            Storage::delete($images->lg);
        }

        return $user;
    }
}
