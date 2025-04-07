<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'address',
        'email',
        'phone_number',
        'manager_name',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($branch) {
            $branch->code = 'BR-' . strtoupper(Str::random(6));
        });
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
