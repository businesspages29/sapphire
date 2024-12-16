<?php

namespace App\Http\Controllers;

use App\Enums\GameResult;
use App\Models\GameMatch;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $maxTeams = 8;

    public function index()
    {
        $records = GameMatch::get();

        return view('home', compact('records'));
    }

    public function create()
    {
        $data = [
            'maxTeams' => $this->maxTeams,
        ];

        return view('create', compact('data'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'team' => 'required|array',
            'team.*' => 'required|string|max:255',
        ]);

        $match = GameMatch::create([]);
        $teams = array_map(fn ($teamName) => ['name' => $teamName], $validatedData['team']);
        foreach ($teams as $key => $team) {
            $match->teams()->create($team);
        }

        return redirect()->route('home.matchRound', ['id' => $match->id, 'round' => 1]);
    }

    // match round
    public function matchRound(Request $request, $id, $round = 1)
    {
        $match = GameMatch::findOrFail($id);
        $teams = $match->teams()
            ->inRandomOrder()
            // ->where('round', $round - 1)
            ->when(! ($round == '1'), function ($query) use ($round) {
                return $query->where('round', $round - 1)->where('result', GameResult::WIN);
            })
            ->get();

        $totalTeams = $teams->count();
        $totalRounds = $totalTeams - 1;
        $round = $round > $totalRounds ? $totalRounds : $round;
        $round = $round < 1 ? 1 : $round;
        for ($i = 0; $i < $totalTeams; $i++) {
            $team = $teams[$i];
            $team->round = intval($round);
            if ($i % 2 == 0) {
                $team->result = GameResult::LOSE->value;
            } else {
                $team->result = GameResult::WIN->value;
            }
            $team->save();
        }
        $winnerTeams = $match->teams()->winner($round)->get();
        $loserTeams = $match->teams()->loser($round)->get();

        $data = [
            'match' => $match,
            'winnerTeams' => $winnerTeams,
            'loserTeams' => $loserTeams,
            'round' => $round,
            'totalRounds' => $totalRounds,
        ];

        return view('matchRound', compact('data'));
    }
}
