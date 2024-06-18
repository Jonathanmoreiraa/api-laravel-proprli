<?php

namespace App\Tests\Unit;

use App\Http\Controllers\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Building;
use App\Models\Task;
use App\Models\TasksStatuses;
use App\Models\User;
use App\Models\UserTypes;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetTasksByBuildingId()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->count(3)->create(['building_id' => $building[0]->id]);

        $response = $this->get('/tasks/'.$building[0]->id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data":[{', $response->getContent());
    }

    public function testCanCreateTaskWithValidData()
    {
        $creator_type = UserTypes::where("name", 'ilike', 'owner')->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'New Task',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertStringContainsString('"data":', $response->getContent());
    }

    public function testFailsToCreateTaskWithNonOwnerCreator()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%team%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'New Task',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutTitle()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutDescription()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'New Task',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutCreatorId()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'New Task',
            'description' => 'This is a description to a new task!',
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutStatusId()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'New Task',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutBuildingId()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $data = [
            'title' => 'New Task',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithoutAssigneeId()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);
        
        $building = Building::factory()->create(['user_id' => $creator->id]);

        $data = [
            'title' => 'New task',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testFailsToCreateTaskWithBigTitle()
    {
        $creator_type = UserTypes::where("name", 'ilike', "%owner%")->first();
        $assignee_type = UserTypes::where("name", 'ilike', 'Team Member')->first();
        $status = TasksStatuses::where("name", 'ilike', 'open')->first();

        $creator = User::factory()->create(['user_type_id' => $creator_type->id]);
        $assignee = User::factory()->create(['user_type_id' => $assignee_type->id]);

        $building = Building::factory()->create(['user_id' => $creator->id]);
        $data = [
            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultricies ac ex in consequat. Duis euismod enim velit, eget ullamcorper tellus aliquam eu. Etiam tortor est, bibendum eget aliquam at, congue eget odio. Mauris at orci nulla. Morbi eu luctus risus. Donec ullamcorper a dui id laoreet. Donec a odio facilisis tellus cursus pellentesque. Nullam tincidunt euismod neque posuere dictum. Aliquam eleifend dignissim tortor aliquam efficitur.',
            'description' => 'This is a description to a new task!',
            'creator_id' => $creator->id,
            'status_id' => $status->id,
            'building_id' => $building->id,
            'assignee_id' => $assignee->id
        ];

        $response = $this->post('/tasks', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('errors', $response->getContent());
    }

    public function testUpdateTaskWithStatusInProgress()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->create(['building_id' => $building[0]->id]);

        $data = [
            "status" => 2
        ];

        $response = $this->put('/tasks/'.$tasks->id, $data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
        $this->assertStringContainsString('"data"', $response->getContent());
    }

    public function testUpdateTaskWithStatusCompletedAndUpdatedDate()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->create(['building_id' => $building[0]->id, 'status_id' => 1]);

        $data = [
            "status_id" => 3
        ];

        $response = $this->put('/tasks/'.$tasks->id, $data);

        $response->assertJson(function (AssertableJson $json) 
            {
                $json->where('data.completed_on', function ($completedOn) {
                    return strtotime($completedOn) != false;
                });
            }
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsString($response->getContent());
    }

    public function testFailsToUpdateTaskWithoutTheFormatDateTime()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->create(['building_id' => $building[0]->id, 'status_id' => 1]);

        $data = [
            "completed_on" => "2024-12-14"
        ];

        $response = $this->put('/tasks/'.$tasks->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testFailsToUpdateTaskWithBigTitle()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->create(['building_id' => $building[0]->id, 'status_id' => 1]);

        $data = [
            "title" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultricies ac ex in consequat. Duis euismod enim velit, eget ullamcorper tellus aliquam eu. Etiam tortor est, bibendum eget aliquam at, congue eget odio. Mauris at orci nulla. Morbi eu luctus risus. Donec ullamcorper a dui id laoreet. Donec a odio facilisis tellus cursus pellentesque. Nullam tincidunt euismod neque posuere dictum. Aliquam eleifend dignissim tortor aliquam efficitur."
        ];

        $response = $this->put('/tasks/'.$tasks->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testFailsToUpdateTaskWithWrongTypeOfStatuses()
    {
        $building = Building::factory(1)->create();
        $tasks = Task::factory()->create(['building_id' => $building[0]->id, 'status_id' => 1]);

        $data = [
            "status_id" => "Open"
        ];

        $response = $this->put('/tasks/'.$tasks->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
    }
}