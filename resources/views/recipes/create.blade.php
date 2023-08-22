@php
$select_category = ['おかず', 'ごはんもの', '麺', 'パン', 'サラダ・野菜', 'スープ', 'ダイエット料理', 'デザート', 'その他'];
$select_level = ['超簡単', '簡単', '普通', '大変', '超大変'];
$select_time = ['~5m', '5m~10m', '10m~30m', '30m~1h', '1h~'];
@endphp

<x-app-layout>
    
    <x-page-section>
        <x-slot name="title">レシピ投稿</x-slot>
        
        <div class="text-center mb-3" id="preview"></div>
        <form action="{{ route('recipe.store') }}" method="post" class="recipe_form" enctype="multipart/form-data"onSubmit="return confirm('送信してもよろしいですか？')">
            @csrf
            <div class="mb-4">
                <label for="image" class="form-label">料理画像（任意）</label>
                <input type="file" name="image" class="form-control image-upload" id="image" accept="image/*">
                <x-input-error :messages="$errors->first('recipe.image')" class="mt-2" />
            </div>
        
            <div class="mb-4 d-flex justify-content-between">
                <div style="width: 48%;">
                    <label for="tag_id" class="form-label">#タグ（任意）</label>
                    <select name="recipe[tag_id]" class="form-select form-select" id="tag_id">
                        <option selected disabled value="">選択してください</option>
                        <option value="">選択しない</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if( old("recipe.tag_id") == $tag->id ) selected @endif>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.tag_id')" class="mt-2" />
                </div>
                <div style="width: 48%;">
                    <label for="category" class="form-label">料理カテゴリー</label>
                    <select name="recipe[category]" class="form-select form-select" id="category" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_category as $e)
                            <option value="{{ $e }}" @if( old("recipe.category") == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.category')" class="mt-2" />
                </div>
            </div>
        
            <div class="mb-4 d-flex justify-content-between">
                <div style="width: 32%;">
                    <label for="amount" class="form-label">量</label>
                    <input type="number" name="recipe[amount]" class="form-control" id="amount" placeholder="？人前" required min="1" max="9" value="{{ old('recipe.amount') }}">
                    <x-input-error :messages="$errors->first('recipe.amount')" class="mt-2" />
                </div>
                <div style="width: 32%;">
                    <label for="cooking_level" class="form-label">調理難易度</label>
                    <select name="recipe[cooking_level]" class="form-select form-select" id="cooking_level" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_level as $e)
                            <option value="{{ $e }}" @if( old("recipe.cooking_level") == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.cooking_level')" class="mt-2" />
                </div>
                <div style="width: 32%;">
                    <label for="cooking_time" class="form-label">調理時間</label>
                    <select name="recipe[cooking_time]" class="form-select form-select" id="cooking_time" required>
                        <option selected disabled value="">選択してください</option>
                        @foreach ($select_time as $e)
                            <option value="{{ $e }}" @if( old("recipe.cooking_time") == $e ) selected @endif>{{ $e }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->first('recipe.cooking_time')" class="mt-2" />
                </div>
            </div>
        
            <div class="mb-4">
                <label for="title" class="form-label">料理名（50文字以内）</label>
                <input type="text" name="recipe[title]" class="form-control" id="title" placeholder="料理名を入力してください" required value="{{ old('recipe.title') }}">
                <x-input-error :messages="$errors->first('recipe.title')" class="mt-2" />
            </div>
        
            <div class="ingredient_area">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="ingredient" class="form-label" style="width: 48%;">材料</label>
                    <label for="quantity" class="form-label" style="width: 48%;">数量</label>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <div style="width: 48%;">
                        <input name="recipe[ingredient][]" class="form-control" id="ingredient1" required placeholder="材料1" value="{{ old('recipe.ingredient.0') }}">
                        <x-input-error :messages="$errors->first('recipe.ingredient')" class="mt-2" />
                    </div>
                    <div class="d-flex align-items-center">：</div>
                    <div style="width: 48%;">
                        <input name="recipe[quantity][]" class="form-control" id="quantity" required placeholder="数量1" value="{{ old('recipe.quantity.0') }}">
                        <x-input-error :messages="$errors->first('recipe.quantity')" class="mt-2" />
                    </div>
                </div>
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
                    <label for="method1" class="form-label">調理法</label>
                    <textarea name="recipe[method][]" class="form-control" id="method1" maxlength="500" rows="2" required placeholder="調理法1">{{ old('recipe.method.0') }}</textarea>
                    <x-input-error :messages="$errors->first('recipe.method')" class="mt-2" />
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
                <textarea name="recipe[advice]" class="form-control" id="advice" maxlength="1000" rows="3" placeholder="調理のアドバイスやコツを入力してください">{{ old('recipe.advice') }}</textarea>
                <x-input-error :messages="$errors->first('recipe.advice')" class="mt-2" />
            </div>
        
            <div class="d-grid mb-4"><button type="submit" class="btn submit" style="background-color: #59a387; color: white;">レシピ投稿</button></div>
        </form>
          
    </x-page-section>
    
    <x-page-section>
        <x-slot name="title">#タグ作成</x-slot>
        
        <form action="{{ route('tag.store') }}" method="post" onSubmit="return confirm('送信してもよろしいですか？')" id="tag_form">
            @csrf
            <div class="mb-4">
                <label for="create_tag" class="form-label">新規作成タグ（10文字以内）</label>
                <input type="text" name="name" class="form-control" id="create_tag" required placeholder="新規タグ名を入力してください" value="{{ old('name') }}">
                <x-input-error :messages="$errors->first('name')" class="mt-2" />
            </div>
            <div class="d-grid mb-5"><button type="submit" class="btn"style="background-color: #59a387; color: white;">タグ作成</button></div>
        </form>
        
        <div class="mb-4">
            <label for="exist_tag" class="form-label">既存タグ一覧</label>
            <select class="form-select form-select" id="exist_tag">
                <option selected>値は送信されません</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        
    </x-page-section>
    
    <script src="{{ asset('js/addForm.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/localization/messages_ja.js"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    
    <x-toastr />
    
</x-app-layout>