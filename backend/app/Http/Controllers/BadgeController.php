<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        $badges = Badge::all();
        return response()->json($badges);
    }

    public function show(Badge $badge)
    {
        return response()->json($badge);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:badges',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'type' => 'required|in:automatic,manual,role-based',
            'requirement' => 'nullable|integer',
            'requirement_type' => 'nullable|string',
        ]);

        $badge = Badge::create($validatedData);
        return response()->json($badge, 201);
    }

    public function update(Request $request, Badge $badge)
    {
        $validatedData = $request->validate([
            'name' => 'string|max:255|unique:badges,name,' . $badge->id,
            'description' => 'string',
            'icon' => 'nullable|string',
            'type' => 'in:automatic,manual,role-based',
            'requirement' => 'nullable|integer',
            'requirement_type' => 'nullable|string',
        ]);

        $badge->update($validatedData);
        return response()->json($badge);
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return response()->json(null, 204);
    }

    public function awardBadge(Request $request, User $user)
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id'
        ]);

        $badge = Badge::findOrFail($request->badge_id);
        $user->awardBadge($badge);

        return response()->json([
            'message' => 'Badge awarded successfully',
            'badges' => $user->badges
        ]);
    }

    public function removeBadge(Request $request, User $user)
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id'
        ]);

        $badge = Badge::findOrFail($request->badge_id);
        $user->removeBadge($badge);

        return response()->json([
            'message' => 'Badge removed successfully',
            'badges' => $user->badges
        ]);
    }

    public function checkAndAwardBadges()
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $this->badgeService->checkAndAwardBadges($user);
            return response()->json([
                'message' => 'Badges checked and awarded',
                'badges' => $user->badges
            ]);
        }
        return response()->json(['message' => 'No authenticated user found'], 401);
    }
}