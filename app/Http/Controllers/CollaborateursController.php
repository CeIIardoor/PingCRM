<?php

namespace App\Http\Controllers;

use App\Models\Collaborateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CollaborateursController extends Controller
{
    public function index()
    {
        return Inertia::render('Collaborateurs/Index', [
            'filters' => Request::all('search', 'trashed'),
            'collaborateurs' => Auth::user()->account->collaborateurs()
                ->with('organisation')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($collaborateur) => [
                    'id' => $collaborateur->id,
                    'name' => $collaborateur->name,
                    'phone' => $collaborateur->phone,
                    'city' => $collaborateur->city,
                    'deleted_at' => $collaborateur->deleted_at,
                    'organisation' => $collaborateur->organisation ? $collaborateur->organisation->only('name') : null,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Collaborateurs/Create', [
            'organisations' => Auth::user()->account
                ->organisations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        Auth::user()->account->collaborateurs()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organisation_id' => ['nullable', Rule::exists('organisations', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('collaborateurs')->with('success', 'Collaborateur created.');
    }

    public function edit(Collaborateur $collaborateur)
    {
        return Inertia::render('Collaborateurs/Edit', [
            'collaborateur' => [
                'id' => $collaborateur->id,
                'first_name' => $collaborateur->first_name,
                'last_name' => $collaborateur->last_name,
                'organisation_id' => $collaborateur->organisation_id,
                'email' => $collaborateur->email,
                'phone' => $collaborateur->phone,
                'address' => $collaborateur->address,
                'city' => $collaborateur->city,
                'region' => $collaborateur->region,
                'country' => $collaborateur->country,
                'postal_code' => $collaborateur->postal_code,
                'deleted_at' => $collaborateur->deleted_at,
            ],
            'organisations' => Auth::user()->account->organisations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Collaborateur $collaborateur)
    {
        $collaborateur->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organisation_id' => [
                    'nullable',
                    Rule::exists('organisations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Collaborateur updated.');
    }

    public function destroy(Collaborateur $collaborateur)
    {
        $collaborateur->delete();

        return Redirect::back()->with('success', 'Collaborateur deleted.');
    }

    public function restore(Collaborateur $collaborateur)
    {
        $collaborateur->restore();

        return Redirect::back()->with('success', 'Collaborateur restored.');
    }
}
