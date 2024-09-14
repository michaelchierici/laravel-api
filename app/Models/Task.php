<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

     //add name, day and reminder to fillable
     protected $fillable = ['name', 'user_id', 'reminder'];
}
