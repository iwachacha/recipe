<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\Like;

class LikeController extends Controller
{
    public function like(Recipe $recipe, Like $like)
    {
        if($recipe->id != Auth::id()) //レシピ投稿者本人のいいねを制限
        {
            $recipe->likeUsers()->syncWithoutDetaching(Auth::id()); //重複なしで紐付け=いいね登録
            \Toastr::success('いいねが完了しました。');
            
        } else {
            \Toastr::info('投稿者はいいねできません。');
        }
        
        return redirect()->back();
        /*return [
            'id' => $recipe->id,
            'countLikes' => $recipe->count_likes,
        ];*/
    }
    
    public function unlike(Recipe $recipe)
    {
        $recipe->likeUsers()->detach(Auth::id()); //紐付け解除＝いいね解除
        
        \Toastr::success('いいねが削除されました。');
        return redirect()->back();
        /*return [
            'id' => $recipe->id,
            'countLikes' => $recipe->count_likes,
        ];*/
    }
}
