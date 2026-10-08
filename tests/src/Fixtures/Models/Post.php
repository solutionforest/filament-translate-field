<?php

namespace SolutionForest\FilamentTranslateField\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];
}
