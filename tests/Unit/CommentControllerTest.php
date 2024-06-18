<?php

namespace App\Tests\Unit;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateCommentkWithValidData()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment',
            'task_id'=> $task->id,
	        "user_id"=> $task->creator_id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertStringContainsString('"data":', $response->getContent());
    }

    public function testFailsToCreateCommentkWithoutComment()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'task_id'=> $task->id,
	        "user_id"=> $task->creator_id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }
    
    public function testFailsToCreateCommentkWithEmptyComment()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => '',
            'task_id'=> "Tasks",
	        "user_id"=> $task->creator_id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToCreateCommentkWithoutTaskId()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment',
	        "user_id"=> $task->creator_id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToCreateCommentkWithTaskString()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment',
            'task_id'=> "Tasks",
	        "user_id"=> $task->creator_id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToCreateCommentkWithoutUserId()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment',
            'task_id'=> $task->id
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToCreateCommentkWithoutUserName()
    {
        $task = Task::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment',
            'task_id'=> $task->id,
	        "user_id"=> "John"
        ];

        $response = $this->post('/comment', $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testUpdateACommentWithValidDataComment()
    {
        $comment = Comment::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment 2'
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('"data":', $response->getContent());
    }

    public function testFailsToUpdateACommentWithNonexistentId()
    {
        $data = [
            'comment' => 'New Comment 2'
        ];

        $response = $this->put("/comment/0", $data);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testUpdateACommentWithValidDataCommentAndDate()
    {
        $comment = Comment::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment 2',
            'date' => Carbon::today()->format('Y-m-d H:i:s')
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('"data":', $response->getContent());
    }

    public function testFailsToUpdateACommentWithEmptyText()
    {
        $comment = Comment::inRandomOrder()->first();

        $data = [
            'comment' => '',
            'date' => Carbon::today()->format('Y-m-d H:i:s')
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToUpdateACommentWithWrongIncompleteDate()
    {
        $comment = Comment::inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment 2',
            'date' => Carbon::today()->format('Y-m-d')
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToUpdateThaTaskIdOfAComment()
    {
        $comment = Comment::inRandomOrder()->first();
        $task = Task::where("id", "!=", $comment->task_id)->inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment 2',
            'task_id' => $task->id
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }

    public function testFailsToUpdateThaUserIdOfAComment()
    {
        $comment = Comment::inRandomOrder()->first();
        $user = User::where("id", "!=", $comment->user_id)->inRandomOrder()->first();

        $data = [
            'comment' => 'New Comment 2',
            'user_id' => $user->id
        ];

        $response = $this->put("/comment/".$comment->id, $data);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertStringContainsString('"errors":', $response->getContent());
    }
}