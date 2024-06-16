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
use App\Http\Requests\EmployeeFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployeeController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        /* TODO not working
        $this->authorizeResource(User::class, 'employee');*/
    }

    public function index(Request $request): View
    {
        $employeesQuery = User::where('type', 'E')
            ->orderBy('name');
        $filterByName = $request->query('name');
        if ($filterByName) {
            $employeesQuery->where('name', 'like', "%$filterByName%");
        }
        $employees = $employeesQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'employees.index',
            compact('employees', 'filterByName')
        );
    }


    public function show(User $employee): View
    {
        return view('employees.show',compact('employee'));
    }

    public function create(): View
    {
        $employee = new User();
        $employee->type = 'E';
        return view('employees.create',compact('employee'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.(Auth::User()?->id),
            'password' => ['required', 'confirmed', Password::defaults()],
            'photo_file' => 'sometimes|image|max:4096',
        ]);

        $newEmployee  = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'E'
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/photos');
            $newEmployee->photo_filename = basename($path);
            $newEmployee->save();
        }
        $newEmployee->sendEmailVerificationNotification();


        $url = route('employees.show', ['employee' => $newEmployee]);
        $htmlMessage = "Employee <a href='$url'><u>{$newEmployee->name}</u></a> ({$newEmployee->email}) has been created successfully!";
        return redirect()->route('employees.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $employee): View
    {
        return view('employees.edit',compact('employee'));
    }

    public function update(EmployeeFormRequest $request, User $employee): RedirectResponse
    {
        $validatedData = $request->validated();
        $employee->name = $validatedData['name'];
        $employee->email = $validatedData['email'];
        $employee->type = 'E';
        $employee->save();

        if ($request->hasFile('photo_file')) {
            if ( $employee->photo_filename && Storage::fileExists('public/photos/' . $employee->photo_filename) ) {
                // Delete previous file (if any)
                Storage::delete('public/photos/' . $employee->photo_filename);
            }

            $path = $request->photo_file->store('public/photos');
            $employee->photo_filename = basename($path);
            $employee->save();
        }


        $url = route('employees.show', ['employee' => $employee]);
        $htmlMessage = "Employee <a href='$url'><u>{$employee->name}</u></a> has been updated successfully!";

        return redirect()->route('employees.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $employee): RedirectResponse
    {
        try {
            if ($employee->photo_filename) {
                if (Storage::fileExists('public/photos/' . $employee->photo_filename)) {
                    Storage::delete('public/photos/' . $employee->photo_filename);
                }
            }
            $employee->delete();

            $alertType = 'success';
            $alertMsg = "Employee {$employee->name} has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the employee
                            <a href='$url'><u>{$employee->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('employees.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(User $employee): RedirectResponse
    {
        if ($employee->photo_filename) {
            if (Storage::fileExists('public/photos/' . $employee->photo_filename)) {
                Storage::delete('public/photos/' . $employee->photo_filename);
            }
            $employee->photo_filename = null;
            $employee->save();
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of employee {$employee->name} has been deleted.");
        }
        return redirect()->back();
    }
}
