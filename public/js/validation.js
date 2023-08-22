$(function(){
  $('.recipe_form').validate({
      
    ignore: [],
      
    rules: {
        
      'recipe[category]': { 
        required: true, 
      },
      'recipe[cooking_level]': { 
        required: true, 
      },
      'recipe[cooking_time]': { 
        required: true, 
      },
      'recipe[title]': { 
        required: true, 
        maxlength: 50, 
      },
      'recipe[amount]': { 
        max: 10,
        min: 1
      },
      'recipe[ingredient][]': { 
        required: true, 
        maxlength: 50,
      },
      'recipe[quantity][]': { 
        required: true, 
        maxlength: 50, 
      },
      'recipe[method][]': { 
        required: true, 
        maxlength: 500, 
      },
      'recipe[advice]': { 
        maxlength: 1000, 
      },
      
    },
    
    onfocusout: function(element) {
      $(element).valid();
    },
    
    errorPlacement: function(error, element){
      var errorKey = $(element).attr('id') + 'Error';
      $('#error_' + errorKey).remove();
      element.addClass('is-invalid');
      const errorP = $('<p class="mb-0">').text(error[0].innerText);
      const errorDiv = $('<div class="invalid-feedback" id="error_' + errorKey + '">').append(errorP);
      element.parent().append(errorDiv);
    },
    
    success: function(error, element) {
      var errorKey = $(element).attr('id') + 'Error';
      $('#error_' + errorKey).remove();
      $(error).remove();
      $(element).removeClass('is-invalid');
      $(element).removeClass('error');
    },
      
  });
});

//tag_form validation
$(function(){
  $('#tag_form').validate({
      
    rules: {
      create_tag: { required: true, maxlength: 10 },
    },
    
    onfocusout: function(element) {
      $(element).valid();
    },
    
    errorPlacement: function(error, element){
      var errorKey = $(element).attr('id') + 'Error';
      $('#error_' + errorKey).remove();
      element.addClass('is-invalid');
      const errorP = $('<p class="mb-0">').text(error[0].innerText);
      const errorDiv = $('<div class="invalid-feedback" id="error_' + errorKey + '">').append(errorP);
      element.parent().append(errorDiv);
    },
    
    success: function(error, element) {
      var errorKey = $(element).attr('id') + 'Error';
      $('#error_' + errorKey).remove();
      $(error).remove();
      $(element).removeClass('is-invalid');
      $(element).removeClass('error');
    },
      
  });
});

