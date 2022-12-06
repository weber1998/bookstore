<?php

namespace App\Http\Controllers;

use App\Models\Copy;
use App\Models\Lending;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller
{
    //
    public function index(){
        $lendings =  Lending::all();
        return $lendings;
    }

    public function show ($user_id, $copy_id, $start)
    {
        $lending = Lending::where('user_id', $user_id)->where('copy_id', $copy_id)->where('start', $start)->get();
        return $lending[0];
    }
    public function destroy($user_id, $copy_id, $start)
    {
        LendingController::show($user_id, $copy_id, $start)->delete();
    }

    public function store(Request $request)
    {
        $lending = new Lending();
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->save();
    }

    public function update(Request $request, $user_id, $copy_id, $start)
    {
        $lending = LendingController::show($user_id, $copy_id, $start);
        $lending->user_id = $request->user_id;
        $lending->copy_id = $request->copy_id;
        $lending->start = $request->start;
        $lending->save();
    }

    public function userLendingsList()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')->where('user_id','=', $user->id)->get();
        return $lendings;
    }

    public function userLendingsCount()
    {
        $user = Auth::user();	//bejelentkezett felhasználó
        $lendings = Lending::with('user_c')->where('user_id','=', $user->id)->distinct('copy_id')->count();
        return $lendings;
    }

    //view-k:
    public function newView()
    {
        //új rekord(ok) rögzítése
        $users = User::all();
        $copies = Copy::all();
        return view('lending.new', ['users' => $users, 'copies' => $copies]);
    }

    public function moreLendings($db) {
        $user = Auth::user();
        $lendings = DB::table('lendings as l')
        ->selectRaw('count(l.copy_id) as number_of_copies, l.copy_id')
        ->where('l.user_id', $user->id)
        ->groupBy('l.copy_id')
        ->having('number_of_copies','>='.$db)
        ->get();
        
        return $lendings;
    }

    public function lengthen($copy_id, $start) {
        $user = Auth::user();
        $book = DB::table('lending as l')
        ->select('c.book_id')
        ->join('copies as c','l.copy_id','=','c.copy_id')
        ->where('l.user_id', $user)
        ->where('l.start', $start)
       // ->get()
    }

    public function books_back() {
        $books = DB::table('lendings as l')
        ->join('copies as c', 'l.copy_id', '=', 'c.copy_id')
        ->join('books as b', 'c.book_id', '=', 'b.book_id')
        ->select("b.author")
    }
}
