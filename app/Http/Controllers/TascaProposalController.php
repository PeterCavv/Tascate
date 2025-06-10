<?php

namespace App\Http\Controllers;

use App\Enums\ManageStatus;
use App\Enums\Role;
use App\Http\Requests\TascaProposal\CreateTascaProposalRequest;
use App\Http\Requests\TascaProposal\UpdateTascaProposalRequest;
use App\Models\Tasca;
use App\Models\TascaProposal;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TascaProposalController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        return Inertia::render('Admin/TascaProposalIndex', [
            'tascasProposals' => TascaProposal::orderBy('updated_at', direction: 'desc')->get(),
        ]);
    }

    public function show(TascaProposal $tascaProposal)
    {
        return Inertia::render('Admin/TascaProposalShow', [
            'tascaProposal' => $tascaProposal,
        ]);
    }

    public function registerForm()
    {
        return Inertia::render('Auth/TascaProposalCreate');
    }

    public function store(CreateTascaProposalRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('dni_picture_path')) {
            $validated['dni_picture_path'] = $request->file('dni_picture_path')->store('dni', 'private');
        }
        if ($request->hasFile('cif_picture_path')) {
            $validated['cif_picture_path'] = $request->file('cif_picture_path')->store('cif', 'private');
        }

        TascaProposal::create($validated);

        return Inertia::render('Auth/TascaProposalCreated');
    }

    public function update(UpdateTascaProposalRequest $request, TascaProposal $tascaProposal)
    {
        $this->authorize('update', $tascaProposal);

        $tascaProposal->update($request->validated());

        return redirect()->route('tascas-proposals.index')->with('toast', [
            'severity' => 'success',
            'summary' => __('messages.toast.updated'),
            'detail' => __('messages.toast.tasca_proposal_updated'),
        ]);
    }

    public function clone(TascaProposal $tascaProposal)
    {
        $this->authorize('update', $tascaProposal);

        $clonedProposal = $tascaProposal->replicate();
        $clonedProposal->status = ManageStatus::PENDING->value;
        $clonedProposal->save();

        return redirect()->route('tascas-proposals.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.cloned'),
                'detail' => __('messages.toast.tasca_proposal_cloned'),
            ]);
    }

    public function approve(TascaProposal $tascaProposal)
    {
        $this->authorize('update', $tascaProposal);

        $user = User::create([
            'name' => $tascaProposal->owner_name,
            'email' => $tascaProposal->owner_email,
            'password' => Hash::make(Str::random(10)),
            'dni' => $tascaProposal->dni,
            'telephone' => $tascaProposal->telephone,
        ]);

        $user->assignRole(Role::TASCA->value);

        Tasca::create([
            'name' => $tascaProposal->tasca_name,
            'address' => $tascaProposal->address,
            'user_id' => $user->id,
            'cif' => $tascaProposal->cif,
            'telephone' => $tascaProposal->telephone,
        ]);

        $tascaProposal->update(['status' => ManageStatus::ACCEPTED->value]);

        return redirect()->route('tascas-proposals.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.updated'),
                'detail' => __('messages.toast.tasca_created'),
            ]);
    }
}
