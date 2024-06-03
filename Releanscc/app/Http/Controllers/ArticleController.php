<?php

namespace App\Http\Controllers;

use App\Models\article;
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
            'blog_id' => 'required|exists:blogs,id',
            'header' => 'required|string|max:255',
            'paragraph' => 'required|string',
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
    public function show(article $article)
    {
        //
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
    public function destroy(article $article)
    {
        //
    }
}
