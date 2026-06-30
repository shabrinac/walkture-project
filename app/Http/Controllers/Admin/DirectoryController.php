<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotographerDirectory;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function index()
    {
        $photographers = PhotographerDirectory::orderByDesc('is_active')
                                              ->orderBy('full_name')
                                              ->get();

        $activeCount   = $photographers->where('is_active', true)->count();
        $inactiveCount = $photographers->where('is_active', false)->count();

        return view('admin.directory', compact('photographers', 'activeCount', 'inactiveCount'));
    }

    /** Show full details for a single photographer */
    public function show($id)
    {
        $photographer = PhotographerDirectory::findOrFail($id);
        return view('admin.directory.show', compact('photographer'));
    }

    /** Show the Add Photographer form */
    public function create()
    {
        return view('admin.directory.create');
    }

    /** Persist a new photographer */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'specialty'      => 'required|string|max:255',
            'avatar_url'     => 'nullable|url|max:2048',
            'portfolio_url'  => 'nullable|url|max:2048',
            'whatsapp_link'  => 'nullable|url|max:2048',
            'instagram_link' => 'nullable|url|max:2048',
            'is_active'      => 'boolean',
            'paid_until'     => 'nullable|date',
            // Pricing packages — each key is nullable
            'packages'       => 'nullable|array',
            'packages.basic.price'       => 'nullable|string|max:100',
            'packages.basic.description' => 'nullable|string|max:500',
            'packages.standard.price'    => 'nullable|string|max:100',
            'packages.standard.description' => 'nullable|string|max:500',
            'packages.premium.price'     => 'nullable|string|max:100',
            'packages.premium.description'  => 'nullable|string|max:500',
            'packages.fullday.price'     => 'nullable|string|max:100',
            'packages.fullday.description'  => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // Build pricing_packages JSON from packages input
        $validated['pricing_packages'] = $this->buildPricingPackages($request->input('packages', []));
        unset($validated['packages']);

        PhotographerDirectory::create($validated);

        return redirect()->route('admin.directory')
                         ->with('success', $validated['full_name'] . ' added to the directory.');
    }

    /** Update an existing photographer */
    public function update(Request $request, $id)
    {
        $photographer = PhotographerDirectory::findOrFail($id);

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'specialty'      => 'required|string|max:255',
            'avatar_url'     => 'nullable|url|max:2048',
            'portfolio_url'  => 'nullable|url|max:2048',
            'whatsapp_link'  => 'nullable|url|max:2048',
            'instagram_link' => 'nullable|url|max:2048',
            'is_active'      => 'boolean',
            'paid_until'     => 'nullable|date',
            'packages'       => 'nullable|array',
            'packages.basic.price'          => 'nullable|string|max:100',
            'packages.basic.description'    => 'nullable|string|max:500',
            'packages.standard.price'       => 'nullable|string|max:100',
            'packages.standard.description' => 'nullable|string|max:500',
            'packages.premium.price'        => 'nullable|string|max:100',
            'packages.premium.description'  => 'nullable|string|max:500',
            'packages.fullday.price'        => 'nullable|string|max:100',
            'packages.fullday.description'  => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        // Build pricing_packages JSON
        $validated['pricing_packages'] = $this->buildPricingPackages($request->input('packages', []));
        unset($validated['packages']);

        $photographer->update($validated);

        return redirect()->route('admin.directory')
                         ->with('success', $photographer->full_name . ' updated successfully.');
    }

    /** Remove a photographer from the directory */
    public function destroy($id)
    {
        $photographer = PhotographerDirectory::findOrFail($id);
        $name = $photographer->full_name;
        $photographer->delete();

        return redirect()->route('admin.directory')
                         ->with('success', $name . ' removed from the directory.');
    }

    /**
     * Build structured pricing_packages array from form input.
     */
    private function buildPricingPackages(array $packages): ?array
    {
        $tiers = ['basic', 'standard', 'premium', 'fullday'];
        $labels = [
            'basic'    => 'Basic',
            'standard' => 'Standard',
            'premium'  => 'Premium',
            'fullday'  => 'Full Day Event',
        ];

        $result = [];
        foreach ($tiers as $tier) {
            $price = $packages[$tier]['price'] ?? null;
            $desc  = $packages[$tier]['description'] ?? null;

            if ($price || $desc) {
                $result[$tier] = [
                    'label'       => $labels[$tier],
                    'price'       => $price ?: 'Hubungi kami',
                    'description' => $desc  ?: '',
                ];
            }
        }

        return !empty($result) ? $result : null;
    }
}
