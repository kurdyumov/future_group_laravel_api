<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SortDirection;

class TaskManagerController extends Controller
{
    public function get_tasks(Request $request) {
        $params = $request->all(['search', 'sort', 'page', 'per_page']);
        $res = DB::table('tasks');
        if ($params['search']) {
            $res = $res->where(['title'=>$params['search']]);
        }
        if ($params['sort'] and in_array($params['sort'], ['due_date', 'create_date'])) {
            $direction = ($params['sort'][0] == '-')?SortDirection::Descending:SortDirection::Ascending;
            $res = $res->orderBy(ltrim($params['sort'], '+-'), $direction);
        }
        if ($params['page']) {
            $res = $res->paginate(
                $params['per_page']?intval($params['per_page']):10, ['*'], 'page', intval($params['page'])
            )->items();
        } else {
            $res = $res->get();
        }
        return response()->json($res);
    }

    public function get_task(Request $request, $id) {
        $res = DB::table('tasks')->find($id);
        return response()->json($res);
    }

    public function post_task(Request $request) {
        $search = ['title', 'description', 'due_date', 'create_date', 'priority', 'category', 'status'];
        $task = $request->all($search);
        $args = array_combine(
            $search,
            array_map(fn ($field) => $task[$field],
            $search)
        );
        $id = DB::table('tasks')->insertGetId($args);
        return response()->json(['id'=>$id, "message"=> "Task created successfully"]);
    }

    public function put_task(Request $request, $id) {
        $data = $request->all();
        unset($data['id']);
        DB::table('tasks')->where(['id'=>$id])->update($data);
        return response()->json(['id'=>$id, 'message'=>'Task updated successfully']);
    }

    public function delete_task(Request $request, $id) {
        DB::table('tasks')->delete($id);
        return response(['id'=>$id, 'message'=>'Task deleted successfully']);
    }
}
