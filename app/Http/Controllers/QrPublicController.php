<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qr;

class QrPublicController extends Controller
{
    public function show($token)
    {
        $qr = Qr::where('token', $token)
                ->where('is_active', 1)
                ->first();

        if (!$qr) {
            return view('qr.expired');
        }

        // simpan token QR ke session
        session(['qr_token' => $qr->token]);

        return redirect()->route('pengunjung.step1');
    }
}
