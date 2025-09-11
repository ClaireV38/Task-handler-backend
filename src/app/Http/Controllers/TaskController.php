<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Client\Request;

final readonly class TaskController
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            // admin → toutes les tâches
            $tasks = Task::all();
        } else {
            // user → seulement ses tâches
            $tasks = Task::where('user_id', $user->id)->get();
        }

        return response()->json(['data' => $tasks]);
    }
}
