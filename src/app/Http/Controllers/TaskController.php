<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Responses\TaskResponse;
use App\Support\Http\Resources\Json\JsonResponseFactory;

class TaskController extends Controller
{
    public function __construct(private JsonResponseFactory $jsonResponse) {}

    public function index()
    {
        $tasks = Task::all();

        return $this->jsonResponse
            ->collection($tasks, new TaskResponse)
            ->create();
    }
}
