<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(RecipeController::class)->group(function () {
    Route::get('/', 'index')->name('recipe.index');
    
    Route::middleware('auth')->group(function () {
        Route::post('/recipes', 'store')->name('recipe.store');
        Route::get('/recipes/create', 'create')->name('recipe.create');
    });
    
    Route::get('/recipes/{recipe}', 'show')->name('recipe.show');
    
    Route::middleware('auth')->group(function () {
        Route::put('/recipes/{recipe}', 'update')->name('recipe.update');
        Route::delete('/recipes/{recipe}', 'destroy')->name('recipe.destroy');
        Route::get('/recipes/{recipe}/edit', 'edit')->name('recipe.edit');
    });
});

Route::put('/recipes/{recipe}/like', [LikeController::class, 'like'])->middleware('auth')->name('recipe.like');
Route::delete('/recipes/{recipe}/unlike', [LikeController::class, 'unlike'])->middleware('auth')->name('recipe.unlike');

Route::controller(TagController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::put('/tags/{tag}', 'update')->name('tag.update');
        Route::post('/tags', 'store')->name('tag.store');
        Route::delete('/tags/{tag}', 'destroy')->name('tag.destroy');
    });
});

require __DIR__.'/auth.php';
