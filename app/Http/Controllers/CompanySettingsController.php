<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingsController extends Controller
{
    public function edit()
    {
        $setting = AppSetting::current();

        return view('configuracion.empresa', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = AppSetting::current();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'company_name.required' => 'El nombre de la empresa es obligatorio.',
            'company_logo.image' => 'El logo debe ser una imagen valida.',
            'company_logo.mimes' => 'El logo debe ser JPG, PNG o WEBP.',
            'company_logo.max' => 'El logo no puede exceder 2 MB.',
        ]);

        $updates = [
            'company_name' => $validated['company_name'],
        ];

        if ($request->hasFile('company_logo')) {
            if ($setting->company_logo_path) {
                Storage::disk('public')->delete($setting->company_logo_path);
            }

            $updates['company_logo_path'] = $request->file('company_logo')->store('empresa', 'public');
        }

        $setting->update($updates);

        return redirect()
            ->route('configuracion.empresa.edit')
            ->with('success', 'Configuracion de empresa actualizada correctamente.');
    }
}
