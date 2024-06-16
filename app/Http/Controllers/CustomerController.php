<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CustomerFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        //
    }

    public function index(Request $request): View{

        $customersQuery = User::where('type', 'C')
            ->orderBy('name');
        $filterByName = $request->query('name');
        if ($filterByName) {
            $customersQuery->where('name', 'like', "%$filterByName%");
        }
        $customers = $customersQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'customers.index',
            compact('customers', 'filterByName')
        );
    }

    public function show(User $customer): View
    {
        return view('customers.show',compact('customer'));
    }

    public function create(): View
    {
        $customer = new User();
        $customer->type = 'C';
        return view('customers.create',compact('customer'));
    }

    public function store(CustomerFormRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $newCustomer = new User();
        $newCustomer->type = 'C';
        $newCustomer->name = $validatedData['name'];
        $newCustomer->email = $validatedData['email'];
        $newCustomer->password = bcrypt($validatedData['password']);
        $newCustomer->save();
        $newCustomer->gender = $validatedData['gender'];

        $newCustomer->customer()->create([
            'nif' => $validatedData['nif'],
            'payment_type' => $validatedData['payment_type'],
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/photos');
            $newCustomer->photo_url = basename($path);
            $newCustomer->save();
        }

        $newCustomer->sendEmailVerificationNotification();
        $url = route('customers.show', ['customer' => $newCustomer]);
        $htmlMessage = "Customer <a href='$url'><u>{$newCustomer->name}</u></a> has been created successfully!";
        return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $customer): View
    {
        return view('customers.edit',compact('customer'));
    }

    public function update(CustomerRequest $request){
        $validatedData = $request->validated();
        $customer->type = 'C';
        $customer->name = $validatedData['name'];
        $customer->email = $validatedData['email'];

        $customer->gender = $validatedData['gender'];
        $customer->save();

        $customer->customer->nif = $validatedData['nif'];
        $customer->customer->payment_type = $validatedData['payment_type'];

        if ($request->hasFile('photo_file')) {
            if (
                $customer->photo_url &&
                Storage::fileExists('public/photos/' . $customer->photo_url)
            ) {
                Storage::delete('public/photos/' . $customer->photo_url);
            }
            $path = $request->photo_file->store('public/photos');
            $customer->photo_url = basename($path);
            $customer->save();
        }

        $url = route('customers.show', ['customer' => $customer]);
        $htmlMessage = "Customer <a href='$url'><u>{$customer->name}</u></a> has been updated successfully!";
        return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);

        if ($request->user()->can('viewAny', User::class)) {
            return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
        }
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $customer): RedirectResponse
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "Customer $customer->name has been deleted successfully!");
    }

    public function destroyPhoto(User $customer): RedirectResponse
    {
        if ($customer->photo_url) {
            if (Storage::fileExists('public/photos/' . $customer->photo_url)) {
                Storage::delete('public/photos/' . $customer->photo_url);
            }
            $customer->photo_url = null;
            $customer->save();
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of customer {$customer->name} has been deleted.");
        }
        return redirect()->back();
    }

}
