<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Show the form for creating a new room
     */
    public function create($accommodationId)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($accommodationId);
        $amenities = Amenity::where('category', '!=', 'general')->orderBy('category')->orderBy('name')->get()->groupBy('category');
        $bedTypes = ['single', 'double', 'queen', 'king'];

        return view('manager.rooms.create', compact('accommodation', 'amenities', 'bedTypes'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request, $accommodationId)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($accommodationId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'integer', 'min:1'],
            'bed_type' => ['nullable', 'string'],
            'bed_count' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'total_rooms' => ['required', 'integer', 'min:1'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
        ]);

        // Handle image upload
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('rooms', 'public');
        }

        $validated['accommodation_id'] = $accommodation->id;
        $validated['is_active'] = true;

        $room = Room::create($validated);

        // Attach amenities
        if ($request->has('amenities')) {
            $room->amenities()->attach($request->amenities);
        }

        return redirect()
            ->route('manager.accommodations.show', $accommodation->id)
            ->with('success', '객실이 성공적으로 등록되었습니다!');
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit($accommodationId, $id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($accommodationId);
        $room = $accommodation->rooms()->findOrFail($id);
        $amenities = Amenity::where('category', '!=', 'general')->orderBy('category')->orderBy('name')->get()->groupBy('category');
        $bedTypes = ['single', 'double', 'queen', 'king'];

        return view('manager.rooms.edit', compact('accommodation', 'room', 'amenities', 'bedTypes'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, $accommodationId, $id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($accommodationId);
        $room = $accommodation->rooms()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'max_occupancy' => ['required', 'integer', 'min:1'],
            'size' => ['nullable', 'integer', 'min:1'],
            'bed_type' => ['nullable', 'string'],
            'bed_count' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'total_rooms' => ['required', 'integer', 'min:1'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
            'is_active' => ['boolean'],
        ]);

        // Handle image upload
        if ($request->hasFile('main_image')) {
            // Delete old image
            if ($room->main_image) {
                Storage::disk('public')->delete($room->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('rooms', 'public');
        }

        $room->update($validated);

        // Sync amenities
        if ($request->has('amenities')) {
            $room->amenities()->sync($request->amenities);
        } else {
            $room->amenities()->sync([]);
        }

        return redirect()
            ->route('manager.accommodations.show', $accommodation->id)
            ->with('success', '객실 정보가 수정되었습니다.');
    }

    /**
     * Remove the specified room
     */
    public function destroy($accommodationId, $id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($accommodationId);
        $room = $accommodation->rooms()->findOrFail($id);

        // Check if there are active bookings
        if ($room->bookings()->whereIn('status', ['pending', 'confirmed', 'checked_in'])->exists()) {
            return redirect()
                ->back()
                ->with('error', '활성화된 예약이 있어 삭제할 수 없습니다.');
        }

        // Delete image
        if ($room->main_image) {
            Storage::disk('public')->delete($room->main_image);
        }

        $room->delete();

        return redirect()
            ->route('manager.accommodations.show', $accommodation->id)
            ->with('success', '객실이 삭제되었습니다.');
    }
}
