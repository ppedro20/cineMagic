<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Http\Requests\ConfigurationFormRequest;
use Illuminate\Http\RedirectResponse;

class ConfigurationController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $configuration = Configuration::first();
        return view('configurations.edit',compact('configuration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConfigurationFormRequest $request): RedirectResponse
    {
        $configuration = Configuration::first();
        $configuration->update($request->validated());

        $htmlMessage = "Configuration has been updated successfully!";
        return redirect()->route('configurations.edit')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }
}
