<?php

namespace App\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class TasksStatusesControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexReturnsAllTasksStatuses()
    {
        $response = $this->get("/status");

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertIsObject($response);
    }
}