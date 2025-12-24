<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class AdminCouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);

        $coupons = Coupon::with('user')
            ->whereNotNull('user_id')
            ->latest('date_debut')
            ->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function toggle($id)
    {
        $user = Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);

        $coupon = Coupon::findOrFail($id);
        $coupon->update(['actif' => !$coupon->actif]);

        return back()->with('success', "Le coupon {$coupon->code} a été " . ($coupon->actif ? 'activé' : 'désactivé'));
    }
}
