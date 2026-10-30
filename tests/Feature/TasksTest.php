<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_tasks(): void
    {
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
    }

    public function test_can_get_task_by_id(): void
    {
        $response = $this->get('/api/tasks/1');
        $response->assertStatus(200);
    }

    public function test_can_get_specific_task_error(): void
    {
        $response = $this->get('/api/tasks/-1');
        $response->assertStatus(200);
    }

    public function test_insert_row(): void
    {
        $data = [
            "title"=>"Задача39",
            "description"=>"Задача1 описание",
            "due_date"=>"2025-01-20T15:00:00",
            "create_date"=>"2025-01-20T15:00:00",
            "priority"=>"высокий",
            "category"=>"Работа",
            "status"=>"не выполнена"
        ];
        $response = $this->post('/api/tasks', $data);
        $response->assertStatus(204);
    }

    public function test_insert_row_not_entire(): void
    {
        $data = [
            "title"=>"Задача39",
            "description"=>"Задача1 описание",
            "due_date"=>"2025-01-20T15:00:00",
            "priority"=>"высокий",
            "status"=>"не выполнена"
        ];
        $response = $this->post('/api/tasks', $data);
        $response->assertStatus(204);
    }

    public function test_insert_row_with_typos(): void
    {
        $data = [
            "title"=>"Задача39",
            "descripti"=>"Задача1 описание",
            "due_date"=>"2025-01-20T15:00:00",
            "create_date"=>"2025-01-20T15:00:00",
            "priorit"=>"высокий",
            "category"=>"Работа",
            "status"=>"не выполнена"
        ];
        $response = $this->post('/api/tasks', $data);
        $response->assertStatus(204);
    }

    public function test_update_row_by_id(): void
    {
        $data = [
            'due_date'=>date('Y-m-d H:i:s', time())
        ];
        $response = $this->put('/api/tasks/8', $data);
        $response->assertStatus(204);
    }

    public function test_delete_row_by_id(): void
    {
        $data = [
            'due_date'=>date('Y-m-d H:i:s', time())
        ];
        $response = $this->delete('/api/tasks/2', $data);
        $response->assertStatus(204);
    }
}
