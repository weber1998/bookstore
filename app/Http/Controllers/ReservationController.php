<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\reservations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    //
    public function store(Request $request) {
        $reservations = new reservations();
        $reservations->book_id = $request->book_id;
        $reservations->user_id = $request->user_id;
        $reservations->start = $request->start;
        $reservations->message = $request->message;
        $reservations->message_date = $request->message_date;
        $reservations->status = $request->status;
    }

    public function elojegyzesDb() {
        $user = Auth::user();
        $reservations = DB::table('reservations as r')
            ->select('r.book_id',$user->id)
            ->count();
        return $reservations;
    }

    public function booksWithR(){
        //$user = Auth::user();	//bejelentkezett felhasználó
        $books = reservations::with('book_c')->with('user_c')
        ->where('message', 0)
        ->get();
        return $books;
    }

    public function usersWithR(){
        //$user = Auth::user();	//bejelentkezett felhasználó
        $users = reservations::with('user_c')
        ->get();
        return $users;
    }

    public function deleteOldReservs() {
        $reservations = DB::table('reservations')
            ->where('status', 1)
            ->delete();
        return $reservations;
    }
}
