<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    

    protected $table = 'post';


    protected $fillable = [
        'title',
        'content',
        'category_id',

    ];
    //relación de muchos a uno

 /**
    * Get the user that owns the Post
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function user()
   {
       return $this->belongsTo('App\User', 'user_id');
   }

 /**
    * Get the user that owns the Post
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function category()
   {
       return $this->belongsTo('App\Category', 'category_id');
   }
}
