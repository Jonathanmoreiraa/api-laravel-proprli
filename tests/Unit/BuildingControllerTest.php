<?php

namespace App\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class BuildingControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexReturnsAllBuildings()
    {
        $response = $this->get("/buildings");

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertIsObject($response);
    }
}