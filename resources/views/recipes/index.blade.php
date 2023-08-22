<x-app-layout>
    <x-page-section>
        <x-slot name="title">
            @if(!empty(request()->query('search')))
                {{ request()->query('search') }}（{{ $result_count }}件取得）
                
            @elseif(!empty(request()->query('tag_id')))
                # {{ $tag->getTagName(request()->query('tag_id')) }}（{{ $result_count }}件取得）
            @else
                レシピ一覧
            @endif
        </x-slot>

        <div class="my-4">
            <form action="{{ route('recipe.index') }}" method="get" class="justify-content-center align-items-end"><!-- 検索フォーム -->
                <div class="d-flex justify-content-center">
                    <input type="search" name="search" class="form-control" placeholder="キーワードを入力してください" value="{{ request()->query('search') }}" style="max-width: 60%">
                    <button class="btn d-flex align-items-center" type="submit" style="background-color: #59a387; color: white;">
                        <i class="bi bi-search"></i><div class="ms-1">検索</div>
                    </button>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('recipe.index') }}">検索条件をリセットする</a>
                </div>
            </form><!-- 検索フォーム閉じ -->
        </div>

        <div class="d-flex flex-wrap justify-content-around">
            @foreach ($recipes as $recipe)
                <div class="card shadow-lg rounded" style="width: 30%; margin-bottom: 50px;">
                    <div class="text-center align-items-center border border-2 border-top-0 border-start-0 border-end-0" style="height: 200px">
                        <img src="{{ asset('storage/images/'.$recipe->image_name) }}" style="width: 100%; max-height: auto; object-fit: cover;" alt="投稿画像">
                    </div>
                    <div class="card-body text-break bg-light bg-gradient d-flex flex-column justify-content-around">
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="card-text me-2">
                                    <a href='profile.php?id=" . $user_id . "'><i class="bi bi-person-circle fs-4"></i></a>
                                </div>
                                <div class="card-text text-secondary"><!-- ユーザー名が10文字以上なら省略表示 -->
                                    @if(mb_strlen($recipe->user->name) >= 10))
                                        @php $name = mb_substr($recipe->user->name, 0, 10) . "..."; @endphp 
                                        {{ $name }} さん
                                    @else
                                        {{ $recipe->user->name }}さん
                                    @endif
                                </div>
                            </div>
                            @auth
                                @if(Auth::user()->id == $recipe->user_id)
                                    <div class='dropdown'>
                                        <button class='dropdown-toggle bg-light bg-gradient' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
                                            <li class='pb-1 align-items-center'>
                                                <a class='dropdown-item' href='{{ route("recipe.edit", ["recipe" => $recipe->id]) }}' onclick='return editConfirm()'>
                                                    <i class="bi bi-pencil-square"></i>  編集
                                                </a>
                                            </li>
                                            <li class="align-items-center">
                                                <form action='{{ route("recipe.destroy", ["recipe" => $recipe->id]) }}' method="post" onSubmit='return confirm("本当に削除しますか？")' class='dropdown-item align-baseline text-danger' >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="bi bi-trash"></i>  削除</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        <h3 class="card-title text-center mb-2 fw-bold fs-3"><!-- 料理名が25文字以上なら省略表示 -->
                            @if(mb_strlen($recipe->title) >= 25))
                                @php $title = mb_substr($recipe->title, 0, 25) . "..."; @endphp 
                                {{ $title }}
                            @else
                                {{ $recipe->title }}
                            @endif
                        </h3>
                        <div class="mb-3 d-flex justify-content-center">
                            <div class="me-4 d-flex align-items-center">
                                <i class="bi bi-stopwatch fs-5"></i><div class="ms-1">{{ $recipe->cooking_time }}</div>
                            </div>
                            <a href="/?search={{ $recipe->cooking_level }}" class="text-dark">
                                <div class="d-flex align-items-center">
                                    @if($recipe->cooking_level == "超簡単")
                                        <i class="bi bi-emoji-sunglasses fs-5"></i>  
                                    @elseif($recipe->cooking_level == "簡単")
                                        <i class="bi bi-emoji-smile fs-5"></i>  
                                    @elseif($recipe->cooking_level == "普通")
                                        <i class="bi bi-emoji-neutral fs-5"></i>  
                                    @elseif($recipe->cooking_level == "大変")
                                        <i class="bi bi-emoji-expressionless fs-5"></i>  
                                    @else
                                        <i class="bi bi-emoji-dizzy fs-5"></i>  
                                    @endif
                                    <div class="ms-1">{{ $recipe->cooking_level }}</div>
                                </div>
                            </a>
                        </div>
                        <ul class="mb-1 d-flex flex-wrap justify-content-center pe-0" style="padding-inline-start: 0px;"> <!-- 材料が6つ以上なら省略表示 -->
                            @php list($ingredients, $quantities) = $recipe->splitIngredient($recipe->ingredient); @endphp
                            @if(count($ingredients) <= 6)
                                @foreach ($ingredients as $e)
                                    <a href='/?search={{ $e }}' class='text-body'>
                                        <li class='mx-1 rounded-pill border px-2 mb-1 border-warning bg-warning' style='list-style:none; --bs-bg-opacity: 3%'>
                                            {{ $e }}
                                        </li>
                                    </a>
                                @endforeach
                            @else
                                @for($i = 0; $i < 5; $i++)
                                    <a href='/?search={{ $ingredients[$i] }}' class='text-body'>
                                        <li class='mx-1 rounded-pill border px-2 mb-1 border-warning bg-warning' style='list-style:none; --bs-bg-opacity: 3%'>
                                            {{ $ingredients[$i] }}
                                        </li>
                                    </a>
                                @endfor
                                <li class='mx-1 mb-1' style='list-style:none;'>
                                    ...
                                </li>
                            @endif
                        </ul>
                        @if(!empty($recipe->tag_id))
                            <div class="mb-2 text-end">
                                <a href="/?tag_id={{ $recipe->tag->id }}"># {{ $recipe->tag->name }}</a>
                            </div>
                        @endif
                        <div class="mb-1 d-flex justify-content-center align-items-center">
                            <div>
                                <div class="d-flex align-items-center">
                                    <a href='{{ route("recipe.show", ["recipe" => $recipe->id]) }}' class="d-flex align-items-center">
                                        <i class="bi bi-book fs-5"></i><div class="ms-1">レシピ詳細</div>
                                    </a>
                                    <div class="mx-1 text-secondary">/</div>
                                    <i class="bi bi-eye-fill fs-5"></i><div class="ms-1">{{ $recipe->count_access_logs }}</div>
                                </div>
                            </div>
                            <div class="ms-4">
                                @auth
                                    @if($like->isLikedBy(Auth::user()->id, $recipe->id))
                                        <div class="d-flex align-items-center">
                                            <form action="/recipes/{{ $recipe->id }}/unlike" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn p-0">
                                                    <i class="bi bi-heart-fill fs-5 text-danger"></i>
                                                </button>
                                            </form>
                                            <div id="like_count" class="ms-1">{{ $recipe->count_likes }}</div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <form action="/recipes/{{ $recipe->id }}/like" method="post">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn p-0">
                                                    <i class="bi bi-heart text-danger fs-5"></i>
                                                </button>
                                            </form>
                                            <div id="like_count" class="ms-1">{{ $recipe->count_likes }}</div>
                                        </div>
                                    @endif
                                @endauth
                                @guest
                                    <div class="d-flex align-items-center">
                                        <button type="button" class="btn p-0" onClick="location.href='/login'">
                                            <i class="bi bi-heart fs-5"></i>
                                        </button>
                                        <div class="ms-1">{{ $recipe->count_likes }}</div>
                                    </div>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </x-page-section>
    
    <x-toastr />
    
</x-app-layout>