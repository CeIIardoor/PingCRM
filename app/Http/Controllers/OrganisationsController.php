<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class OrganisationsController extends Controller
{
    public function index()
    {
        return Inertia::render('Organisations/Index', [
            'filters' => Request::all('search', 'trashed'),
            'organisations' => Auth::user()->account->organisations()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($organisation) => [
                    'id' => $organisation->id,
                    'name' => $organisation->name,
                    'phone' => $organisation->phone,
                    'city' => $organisation->city,
                    'deleted_at' => $organisation->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Organisations/Create');
    }

    public function store()
    {
        Auth::user()->account->organisations()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('organisations')->with('success', 'Organisation created.');
    }

    public function edit(Organisation $organisation)
    {
        return Inertia::render('Organisations/Edit', [
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'email' => $organisation->email,
                'phone' => $organisation->phone,
                'address' => $organisation->address,
                'city' => $organisation->city,
                'region' => $organisation->region,
                'country' => $organisation->country,
                'postal_code' => $organisation->postal_code,
                'deleted_at' => $organisation->deleted_at,
                'collaborateurs' => $organisation->collaborateurs()->orderByName()->get()->map->only('id', 'name', 'city', 'phone'),
            ],
        ]);
    }

    public function update(Organisation $organisation)
    {
        $organisation->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Organisation updated.');
    }

    public function destroy(Organisation $organisation)
    {
        $organisation->delete();

        return Redirect::back()->with('success', 'Organisation deleted.');
    }

    public function restore(Organisation $organisation)
    {
        $organisation->restore();

        return Redirect::back()->with('success', 'Organisation restored.');
    }
}
