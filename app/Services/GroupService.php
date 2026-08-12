<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{
    public function getPublicGroups(int $userId): Collection
    {
        return Group::where('is_private', false)
            ->with(['owner:id,name,avatar_url', 'members:id'])
            ->withCount('members')
            ->latest()
            ->get()
            ->each(function ($group) use ($userId) {
                $member = $group->members->firstWhere('id', $userId);
                $group->is_joined = $member ? $member->pivot->status : false;
            });
    }

    public function getMyGroups(int $userId): Collection
    {
        return Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'approved');
        })
        ->withCount('members')
        ->latest()
        ->get();
    }

    public function createGroup(array $data, int $ownerId): Group
    {
        $group = Group::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'owner_id' => $ownerId,
            'is_private' => $data['is_private'] ?? false,
            'is_approval_required' => $data['is_approval_required'] ?? false,
            'max_members' => $data['max_members'] ?? 10,
            'start_point' => $data['start_point'] ?? null,
            'end_point' => $data['end_point'] ?? null,
            'ride_date' => $data['ride_date'] ?? null,
        ]);

        $group->members()->attach($ownerId, ['role' => 'admin', 'status' => 'approved', 'joined_at' => now()]);

        return $group;
    }

    public function joinGroup(Group $group, int $userId, string $status = 'approved'): void
    {
        if (!$group->members()->where('user_id', $userId)->exists()) {
            $group->members()->attach($userId, ['role' => 'member', 'status' => $status, 'joined_at' => now()]);
        }
    }
}