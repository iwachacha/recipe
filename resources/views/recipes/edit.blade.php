@php
$select_category = ['おかず', 'ごはんもの', '麺', 'パン', 'サラダ・野菜', 'スープ', 'ダイエット料理', 'デザート', 'その他'];
$select_level = ['超簡単', '簡単', '普通', '大変', '超大変'];
$select_time = ['~5m', '5m~10m', '10m~30m', '30m~1h', '1h~'];
@endphp

<x-app-layout>
    
    <x-page-section>
        <x-slot name="title">編集前レシピ</x-slot>
        
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        編集前の内容を表示する
                    </button>
                </h3>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body bg-secondary shadow" style="--bs-bg-opacity: 8%">
            
                    <div class="my-4">
                        <div class="d-flex justify-content-center"><img src="{{ @asset('storage/images/'.$recipe->image_name) }}" style='max-width: 90%; max-height: 200px;'></div>
                    </div>
                    
                    <div class="mb-4 d-flex justify-content-between">
                        <div style="width: 48%;">
                            <label class="form-label">#タグ</label>
                            <div class="form-control">
                                {{ @$recipe->tag->name }}
                            </div>
                        </div>
                        <div style="width: 48%;">
                            <label class="form-label">料理カテゴリー</label>
                            <div class="form-control">
                                {{ $recipe->category }}
                            </div>
                        </div>
                    </div>
                
                    <div class="mb-4 d-flex justify-content-between">
                        <div style="width: 32%;">
                            <label class="form-label">量</label>
                            <div class="form-control">
                                {{ $recipe->amount }}人前
                            </div>
                        </div>
                        <div style="width: 32%;">
                            <label class="form-label">調理難易度</label>
                            <div class="form-control">
                                {{ $recipe->cooking_level }}
                            </div>
                        </div>
                        <div style="width: 32%;">
                            <label class="form-label">調理時間</label>
                            <div class="form-control">
                                {{ $recipe->cooking_time }}
                            </div>
                        </div>
                    </div>
                
                    <div class="mb-4">
                        <label class="form-label">調理時間</label>
                        <div class="form-control">
                            {{ $recipe->title }}
                        </div>
                    </div>
                    
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label class="form-label" style="width: 48%;">材料</label>
                        <label class="form-label" style="width: 48%;">数量</label>
                    </div>
                    <div class="mb-4">
                        @php list($ingredients, $quantities) = $recipe->splitIngredient($recipe->ingredient); @endphp
                        @for($i = 0; $i < count($ingredients); $i++)
                            <div class="mb-3 d-flex justify-content-between ingredient">
                                <div style="width: 48%;">
                                    <div class="form-control">
                                        {{ $ingredients[$i] }}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">：</div>
                                <div style="width: 48%;">
                                    <div class="form-control">
                                        {{ $quantities[$i] }}
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                
                    <div class="mb-4">
                        <label class="form-label">調理法</label>
                        @php $methods = $recipe->splitMethod($recipe->method); @endphp
                        @for($i = 0; $i < count($methods); $i++)
                            <div class="mb-3 form-control">
                                {{ $methods[$i] }}
                            </div>
                        @endfor
                    </div>
                
                    <div class="mb-4">
                        <label class="form-label">アドバイス・コツ</label>
                        <div class="form-control">
                            {{ @$recipe->advice }}
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        
    </x-page-section>
        
    <x-page-section>
        <x-slot name="title">レシピ編集</x-slot>
        
        <div class="text-center mb-3" id="preview"></div>
        <form action="{{ route('recipe.update', ['recipe' => $recipe->id]) }}" method="post" class="recipe_form" enctype="multipart/form-data"onSubmit="return confirm('送信してもよろしいですか？')">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label for="image" class="form-label">料理画像（任意）</label>
                    <div class="form-check">
                        <input name="check" class="form-check-input" type="checkbox" id="check" checked>
                        <label class="form-check-label fw-bold" for="check">元の画像を使用</label>
                    </div>
                </div>
                <input type="file" name="image" class="form-control image-upload" id="image" accept="image/*">
            </div>
        
            <div class="mb-4 d-flex justify-content-between">
                <div style="width: 48%;">
                    <label for="tag_id" class="form-label">#タグ（任意）</label>
                    <select name="recipe[tag_id]" class="form-select form-select" id="tag_id">
                        <option selected disabled value="">選択してください</option>
                        <option value="">選択しない</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if( old("recipe.tag_id", $recipe->tag->id) == $tag->id ) selected @endif>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.tag_id')" class="mt-2" />
                </div>
                <div style="width: 48%;">
                    <label for="category" class="form-label">料理カテゴリー</label>
                    <select name="recipe[category]" class="form-select form-select" id="category" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_category as $e)
                            <option value="{{ $e }}" @if( old("recipe.category", $recipe->category) == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.category')" class="mt-2" />
                </div>
            </div>
        
            <div class="mb-4 d-flex justify-content-between">
                <div style="width: 32%;">
                    <label for="amount" class="form-label">量</label>
                    <input type="number" name="recipe[amount]" class="form-control" id="amount" placeholder="？人前" required min="1" max="9" value="{{ old('recipe.amount', $recipe->amount) }}">
                    <x-input-error :messages="$errors->first('recipe.amount')" class="mt-2" />
                </div>
                <div style="width: 32%;">
                    <label for="cooking_level" class="form-label">調理難易度</label>
                    <select name="recipe[cooking_level]" class="form-select form-select" id="cooking_level" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_level as $e)
                            <option value="{{ $e }}" @if( old("recipe.cooking_level", $recipe->cooking_level) == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.cooking_level')" class="mt-2" />
                </div>
                <div style="width: 32%;">
                    <label for="cooking_time" class="form-label">調理時間</label>
                    <select name="recipe[cooking_time]" class="form-select form-select" id="cooking_time" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_time as $e)
                            <option value="{{ $e }}" @if( old("recipe.cooking_time", $recipe->cooking_time) == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.cooking_time')" class="mt-2" />
                </div>
            </div>
        
            <div class="mb-4">
                <label for="title" class="form-label">料理名（50文字以内）</label>
                <input type="text" name="recipe[title]" class="form-control" id="title" placeholder="料理名を入力してください" required value="{{ old('recipe.title', $recipe->title) }}">
                <x-input-error :messages="$errors->first('recipe.title')" class="mt-2" />
            </div>
        
            <div class="ingredient_area">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="ingredient" class="form-label" style="width: 48%;">材料</label>
                    <label for="quantity" class="form-label" style="width: 48%;">数量</label>
                </div>
                @php list($ingredients, $quantities) = $recipe->splitIngredient($recipe->ingredient); @endphp
                @for($i = 0; $i < count($ingredients); $i++)
                    @php $ingredient_id = $i + 1; @endphp
                    <div class="mb-3 d-flex justify-content-between ingredient">
                        <div style="width: 48%;">
                            <input name="recipe[ingredient][]" class="form-control" id="ingredient{{ $ingredient_id }}" required value="{{ old('recipe.ingredient.'.$ingredient_id, $ingredients[$i]) }}">
                            <x-input-error :messages="$errors->first('recipe.ingredient')" class="mt-2" />
                        </div>
                        <div class="d-flex align-items-center">：</div>
                        <div style="width: 48%;">
                            <input name="recipe[quantity][]" class="form-control" id="quantity{{ $ingredient_id }}" required value="{{ old('recipe.quantity.'.$ingredient_id, $quantities[$i]) }}">
                            <x-input-error :messages="$errors->first('recipe.quantity')" class="mt-2" />
                        </div>
                    </div>
                @endfor
            </div>
            <div class="mb-4 d-flex justify-content-center">
                <button type="button" class="d-flex align-items-center btn btn-light border me-3 add_ingredient">
                    <i class="bi bi-plus-lg"></i><div class="ms-1">1行追加する</div>
                </button>
                <button type="button" class="d-flex align-items-center btn btn-light border text-danger delete_ingredient">
                    <i class="bi bi-trash"></i><div class="ms-1">1行削除する</div>
                </button>
            </div>
        
            <div class="method_area">
                <div class="mb-3">
                    <label class="form-label">調理法</label>
                    @php $methods = $recipe->splitMethod($recipe->method); @endphp
                    @for($i = 0; $i < count($methods); $i++)
                        @php $method_id = $i + 1; @endphp
                        <textarea name="recipe[method][]" class="form-control mb-3 method" id="method{{ $method_id }}" maxlength="500" rows="2" required>{{ old('recipe.method.'.$method_id, $methods[$i]) }}</textarea>
                        <x-input-error :messages="$errors->first('recipe.method')" class="mt-2" />
                    @endfor
                </div>
            </div>
            <div class="mb-4 d-flex justify-content-center">
                <button type="button" class="d-flex align-items-center btn btn-light border me-3 add_method">
                    <i class="bi bi-plus-lg"></i><div class="ms-1">1行追加する</div>
                </button>
                <button type="button" class="d-flex align-items-center btn btn-light border text-danger delete_method">
                    <i class="bi bi-trash"></i><div class="ms-1">1行削除する</div>
                </button>
            </div>
        
            <div class="mb-4">
                <label for="advice" class="form-label">アドバイス・コツ（任意）</label>
                <textarea name="recipe[advice]" class="form-control" id="advice" maxlength="1000" rows="3" placeholder="調理のアドバイスやコツを入力してください">{{ old('recipe.advice', $recipe->advice) }}</textarea>
                <x-input-error :messages="$errors->first('recipe.advice')" class="mt-2" />
            </div>
        
            <div class="d-grid mb-4"><button type="submit" class="btn submit" style="background-color: #59a387; color: white;">レシピ投稿</button></div>
        </form>
          
    </x-page-section>
    
    <script>
        $(function()
        {
            let id = @json($ingredient_id) + 1;
            
            $('.add_ingredient').click(function()
            {
                let add = '<div class="mb-3 d-flex justify-content-between ingredient"><div style="width: 48%;"><input name="recipe[ingredient][]" id="ingredient'+id+'" class="form-control" required placeholder="材料'+id+'"></div><div class="d-flex align-items-center">：</div><div style="width: 48%;"><input name="recipe[quantity][]" id="quantity'+id+'" class="form-control" required placeholder="数量'+id+'"></div>';
                
                if(id <= 10){
                    $('.ingredient_area').append(add);
                    id++;
                }
            });
            
            $('.delete_ingredient').click(function()
            {
                if(id != 2){
                    if( confirm('最終行を削除してもよろしいですか？') ){
                        $(".ingredient:last").remove();
                        id--;
                    }
                }
            });
        });
                
        //調理法入力エリアの追加・削除
        $(function()
        {
            let id = @json($method_id) + 1;
                
            $('.add_method').click(function()
            {
                let add = '<div class="mb-3 method"><textarea name="recipe[method][]" class="form-control" id="method'+id+'" maxlength="500" rows="2" required placeholder="調理法'+id+'"></textarea></div>';
                    
                if(id <= 10){
                    id++;
                    $('.method_area').append(add);
                }
            });
                
            $('.delete_method').click(function()
            {
                if(id != 2){
                    if( confirm('最終行を削除してもよろしいですか？') ){
                        $(".method:last").remove();
                        id--;
                    } 
                }
            });
        });
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/localization/messages_ja.js"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    
    <x-toastr />
    
</x-app-layout>