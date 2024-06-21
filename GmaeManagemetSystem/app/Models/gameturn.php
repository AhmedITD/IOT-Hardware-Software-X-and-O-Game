<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class gameturn extends Model
{
    use HasFactory;
    protected $table = 'gameturn';
    protected $fillable =
    [
        'id',
        'round',
        'permit',
        'realTimeInfo'
    ];
}
