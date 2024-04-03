<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\DeletedArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{
    public function index()
    {
        $data = Articles::all();
        return response()->json($data);
    }

    public function destroy($id)
    {
        $article = Articles::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $deletedArticleTitle = $article->title;

        DB::beginTransaction();

        try {
            DeletedArticle::create(['title' => $deletedArticleTitle]);

            $article->delete();

            DB::commit();

            return response()->json(['message' => 'Article deleted successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Failed to delete article'], 500);
        }
    }
}
