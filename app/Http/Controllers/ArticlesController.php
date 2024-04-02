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
        // Find the article to delete
        $article = Articles::find($id);

        // Check if the article exists
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Store the title of the deleted article
        $deletedArticleTitle = $article->title;

        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Create a record in the deleted_articles table
            DeletedArticle::create(['title' => $deletedArticleTitle]);

            // Delete the article
            $article->delete();

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json(['message' => 'Article deleted successfully']);
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurred
            DB::rollback();

            // Return an error response
            return response()->json(['message' => 'Failed to delete article'], 500);
        }
    }
}
