<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests\GamesRequest;
use App\Result;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Game::all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('admin');
        if ($request->type == 'new') {
            $game = new Game();
            $game->name = $request->name;
            $game->runningTime = $request->time;
            $game->status = 'running';
            $game->save();

            $result = new Result();
            $result->game_id = $game->id;
            $result->game_name = $game->name;
            $result->open = '***';
            $result->close = '***';
            $result->jodi = '**';
            $result->save();
        } elseif ($request->type == 'edit') {
            $game = Game::where('id', $request->gameId)->update([
                'name' => $request->name,
                'runningTime' => $request->time,
                'status' => $request->status
            ]);
        }


        return response($game, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Game::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(GamesRequest $request, $id)
    {
        Gate::authorize('admin');
        $game = Game::find($id);

        $game->update($request->only('name', 'runningTime', 'status'));

        response($game, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('admin');
        Game::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
