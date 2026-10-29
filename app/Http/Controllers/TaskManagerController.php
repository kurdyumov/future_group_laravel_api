<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskManagerController extends Controller
{
    public function get_tasks(Request $request) {
        try {
            $params = $request->all(['search', 'sort']);
            $res = DB::table('tasks');

            if ($params['search']) {
                $res = $res->where(['title'=>$params['search']]);
            }
            if ($params['sort']) {
                $res = $res->orderBy($params['sort']);
            }

            $res = $res->get();
            return response()->json(['params'=>$params, 'res'=>$res]);
        } catch (Exception $e) {
            return response()->json(['message'=>'Failed to find tasks. Check your arguments'], 404);
        }
    }

    public function get_task(Request $request, $id) {
        try {
            $res = DB::table('tasks')->find($id);
            return response()->json($res);
        } catch (Exception $e) {
            return response()->json(['message'=>'Failed to find task: '.$id.'. Check your arguments'], 404);
        }
    }

    public function post_task(Request $request) {
        try {
            $task = $request->all();
            $id = DB::table('tasks')->insertGetId([
                'title'=>$task['title'],
                'description'=>$task['description'],
                'due_date'=>$task['due_date'],
                'create_date'=>$task['create_date'],
                'priority'=>$task['priority'],
                'category'=>$task['category'],
                'status'=>$task['status'],
            ]);
            return response()->json(['id'=>$id, "message"=> "Task created successfully"]);
        } catch (Exception $e) {
            return response()->json(['message'=>'Failed to create task. Check your arguments'], 400);
        }
    }

    public function put_task(Request $request, $id) {
        try {
            $data = $request->all();
            unset($data['id']);
            DB::table('tasks')->where(['id'=>$id])->update($data);
            return response()->json(['id'=>$id, 'message'=>'Task updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message'=>'Failed to updated task. Check your arguments'], 400);
        }
    }

    public function delete_task(Request $request, $id) {
        try {
            DB::table('tasks')->delete($id);
            return response(['id'=>$id, 'message'=>'Task deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message'=>'Failed to delete task. Check your arguments'], 400);
        }
    }
}
