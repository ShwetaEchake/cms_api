    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\ArticleController;
    use Illuminate\Http\Request;


    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/login',[AuthController::class,'login']);


    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/logout',[AuthController::class,'logout']);
        Route::apiResource('articles', ArticleController::class)->except(['show']);
        Route::get('/articles/list', [ArticleController::class, 'listArticles']);
    });

    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::apiResource('categories', CategoryController::class);
    });





