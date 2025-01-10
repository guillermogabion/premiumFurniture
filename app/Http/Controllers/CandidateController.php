<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Positions;
use App\Models\User;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = ['ID', 'Name', 'Running Position', 'Status', 'Action'];
        $user = User::get();
        $positions = Positions::get();
        $candidates = Candidate::with('user_candidates', 'candidate_position')->when($search, function ($query, $search) {
            $query->whereHas('user_candidates', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('candidate_position', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
                ->orWhere('status', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.candidates', ['headers' => $table_header, 'candidates' => $candidates, 'search' => $search, 'positions' => $positions, 'users' => $user]);
    }

    public function store(Request $request)
    {

        $candidates = new Candidate();
        $candidates->user_id = $request->input('user_id');
        $candidates->position_id = $request->input('position_id');
        $candidates->save();

        return redirect()->route('candidates');
    }

    public function update(Request $request)
    {

        $candidates = Candidate::findOrFail($request->id);
        $candidates->fill($request->all());
        $candidates->save();

        return redirect()->route('candidates')->with('success', 'Candidates updated successfully');
    }

    public function updateStatus(Request $request)
    {

        $candidates = Candidate::findOrFail($request->id);
        $candidates->status = $request->input('status');
        $candidates->save();

        return redirect()->route('candidates')->with('success', 'Candidates status updated successfully');
    }
}
