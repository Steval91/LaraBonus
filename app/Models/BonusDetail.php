<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusDetail extends Model
{
    use HasFactory;

    protected $fillable = ['bonus_id', 'employee_id', 'bonus_percentage', 'bonus'];

    public function bonus()
    {
        return $this->belongsTo(Bonus::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
