<?php

namespace App\Tests\Unit;

use App\Models\Building;
use App\Models\TasksStatuses;
use App\Models\User;
use App\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FilterControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testFilterTasksWithoutFilter()
    {
        $response = $this->get("/filters");

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFilterTasksWithStartDate()
    {
        /* 
        * $random has a random date from last year
        */
        $random = Carbon::today()->subDays(rand(0, 365))->format('Y-m-d');
        $response = $this->get("/filters?date_start=".$random);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFailsFilterTasksWithWrongFormatStartDate()
    {
        /* 
        * $random has a random date from last year
        */
        $random = Carbon::today()->subDays(rand(0, 365))->format('d/m/Y');
        $response = $this->get("/filters?date_start=".$random);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFilterTasksWithEndedDate()
    {
        /* 
        * $random has a random date from last year
        */
        $random = Carbon::today()->subDays(rand(0, 365))->format('Y-m-d');
        $response = $this->get("/filters?date_end=".$random);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFilterTasksWithStartAndEndDate()
    {
        /* 
        * $random has a random date from last year
        */
        $random = Carbon::today()->subDays(rand(0, 365))->format('Y-m-d');
        $now = Carbon::today()->format('Y-m-d');
        $response = $this->get("/filters?date_start=".$random."&date_end=".$now);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFailsFilterTasksWithWrongFormatEndDate()
    {
        /* 
        * $random has a random date from last year
        */
        $random = Carbon::today()->subDays(rand(0, 365))->format('d/m/Y');
        $response = $this->get("/filters?date_end=".$random);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFilterTasksWithTheIdOfStatus()
    {
        $status = TasksStatuses::inRandomOrder()->first();
        $response = $this->get("/filters?status=".$status->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFilterTasksWithTheNameOfStatus()
    {
        $status = TasksStatuses::inRandomOrder()->first();
        $response = $this->get("/filters?status=".$status->name);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFilterTasksWithThPartNameOfStatus()
    {
        $status = TasksStatuses::inRandomOrder()->first();
        /* 
        * Uses substr to get only a part of status name
        */
        $partStatus = substr($status->name, 0, 3);
        $response = $this->get("/filters?status=".$partStatus);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFilterTasksWithCreatorQuery()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%Owner%")->first();
        $user = User::where("user_type_id", $creator_type->id)->inRandomOrder()->first();
        
        $response = $this->get("/filters?creator=".$user->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFailsFilterTasksWithANameOfCreator()
    {
        $user = User::inRandomOrder()->first();
        
        $response = $this->get("/filters?creator=".$user->name);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFailsFilterTasksWithANonexistentIdOfCreator()
    {        
        $response = $this->get("/filters?creator=0");

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFilterTasksWithResponsibleQuery()
    {
        $user = User::inRandomOrder()->first();
        $response = $this->get("/filters?responsible=".$user->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFailsFilterTasksWithANameOfResponsible()
    {
        $user = User::inRandomOrder()->first();
        $response = $this->get("/filters?responsible=".$user->name);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFailsFilterTasksWithANonexistentIdOfResponsible()
    {        
        $response = $this->get("/filters?responsible=0");

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFilterTasksWithBuildingId()
    {
        $building = Building::inRandomOrder()->first();
        $response = $this->get("/filters?building=".$building->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testFailsFilterTasksWithANameOfABuilding()
    {
        $building = Building::inRandomOrder()->first();
        $response = $this->get("/filters?building=".$building->name);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

    public function testFailsFilterTasksWithANonexistentIdOfBuilding()
    {        
        $response = $this->get("/filters?building=0");

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"errors"', $response->getContent());
    }

}