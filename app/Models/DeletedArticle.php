<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedArticle extends Model
{
    use HasFactory;

    protected $table = 'deleted_articles';

    protected $fillable = ['title'];
}
