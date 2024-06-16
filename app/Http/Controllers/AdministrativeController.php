<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\AdministrativeFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdministrativeController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        /* TODO not working
        $this->authorizeResource(User::class, 'administrative');*/
    }

    public function index(Request $request): View
    {
        $administrativesQuery = User::where('type', 'A')
            ->orderBy('name');
        $filterByName = $request->query('name');
        if ($filterByName) {
            $administrativesQuery->where('name', 'like', "%$filterByName%");
        }
        $administratives = $administrativesQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'administratives.index',
            compact('administratives', 'filterByName')
        );
    }


    public function show(User $administrative): View
    {
        return view('administratives.show',compact('administrative'));
    }

    public function create(): View
    {
        $administrative = new User();
        $administrative->type = 'A';
        return view('administratives.create',compact('administrative'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.(Auth::User()?->id),
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo_file' => 'sometimes|image|max:4096',
        ]);

        $newAdministrative = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'A'
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/photos');
            $newAdministrative->photo_filename = basename($path);
            $newAdministrative->save();
        }
        $newAdministrative->sendEmailVerificationNotification();


        $url = route('administratives.show', ['administrative' => $newAdministrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$newAdministrative->name}</u></a> has been created successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $administrative): View
    {
        return view('administratives.edit',compact('administrative'));
    }

    public function update(AdministrativeFormRequest $request, User $administrative): RedirectResponse
    {
        $validatedData = $request->validated();
        $administrative->name = $validatedData['name'];
        $administrative->email = $validatedData['email'];
        $administrative->type = 'A';
        $administrative->save();

        if ($request->hasFile('photo_file')) {
            if ( $administrative->photo_filename && Storage::fileExists('public/photos/' . $administrative->photo_filename)) {
                // Delete previous file (if any)
                Storage::delete('public/photos/' . $administrative->photo_filename);
            }

            $path = $request->photo_file->store('public/photos');
            $administrative->photo_filename = basename($path);
            $administrative->save();
        }


        $url = route('administratives.show', ['administrative' => $administrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$administrative->name}</u></a> has been updated successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $administrative): RedirectResponse
    {
        try {
            if ($administrative->photo_filename) {
                if (Storage::fileExists('public/photos/' . $administrative->photo_filename)) {
                    Storage::delete('public/photos/' . $administrative->photo_filename);
                }
            }
            $administrative->delete();

            $alertType = 'success';
            $alertMsg = "Administrative {$administrative->name} has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the administrative
                            <a href='$url'><u>{$administrative->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('administratives.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(User $administrative): RedirectResponse
    {
        if ($administrative->photo_filename) {
            if (Storage::fileExists('public/photos/' . $administrative->photo_filename)) {
                Storage::delete('public/photos/' . $administrative->photo_filename);
            }
            $administrative->photo_filename = null;
            $administrative->save();
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of administrative {$administrative->name} has been deleted.");
        }
        return redirect()->back();
    }
}
