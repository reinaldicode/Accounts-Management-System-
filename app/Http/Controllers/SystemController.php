<?php
// app/Http/Controllers/SystemController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\System;
use App\Models\AuditLog;
use App\Helpers\AMSHelper;

class SystemController extends Controller
{
    /**
     * Display system list
     */
    public function index()
    {
        $systems = System::orderBy('system_name')->get();

        return view('systems.index', compact('systems'));
    }

    /**
     * Show create system form
     */
    public function create()
    {
        return view('systems.create');
    }

    /**
     * Store new system
     */
    public function store(Request $request)
    {
        $request->validate([
            'system_code' => 'required|string|max:50|unique:systems,system_code',
            'system_name' => 'required|string|max:255',
            'system_url' => 'required|url|max:500',
            'description' => 'nullable|string',
        ]);

        $system = System::create([
            'system_code' => $request->system_code,
            'system_name' => $request->system_name,
            'system_url' => $request->system_url,
            'api_key' => AMSHelper::generateApiKey(),
            'description' => $request->description,
            'is_active' => 1,
        ]);

        AuditLog::log(
            session('ams_user_id'),
            $system->id,
            'system_created',
            'success',
            ['system_code' => $system->system_code]
        );

        return redirect()->route('systems.index')
            ->with('success', 'System berhasil ditambahkan');
    }

    /**
     * Show edit system form
     */
    public function edit($id)
    {
        $system = System::findOrFail($id);

        return view('systems.edit', compact('system'));
    }

    /**
     * Update system
     */
    public function update(Request $request, $id)
    {
        $system = System::findOrFail($id);

        $request->validate([
            'system_code' => 'required|string|max:50|unique:systems,system_code,' . $id,
            'system_name' => 'required|string|max:255',
            'system_url' => 'required|url|max:500',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $system->update([
            'system_code' => $request->system_code,
            'system_name' => $request->system_name,
            'system_url' => $request->system_url,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        AuditLog::log(
            session('ams_user_id'),
            $system->id,
            'system_updated',
            'success',
            ['system_code' => $system->system_code]
        );

        return redirect()->route('systems.index')
            ->with('success', 'System berhasil diupdate');
    }

    /**
     * Regenerate API key
     */
    public function regenerateApiKey($id)
    {
        $system = System::findOrFail($id);
        $oldKey = substr($system->api_key, 0, 10) . '...';

        $system->update([
            'api_key' => AMSHelper::generateApiKey()
        ]);

        AuditLog::log(
            session('ams_user_id'),
            $system->id,
            'api_key_regenerated',
            'success',
            ['system_code' => $system->system_code, 'old_key_preview' => $oldKey]
        );

        return back()->with('success', 'API Key berhasil di-regenerate');
    }
}