<x-app-layout>
    <x-page-section>
        <x-slot name="title">レシピ詳細</x-slot>

        <div class="my-4 d-flex justify-content-around">
            <div class="d-flex justify-content-center">
                <img src="{{ asset('storage/images/'.$recipe->image_name) }}" style='max-width: 90%; max-height: 200px;'>
            </div>
            <div class="d-flex align-items-center" style="width: 30%;">
                <div class="me-5 d-flex align-items-center justify-content-center">
                    <a href='profile.php?id=" . $user_id . "'><i class="bi bi-person-circle fs-3 text-secondary"></i></a>
                    <div class="ms-2">
                        {{ $recipe->user->name }}さん
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-eye-fill fs-3"></i><div class="ms-1">{{ $recipe->count_access_logs }}</div>
                    </div>
                    <div class="mx-3 fs-4">/</div>
                    <div>
                        @auth
                            @if($like->isLikedBy(Auth::user()->id, $recipe->id))
                                <div class="d-flex align-items-center">
                                    <form action="/recipes/{{ $recipe->id }}/unlike" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0">
                                            <i class="bi bi-heart-fill fs-3 text-danger"></i>
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
                                            <i class="bi bi-heart text-danger fs-3"></i>
                                        </button>
                                    </form>
                                    <div id="like_count" class="ms-1">{{ $recipe->count_likes }}</div>
                                </div>
                            @endif
                        @endauth
                        @guest
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn p-0" onClick="location.href='/login'">
                                    <i class="bi bi-heart fs-3"></i>
                                </button>
                                <div class="ms-1">{{ $recipe->count_likes }}</div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
                    
        <div class="mb-4 d-flex justify-content-between">
            <div style="width: 48%;">
                <label class="form-label">#タグ（任意）</label>
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
        
    </x-page-section>
    
    <x-toastr />
    
</x-app-layout>
    
    