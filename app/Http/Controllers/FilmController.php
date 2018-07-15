<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Auth;
use App\Film;
use Validator;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            Film::paginate(25)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:3,150',
            'description' => 'required',
            'release_year' => 'required|integer|between:1800,2100',
            'rating' => 'required|integer|between:1,5',
            'ticket_price' => 'required|integer|between:0',
            'country' => 'required',
            'genre' => 'required',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $film = new Film;

            $film->user_id = Auth::guard('token')->id();
            $film->name = $request->name;
            $film->description = $request->description;
            $film->release_year = $request->release_year;
            $film->rating = $request->rating;
            $film->ticket_price = $request->ticket_price;
            $film->country = $request->country;
            $film->genre = $request->genre;
            $film->photo = $request->photo;

            $film->save();
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
    public function show($id)
    {
        return response()->json(
            Film::where('id', $id)->first()
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:3,150',
            'description' => 'required',
            'release_year' => 'required|integer|between:1800,2100',
            'rating' => 'required|integer|between:1,5',
            'ticket_price' => 'required|integer|between:0',
            'country' => 'required',
            'genre' => 'required',
            'photo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $film = Film::where('user_id', Auth::guard('token')->id())->where('id', $id)->first();

        if (!$film) {
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Film not found.'
            ], 404);
        }

        try {
            $film->name = $request->name;
            $film->description = $request->description;
            $film->release_year = $request->release_year;
            $film->rating = $request->rating;
            $film->ticket_price = $request->ticket_price;
            $film->country = $request->country;
            $film->genre = $request->genre;
            $film->photo = $request->photo;

            $film->save();
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
    public function destroy($id)
    {
        $film = Film::where('user_id', Auth::guard('token')->id())->find($id);

        if (!$film) {
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Film not found.'
            ], 404);
        }

        try {
            $film->delete();
        }
        catch (QueryException $e){
            // log: $e->getMessage();
            return response()->json([
                'errors' => 'Query error.'
            ], 400);
        }
    }
}