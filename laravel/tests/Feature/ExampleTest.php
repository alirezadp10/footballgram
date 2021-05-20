<?php

namespace Tests\Feature;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use MakesGraphQLRequests;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->graphQL(/** @lang GraphQL */ '{
         mostFollower {
            name
            countFollowers
          }
        }')
                         ->assertJsonStructure([
                             'data' => [
                                 'mostFollower' => [
                                     [
                                         'name',
                                         'countFollowers',
                                     ],
                                 ],
                             ],
                         ]);
    }
}
