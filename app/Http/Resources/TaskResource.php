<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskResource',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'New task'),
        new OA\Property(property: 'description', type: 'text', example: 'A very special task which was expected to be done yesterday'),
        new OA\Property(property: 'create_date', type: 'date-time', example: '2025-01-20T15:00:00'),
        new OA\Property(property: 'due_date', type: 'date-time', example: '2025-01-20T15:00:00'),
        new OA\Property(property: 'priority', type: 'string', example: 'срочный'),
        new OA\Property(property: 'category', type: 'string', example: 'Работа'),
        new OA\Property(property: 'status', type: 'string', example: 'просрочено')
    ],
    type: 'object'
)]
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
