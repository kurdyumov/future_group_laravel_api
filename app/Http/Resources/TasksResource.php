<?php

namespace App\Http\Resources;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'TasksCollection',
    description: 'Collection of tasks',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/TaskResource',
                type: 'object'
            )
        )
    ]
)]
class TasksResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => Tasks::all()
        ];
    }
}
