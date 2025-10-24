<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the manager's accommodations
     */
    public function index()
    {
        $accommodations = Auth::user()->accommodations()
            ->withCount(['rooms', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('manager.accommodations.index', compact('accommodations'));
    }

    /**
     * Show the form for creating a new accommodation
     */
    public function create()
    {
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $categories = ['hotel', 'motel', 'resort', 'guesthouse', 'pension'];

        return view('manager.accommodations.create', compact('amenities', 'categories'));
    }

    /**
     * Store a newly created accommodation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string', 'min:50'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
        ], [
            'name.required' => '숙소명을 입력해주세요.',
            'category.required' => '카테고리를 선택해주세요.',
            'description.required' => '숙소 설명을 입력해주세요.',
            'description.min' => '설명은 최소 50자 이상 입력해주세요.',
            'address.required' => '주소를 입력해주세요.',
            'city.required' => '도시를 입력해주세요.',
            'phone.required' => '연락처를 입력해주세요.',
            'main_image.image' => '올바른 이미지 파일을 선택해주세요.',
            'main_image.max' => '이미지 크기는 2MB를 초과할 수 없습니다.',
        ]);

        // Handle image upload
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('accommodations', 'public');
        }

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        $accommodation = Accommodation::create($validated);

        // Attach amenities
        if ($request->has('amenities')) {
            $accommodation->amenities()->attach($request->amenities);
        }

        return redirect()
            ->route('manager.accommodations.show', $accommodation->id)
            ->with('success', '숙소가 성공적으로 등록되었습니다!');
    }

    /**
     * Display the specified accommodation
     */
    public function show($id)
    {
        $accommodation = Auth::user()->accommodations()
            ->with(['rooms', 'amenities', 'bookings' => function($query) {
                $query->latest()->limit(10);
            }])
            ->findOrFail($id);

        return view('manager.accommodations.show', compact('accommodation'));
    }

    /**
     * Show the form for editing the specified accommodation
     */
    public function edit($id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($id);
        $amenities = Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $categories = ['hotel', 'motel', 'resort', 'guesthouse', 'pension'];

        return view('manager.accommodations.edit', compact('accommodation', 'amenities', 'categories'));
    }

    /**
     * Update the specified accommodation
     */
    public function update(Request $request, $id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'description' => ['required', 'string', 'min:50'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'main_image' => ['nullable', 'image', 'max:2048'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
            'is_active' => ['boolean'],
        ]);

        // Handle image upload
        if ($request->hasFile('main_image')) {
            // Delete old image
            if ($accommodation->main_image) {
                Storage::disk('public')->delete($accommodation->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('accommodations', 'public');
        }

        $accommodation->update($validated);

        // Sync amenities
        if ($request->has('amenities')) {
            $accommodation->amenities()->sync($request->amenities);
        } else {
            $accommodation->amenities()->sync([]);
        }

        return redirect()
            ->route('manager.accommodations.show', $accommodation->id)
            ->with('success', '숙소 정보가 수정되었습니다.');
    }

    /**
     * Remove the specified accommodation
     */
    public function destroy($id)
    {
        $accommodation = Auth::user()->accommodations()->findOrFail($id);

        // Check if there are active bookings
        if ($accommodation->bookings()->whereIn('status', ['pending', 'confirmed', 'checked_in'])->exists()) {
            return redirect()
                ->back()
                ->with('error', '활성화된 예약이 있어 삭제할 수 없습니다.');
        }

        // Delete image
        if ($accommodation->main_image) {
            Storage::disk('public')->delete($accommodation->main_image);
        }

        $accommodation->delete();

        return redirect()
            ->route('manager.accommodations.index')
            ->with('success', '숙소가 삭제되었습니다.');
    }
}
