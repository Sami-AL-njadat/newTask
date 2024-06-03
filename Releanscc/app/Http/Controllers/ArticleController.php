<?php

namespace App\Http\Controllers;

use App\Models\article;
use App\Models\blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article = article::all();

        if ($article->isEmpty()) {
            $data = [
                'status' => 404,
                'message' => 'No article found',
            ];
            return response()->json($data, 404);
        } else {
            $data = [
                'status' => 200,
                'article' => $article,
            ];
            return response()->json($data, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => [
                'required',
                'exists:blogs,id',
                function ($attribute, $value, $fail) {
                    if (Article::where('blog_id', $value)->count() >= 1) {
                        $fail('You can only add up to one articles for the same blog');
                    }
                },
            ],
            'header' => 'required|string|max:255',
            'paragraph' => 'required|string',
            'header_two' => 'nullable|string|max:255',
            'paragraph_two' => 'nullable|string',
            'image1' => 'nullable|image|max:25000',
            'image2' => 'nullable|image|max:25000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $article = Article::create([
            'blog_id' => $request->blog_id,
            'header' => $request->header,
            'paragraph' => $request->paragraph,
            'header_two' => $request->header_two,
            'paragraph_two' => $request->paragraph_two,
            'image1' => $request->image1 ? $this->uploadImage($request->image1) : null,
            'image2' => $request->image2 ? $this->uploadImage($request->image2) : null,
        ]);


        return response()->json([
            'status' => 201,
            'message' => 'Article created successfully',
            'article' => $article,
        ], 201);
    }


    private function uploadImage($image)
    {
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('ArticleImage'), $filename);
        return $filename;
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blog = Blog::find($id);
        $articles = Article::where('blog_id', $id)->get();

        if (is_null($blog)) {
            return response()->json([
                'status' => 404,
                'message' => 'No blog found with this ID.',
            ], 404);
        }


        $filteredArticles = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'header' => $article->header,
                'paragraph' => $article->paragraph,
                'header_two' => $article->header_two,
                'paragraph_two' => $article->paragraph_two,
                'image1' => $article->image1,
                'image2' => $article->image2,
            ];
        });

        $data = [
            'status' => 200,
            'blog' => $blog,
            'articles' => $filteredArticles,
        ];

        return response()->json($data, 200);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $articleId)
    {
        $validator = Validator::make($request->all(), [
            'blog_id' => 'exists:blogs,id',
            'header' => 'string|max:255',
            'paragraph' => 'string',
            'header_two' => 'string|max:255',
            'paragraph_two' => 'string',
            'image1' => 'nullable|image|max:25000',
            'image2' => 'nullable|image|max:25000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $article = Article::find($articleId);

        if (!$article) {
            return response()->json([
                'status' => 404,
                'message' => 'Article not found'
            ], 404);
        }

        if ($request->has('blog_id')) {
            $article->blog_id = $request->blog_id;
        }
        if ($request->has('header')) {
            $article->header = $request->header;
        }
        if ($request->has('paragraph')) {
            $article->paragraph = $request->paragraph;
        }
        if ($request->has('header_two')) {
            $article->header = $request->header;
        }
        if ($request->has('paragraph_two')) {
            $article->paragraph = $request->paragraph;
        }
        if ($request->hasFile('image1')) {
            $article->image1 = $this->uploadImage($request->file('image1'));
        }
        if ($request->hasFile('image2')) {
            $article->image2 = $this->uploadImage($request->file('image2'));
        }

        $article->save();

        return response()->json([
            'status' => 200,
            'message' => 'Article updated successfully',
            'article' => $article,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = article::find($id);
        if (!$article) {
            return response()->json(['message' => 'article not found'], 404);
        }
        $article->delete();

        return response()->json(['message' => 'article deleted successfully'], 200);
    }
}
