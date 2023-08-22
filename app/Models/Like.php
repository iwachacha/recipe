<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;

class Like extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'recipe_id'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }
    
    public function isLikedBy($user_id, $recipe_id) //ユーザーが対象レシピにいいねをしているか確認
    {
        return $this->where([
            ['user_id', '=', $user_id],
            ['recipe_id', '=', $recipe_id],
        ])->exists(); //いいねしていればtrueを返す
    }
    
}
