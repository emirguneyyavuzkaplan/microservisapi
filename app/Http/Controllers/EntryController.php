<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function create(Request $request)
    {
        $user = auth()->user();
        $entryCount = $user->entries()->whereDate('entry_time', today())->count();

        if ($entryCount >= 2) {
            return response()->json(['error' => 'Günlük geçiş hakkınız dolmuştur.'], 400);
        }

        Entry::create([
            'user_id' => $user->id,
            'entry_time' => now(),
        ]);

        return response()->json(['message' => 'Geçiş başarılı.'], 200);
    }
}
