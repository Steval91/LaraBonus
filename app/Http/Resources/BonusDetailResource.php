<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BonusDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $employee = Employee::find($this->employee_id);

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_name' => $employee->name,
            'percentage' => $this->bonus_percentage
        ];
    }
}
