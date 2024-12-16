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
            // ->when(! ($round == '1'), function ($query) use ($round) {
            //     return $query->where('round', $round - 1)->where('result', GameResult::WIN);
            // })
            ->when($round == '1', function ($query) {
                return $query->whereNull('round_1');
            })
            ->when($round == '2', function ($query) {
                return $query->whereNull('round_2')->where('round_1', GameResult::WIN);
            })
            ->when($round == '3', function ($query) {
                return $query->whereNull('round_3')->where('round_2', GameResult::WIN);
            })
            ->when($round == '4', function ($query) {
                return $query->whereNull('result')->where('round_4', GameResult::WIN);
            })
            ->get();
        $totalTeams = $teams->count();
        $totalRounds = $totalTeams - 1;
        // $round = $round > $totalRounds ? $totalRounds : $round;
        // $round = $round < 1 ? 1 : $round;
        $winnerTeams = [];
        $wildcardTeams = [];

        if ($round == 1 && $match->completed_round == 0) {
            for ($i = 0; $i < $totalTeams; $i++) {
                $team = $teams[$i];
                if ($i % 2 == 0) {
                    $team->round_1 = GameResult::LOSE->value;
                } else {
                    $team->round_1 = GameResult::WIN->value;
                }
                $team->save();
            }
            $match->completed_round = $round;
            $match->save();
        } elseif ($round == 2 && $match->completed_round == 1) {
            for ($i = 0; $i < $totalTeams; $i++) {
                $team = $teams[$i];
                if ($i % 2 == 0) {
                    $team->round_2 = GameResult::LOSE->value;
                } else {
                    $team->round_2 = GameResult::WIN->value;
                }
                $team->save();
            }
            $wildcardTeams = $match->teams()->where('round_1', GameResult::LOSE)->get();
            $totalTeams = $wildcardTeams->count();
            for ($i = 0; $i < $totalTeams; $i++) {
                $team = $wildcardTeams[$i];
                if ($i % 2 == 0) {
                    $team->round_3 = GameResult::LOSE->value;
                } else {
                    $team->round_3 = GameResult::WIN->value;
                }
                $team->save();
            }
            $match->completed_round = $round;
            $match->save();
        } elseif ($round == 3 && $match->completed_round == 2) {
            $wildcardTeams = $match->teams()->where('round_3', GameResult::WIN)->get();
            $teams = $teams->merge($wildcardTeams);
            $totalTeams = $teams->count();
            for ($i = 0; $i < $totalTeams; $i++) {
                $team = $teams[$i];
                if ($i % 2 == 0) {
                    $team->round_4 = GameResult::LOSE->value;
                } else {
                    $team->round_4 = GameResult::WIN->value;
                }
                $team->save();
            }
            $match->completed_round = $round;
            $match->save();
        } elseif ($round == 4 && $match->completed_round == 3) {
            for ($i = 0; $i < $totalTeams; $i++) {
                $team = $teams[$i];
                if ($i % 2 == 0) {
                    $team->result = GameResult::LOSE->value;
                } else {
                    $team->result = GameResult::WIN->value;
                }
                $team->save();
            }
            $match->completed_round = $round;
            $match->save();
        }

        if ($round == 1) {
            $winnerTeams = $match->teams()->where('round_1', GameResult::WIN)->get();
        } elseif ($round == 2) {
            $winnerTeams = $match->teams()->where('round_2', GameResult::WIN)->get();
            $wildcardTeams = $match->teams()->where('round_3', GameResult::WIN)->get();
        } elseif ($round == 3) {
            $winnerTeams = $match->teams()->where('round_4', GameResult::WIN)->get();
        } elseif ($round == 4) {
            $winnerTeams = $match->teams()->where('result', GameResult::WIN)->get();
        }

        $data = [
            'match' => $match,
            'winnerTeams' => $winnerTeams,
            'wildcardTeams' => $wildcardTeams,
            'round' => $round,
            'totalRounds' => $totalRounds,
        ];

        return view('matchRound', compact('data'));
    }
}
