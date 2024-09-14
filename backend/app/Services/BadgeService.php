<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Contracts\Auth\Authenticatable;

class BadgeService
{
    public function checkAndAwardBadges(Authenticatable $user)    {
        if (!$user instanceof User) {
            return;
        }
        $this->checkPostBadges($user);
        // $this->checkEventBadges($user);
        $this->checkRoleBadges($user);
    }
    private function checkPostBadges(User $user)
    {
        $postCount = $user->posts()->count();

        if ($postCount == 1) {
            $this->awardBadge($user, 'First post');
        }
    }
    // private function checkEventBadges(User $user)
    // {
    //     $eventCount = $user->events()->count();

    //     if ($eventCount == 1) {
    //         $this->awardBadge($user, 'First participation');
    //     }
    // }

    private function checkRoleBadges(User $user)
    {
        if ($user->hasRole('admin')) {
            $this->awardBadge($user, 'Administrator');
        }

        if ($user->hasRole('moderator')) {
            $this->awardBadge($user, 'Moderator');
        }
    }

    private function awardBadge(User $user, string $badgeName)
    {
        $badge = Badge::where('name', $badgeName)->first();
        if ($badge) {
            $user->awardBadge($badge);
        }
    }
}
