<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\DB;
use SortDirection;
use OpenApi\Attributes as OA;

class TaskManagerController extends Controller
{
    #[OA\Get(
        path: "/api/tasks",
        summary: "Get a list of tasks",
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                parameter: 'search',
                name: 'search',
                description: 'Search tasks by "title"',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: "задача")
            ),
            new OA\Parameter(
                parameter: 'sort',
                name: 'sort',
                description: 'Filters to sort by (create_date/due_date)',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: "-create_date")
            ),
            new OA\Parameter(
                parameter: 'page',
                name: 'page',
                description: 'Sets a page number if "per_page" is set',
                in: 'query',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                parameter: 'per_page',
                name: 'per_page',
                description: 'Sets how much items per page come',
                in: 'query',
                schema: new OA\Schema(type: 'integer', example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "A list of tasks",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/TaskResource')
                )
                // content: new OA\JsonContent(
                //     ref: '#/components/schemas/TasksCollection'
                // )
            ),
            new OA\Response(
                response: 400,
                description: "Failed to perform SQL"
            )
        ]
    )]
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

    #[OA\Get(
        path: "/api/tasks/{id}",
        summary: "Get a specific task by ID",
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Pass an ID of row to obtain',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 7)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Get a specific task by ID",
                content: new OA\JsonContent(
                    ref: '#/components/schemas/TaskResource'
                )
            ),
            new OA\Response(
                response: 400,
                description: "Failed to perform SQL"
            )
        ]
    )]
    public function get_task(Request $request, $id) {
        $res = DB::table('tasks')->find($id);
        return response()->json($res);
    }

    #[OA\Post(
        path: "/api/tasks",
        summary: "Create a new task",
        tags: ['Posts'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/TaskResource'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Task created successfully",
                content: new OA\JsonContent(
                    ref: '#/components/schemas/TaskResource'
                )
            ),
            new OA\Response(
                response: 400,
                description: "Failed to perform SQL"
            )
        ]
    )]
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

    #[OA\Put(
        path: "/api/tasks/{id}",
        summary: "Update a specific task by ID",
        tags: ['Posts'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/TaskResource'
            )
        ),
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Pass an ID of row to obtain',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Task updated successfully",
                content: new OA\JsonContent(
                    ref: '#/components/schemas/TaskResource'
                )
            ),
            new OA\Response(
                response: 400,
                description: "Failed to perform SQL"
            )
        ]
    )]
    public function put_task(Request $request, $id) {
        $data = $request->all();
        unset($data['id']);
        DB::table('tasks')->where(['id'=>$id])->update($data);
        return response()->json(['id'=>$id, 'message'=>'Task updated successfully']);
    }

    #[OA\Delete(
        path: "/api/tasks/{id}",
        summary: "Delete a specific task by ID",
        tags: ['Posts'],
        // requestBody: new OA\RequestBody(
        //     required: true,
        //     content: new OA\JsonContent(

        //     )
        // ),
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Pass an ID of row to obtain',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Task deleted successfully",
                content: new OA\JsonContent(

                )
            )
        ]
    )]
    public function delete_task(Request $request, $id) {
        DB::table('tasks')->delete($id);
        return response(['id'=>$id, 'message'=>'Task deleted successfully']);
    }
}
