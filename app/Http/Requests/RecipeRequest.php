<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'recipe.image' => 'nullable|file|mimes:jpeg,png,jpg,bmb',
            'recipe.amount' => 'required|integer',
            'recipe.title' => 'required|string|max:50',
            'recipe.ingredient.*' => 'required|string|max:50',
            'recipe.quantity.*' => 'required|string|max:50',
            'recipe.method.*' => 'required|string|max:500',
            'recipe.advice' => 'required|string|max:1000',
        ];
    }
}
