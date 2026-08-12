<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function __construct(private GroupService $groupService) {}

    public function index(Request $request)
    {
        $groups = $this->groupService->getPublicGroups($request->user()->id);
        $myGroups = $this->groupService->getMyGroups($request->user()->id);

        return Inertia::render('Social/Index', [
            'groups' => $groups,
            'myGroups' => $myGroups
        ]);
    }

    public function show(Group $group, Request $request)
    {
        $group->load(['owner:id,name,avatar_url', 'members:id,name,avatar_url']);
        
        $isMember = $group->members()->where('user_id', $request->user()->id)->wherePivot('status', 'approved')->exists();
        $isOwner = $group->owner_id === $request->user()->id;
        $isAdmin = $request->user()->role === 'admin';

        // L'admin peut voir le chat et les demandes même s'il n'est pas membre
        $messages = ($isMember || $isOwner || $isAdmin) ? $group->messages()->limit(50)->get() : [];
        $pendingMembers = ($isOwner || $isAdmin) ? $group->members()->wherePivot('status', 'pending')->get(['users.id', 'users.name']) : [];

        return Inertia::render('Social/Show', [
            'group' => $group,
            'isMember' => $isMember,
            'isOwner' => $isOwner,
            'isAdmin' => $isAdmin,
            'messages' => $messages,
            'pendingMembers' => $pendingMembers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_private' => 'boolean',
            'is_approval_required' => 'boolean',
            'max_members' => 'integer|min:2|max:50',
            'start_point' => 'nullable|string|max:100',
            'end_point' => 'nullable|string|max:100',
            'ride_date' => 'nullable|date',
        ]);

        $group = $this->groupService->createGroup($validated, $request->user()->id);

        return redirect()->route('social.groups.show', $group->id)->with('success', 'Groupe créé avec succès !');
    }

    public function join(Group $group, Request $request)
    {
        // Un admin ne rejoins pas un groupe
        if ($request->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Les administrateurs ne rejoignent pas les groupes.');
        }

        $status = $group->is_approval_required ? 'pending' : 'approved';
        $this->groupService->joinGroup($group, $request->user()->id, $status);

        return redirect()->route('social.groups.show', $group->id)->with('success', $status === 'pending' ? 'Demande envoyée !' : 'Vous avez rejoint le groupe !');
    }

    public function approveMember(Group $group, $userId)
    {
        $group->members()->updateExistingPivot($userId, ['status' => 'approved']);
        return redirect()->back()->with('success', 'Membre approuvé.');
    }

    public function storeMessage(Request $request, Group $group)
    {
        // Un admin ne poste pas de messages dans le chat
        if ($request->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Les administrateurs ne peuvent pas poster de messages.');
        }

        $validated = $request->validate(['message' => 'required|string|max:500']);
        
        GroupMessage::create([
            'group_id' => $group->id,
            'user_id' => $request->user()->id,
            'message' => $validated['message']
        ]);

        return redirect()->back();
    }

    public function destroy(Group $group, Request $request)
    {
        // L'admin ou le créateur peuvent supprimer le groupe
        if ($request->user()->role !== 'admin' && $request->user()->id !== $group->owner_id) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $group->delete();
        return redirect()->route('social.index')->with('success', 'Groupe supprimé avec succès.');
    }
}