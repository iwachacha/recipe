<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'user_id', 'tag_id', 'image_name', 'category', 
        'cooking_level', 'cooking_time', 'amount', 
        'title', 'ingredient', 'method', 'advice'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    
    //↓いいね機能関連
    public function likes()
    {
        return $this->hasMany(Like::class);  
    }
    
    public function likeUsers()
    {
        return $this->belongsToMany(User::class, 'likes', 'recipe_id', 'user_id')->withTimestamps();
    }
    
    public function getCountLikesAttribute() //$recipe->count_likes＝いいねの数
    {
        return $this->likes->count(); 
    }
    //↑いいね機能関連
    
    //↓閲覧数機能関連
    public function access_logs()
    {
        return $this->hasMany(AccessLog::class);  
    }
    
    public function getCountAccessLogsAttribute() //$recipe->count_access_logs＝閲覧数
    {
        return $this->access_logs->count(); 
    }
    //↑閲覧数機能関連
    
    //↓レシピ表示関連
    public function splitIngredient($ingredients) //引数＝材料1:数量1/材料2:数量2/...(文字列)
    {
        $ingredients = explode('/', $ingredients); //[0]=材料1:数量1, [1]=材料2:数量2, [2]=...(配列)
        foreach ($ingredients as $e) {
            list($ingredient[], $quantities[]) = explode(':', $e); //$ingredients[0]=材料1, [1]=... $quantities[0]=数量1, [1]=...(配列)
        }
        
        return [$ingredient, $quantities];
    }
    
    public function splitMethod($methods)
    {
        return explode('/', $methods);
    }
    
    public function searchRecipes($request)
    {
        //検索処理　複数条件で絞り込みしていき、最終的に残ったものを取得する　
        $recipes = $this;
        
        //ワード検索
        if($request->filled('search')){
            $search_word = $request->input('search'); 
            $recipes = $recipes->where('title', 'LIKE', '%'.$search_word.'%')
                ->orWhere('ingredient', 'LIKE', '%'.$search_word.'%')
                ->orWhere('cooking_level', 'LIKE', '%'.$search_word.'%');
        }
        
        //タグ名検索
        if($request->filled('tag_id')){
            $search_tag = $request->input('tag_id'); 
            $recipes = $recipes->where('tag_id', '=', $search_tag);
        }
        
        $result_count = $recipes->count(); //検索取得件数
        $recipes = $recipes->orderBy('created_at', 'desc')->paginate(10); //検索結果取得
        
        return [$result_count, $recipes];
    }
    //↑レシピ表示関連
}
