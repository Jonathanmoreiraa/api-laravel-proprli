<?php

namespace App\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexReturnsAllUsers()
    {
        $response = $this->get("/users");

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertIsObject($response);
    }
}