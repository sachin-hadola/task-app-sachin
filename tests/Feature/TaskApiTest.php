<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_success(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
        ]);

        $response->assertStatus(201);
    }

    public function test_create_task_validation_failure(): void
    {
        $response = $this->postJson('/api/tasks', []);

        $response->assertStatus(422);
    }

    public function test_list_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200);
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
    }
}
