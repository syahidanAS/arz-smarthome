<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function getData()
    {
        try {
            $user_id = auth()->id(); // Assuming you have authentication set up
            $automations = Automation::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 'success',
                'data' => $automations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggle(Request $request)
    {

        $request->validate([
            'id' => 'required|integer|exists:automations,id',
            'enabled' => 'required|boolean'
        ]);

        try {

            $automation = Automation::findOrFail($request->id);

            // (optional) cek user ownership
            if ($automation->user_id !== auth()->id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $automation->enabled = $request->enabled;
            $automation->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Automation updated',
                'data' => [
                    'id' => $automation->id,
                    'enabled' => $automation->enabled
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update automation'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:automations,id',
            'time' => 'required',
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        $automation = Automation::findOrFail($request->id);

        if ($automation->user_id !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $automation->update([
            'time' => $request->time,
            'topic' => $request->topic,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'required',
            'topic' => 'required|string',
            'message' => 'required|boolean:true,false',
            'description' => 'nullable|string'
        ]);

        $automation = Automation::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'time' => $request->time,
            'topic' => $request->topic,
            'message' => $request->message,
            'description' => $request->description,
            'enabled' => 1
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $automation
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:automations,id'
        ]);

        $ids = $request->ids;

        // ambil data milik user saja (security)
        $automations = Automation::whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->get();

        foreach ($automations as $auto) {
            $auto->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully'
        ]);
    }
}
