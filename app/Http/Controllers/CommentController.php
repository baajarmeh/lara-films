<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Auth;
use App\Film;
use App\Comment;
use Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filmId)
    {
        return response()->json(
            Comment::where('film_id', $filmId)->paginate(25)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($filmId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $filmId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $film = Film::find($filmId);

        if (!$film) {
            return response()->json([
                'errors' => 'Film does not exist.'
            ], 404);
        }

        try {
            $comment = new Comment;

            $comment->user_id = Auth::guard('token')->id();
            $comment->name = $request->name;
            $comment->film_id = $film->id;
            $comment->comment = $request->comment;

            $comment->save();
        }
        catch (QueryException $e){
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Query error.'
            ], 400);
        }

        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($filmId, $id)
    {
        return response()->json(
            Comment::where('film_id', $filmId)->where('id', $id)->first()
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($filmId, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $filmId, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $comment = Comment::where('user_id', Auth::guard('token')->id())
            ->where('film_id', $filmId)
            ->where('id', $id)
            ->first();

        if (!$comment) {
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Comment not found.'
            ], 404);
        }

        $film = Film::find($filmId);

        if (!$film) {
            return response()->json([
                'errors' => 'Film does not exist.'
            ], 404);
        }

        try {
            $comment->name = $request->name;
            $comment->comment = $request->comment;

            $comment->save();
        }
        catch (QueryException $e){
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Query error.'
            ], 400);
        }

        return response('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($filmId, $id)
    {
        $comment = Comment::where('user_id', Auth::guard('token')->id())
            ->where('film_id', $filmId)
            ->where('id', $id)
            ->first();

        if (!$comment) {
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Comment not found.'
            ], 404);
        }

        try {
            $comment->delete();
        }
        catch (QueryException $e){
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Query error.'
            ], 400);
        }
    }
}