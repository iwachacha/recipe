<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecipeRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\AccessLog;
use App\Models\Like;
use Helper;

class RecipeController extends Controller
{
    public function index(Request $request, Recipe $recipe, Like $like, Tag $tag)
    {
        list($result_count, $recipes) = $recipe->searchRecipes($request); // /Models/Recipe.phpに定義 検索処理（検索結果＋件数取得）
        
        if(!empty($request->query())){ //検索があればメッセージ表示
            \Toastr::success($result_count . '件取得しました。'); // /Helpers/Toastr.phpに定義 フラッシュメッセージ
        }
        
        return view('recipes/index')->with(['recipes' => $recipes, 'result_count' => $result_count, 'like' => $like, 'tag' => $tag]);
    }

    public function create(Tag $tag)
    {
        return view('recipes/create')->with(['tags' => $tag->get()]);
    }

    public function store(RecipeRequest $request, Recipe $recipe)
    {
        $input = $request['recipe'];
        
        $input['user_id'] = Auth::id();
        
        //画像処理
        if(!empty($request->file('image'))){
            $image = $request->file('image')->store('public/images');
            $input['image_name'] = getClientOriginalName($image);
            
            //リサイズして保存
            /*$resized_image = InterventionImage::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save()->store('public/images');*/
        }
        
        //材料1:数量1/材料2:数量2/...と結合
        $input_ingredient = $input['ingredient'];
        $input_quantitie = $input['quantity'];
        for($i = 0; $i < count($input_ingredient); $i++){
            $merge_ingredient[] = $input_ingredient[$i] . ":" . $input_quantitie[$i];
        }
        $save_ingredient = join("/", $merge_ingredient);
        $input['ingredient'] = $save_ingredient;
        
        //調理法1/調理法2/...と配列を結合
        $save_method = join("/", $input['method']);
        $input['method'] = $save_method;
        
        $recipe->fill($input)->save();
        
        \Toastr::success('投稿ありがとうございます！');
        return redirect()->route('recipe.index');
    }

    public function show(Recipe $recipe, AccessLog $access_log, Like $like)
    {
        //閲覧数増加
        $access_log->create([
            'recipe_id' => $recipe->id,
        ]);
        
        return view('recipes/show')->with(['recipe' => $recipe, 'like' => $like]);
    }
    
    public function edit(Recipe $recipe, Tag $tag)
    {
        return view('recipes/edit')->with(['recipe' => $recipe, 'tags' => $tag->get()]);
    }

    public function update(Recipe $recipe, RecipeRequest $request)
    {
        $input = $request['recipe'];
        
        if(isset($input['check'])){ //同じ画像を使用にチェックされたら同じ画像の名前を保存
            $input['image_name'] = $recipe->image_name;
            
        } elseif(!empty($request->file('image'))){
            $image = $request->file('image')->store('public/images');
            $input['image_name'] = getClientOriginalName($image);
        }
        
        //材料1:数量1/材料2:数量2/...と結合
        $input_ingredient = $input['ingredient'];
        $input_quantitie = $input['quantity'];
        for($i = 0; $i < count($input_ingredient); $i++){
            $merge_ingredient[] = $input_ingredient[$i] . ":" . $input_quantitie[$i];
        }
        $save_ingredient = join("/", $merge_ingredient);
        $input['ingredient'] = $save_ingredient;
        
        //調理法1/調理法2/...と配列を結合
        $save_method = join("/", $input['method']);
        $input['method'] = $save_method;
        
        $recipe->fill($input)->save();
        return redirect()->route('recipe.index');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        \Toastr::success('投稿が削除されました。');
        return redirect()->route('recipe.index');
    }
}
