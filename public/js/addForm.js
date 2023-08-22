//材料入力エリアの追加・削除
$(function()
{
    let id = 2;
    
    $('.add_ingredient').click(function()
    {
        let add = '<div class="mb-3 d-flex justify-content-between added_ingredient"><div style="width: 48%;"><input name="recipe[ingredient][]" id="ingredient'+id+'" class="form-control" required placeholder="材料'+id+'"></div><div class="d-flex align-items-center">：</div><div style="width: 48%;"><input name="recipe[quantity][]" id="quantity'+id+'" class="form-control" required placeholder="数量'+id+'"></div>';
        
        if(id <= 10){
            $('.ingredient_area').append(add);
            id++;
        }
    });
    
    $('.delete_ingredient').click(function()
    {
        if(id != 2){
            if( confirm('最終行を削除してもよろしいですか？') ){
                $(".added_ingredient:last").remove();
                id--;
            }
        }
    });
});
        
//調理法入力エリアの追加・削除
$(function()
{
    let id = 2;
        
    $('.add_method').click(function()
    {
        let add = '<div class="mb-3 added_method"><textarea name="recipe[method][]" class="form-control" id="method'+id+'" maxlength="500" rows="2" required placeholder="調理法'+id+'"></textarea></div>';
            
        if(id <= 10){
            $('.method_area').append(add);
            id++;
        }
    });
        
    $('.delete_method').click(function()
    {
        if(id != 2){
            if( confirm('最終行を削除してもよろしいですか？') ){
                $(".added_method:last").remove();
                id--;
            } 
        }
    });
});