<?php

namespace App\GraphQL;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Laravel\Passport\Client;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Auth
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
    public function register($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $user = User::create([
            'name'     => $args['name'],
            'email'    => $args['email'],
            'password' => bcrypt($args['password'])
        ]);
        $user->userActions()->attach([1,3,6,7]);
        return 'registration successful';
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
    public function login($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $clientOauthData = Client::where('password_client', 1)->where('name','graphql')->first();
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('APP_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => $clientOauthData->id,
                'client_secret' => $clientOauthData->secret,
                'username'      => $args['email'],
                'password'      => $args['password'],
                'scope'         => '*',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
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
    public function logout($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        auth('api')->user()->token()->revoke();
        return "you are log out";
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
    public function refreshToken($rootValue,array $args,GraphQLContext $context,ResolveInfo $resolveInfo)
    {
        $clientOauthData = Client::where('password_client', 1)->where('name','graphql')->first();
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('APP_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $args['refreshToken'],
                'client_id'     => $clientOauthData->id,
                'client_secret' => $clientOauthData->secret,
                'scope'         => '*',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }
}
