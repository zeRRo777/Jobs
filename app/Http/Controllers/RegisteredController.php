<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRegisteredRequest;
use App\Http\Requests\RegisteredRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredController extends Controller
{
    public function create(): View
    {
        return view('pages.auth.register');
    }

    public function store(RegisteredRequest $request): RedirectResponse
    {
        $dataValidated = $request->validated();

        $dataValidated['type'] = 2;

        $dataValidated['password'] = Hash::make($dataValidated['password']);

        $user = User::create($dataValidated);

        // event(new Registered($user));

        Auth::login($user, true);

        return redirect()->route('vacancies');
    }

    public function admin_create(): View
    {
        return view('pages.auth.admin.register');
    }

    public function admin_store(AdminRegisteredRequest $request): RedirectResponse
    {
        $dataValidated = $request->validated();

        $dataValidated['type'] = 1;

        $dataValidated['password'] = Hash::make($dataValidated['password']);

        if (isset($dataValidated['company'])) {

            $company = new Company();

            $company->name = $dataValidated['company'];

            $company->save();

            $dataValidated['company_id'] = $company->id;

            unset($dataValidated['company']);
        } else if (isset($dataValidated['secret_code'])) {
            $company = Company::where('secret_code', $dataValidated['secret_code'])->firstOrFail();

            $company->secret_code = null;

            $company->save();

            $dataValidated['company_id'] = $company->id;

            unset($dataValidated['secret_code']);
        }

        $user = User::create($dataValidated);

        // event(new Registered($user));

        Auth::login($user, true);

        return redirect()->route('company.show', $company->id);
    }
}
