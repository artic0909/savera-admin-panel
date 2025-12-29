<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoryVideo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoryVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = StoryVideo::withCount('products')->latest()->paginate(10);
        return view('admin.story_video.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('product_name')->get();
        return view('admin.story_video.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'video' => 'required|mimes:mp4,mov,ogg,qt|max:20480', // 20MB max
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('story_videos', 'public');
        }

        $storyVideo = StoryVideo::create($data);
        $storyVideo->products()->sync($request->product_ids);

        return redirect()->route('admin.story-videos.index')->with('success', 'Story Video uploaded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video = StoryVideo::with('products')->findOrFail($id);
        $products = Product::orderBy('product_name')->get();
        return view('admin.story_video.edit', compact('video', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $video = StoryVideo::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('video')) {
            // Delete old video
            if ($video->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
            $data['video_path'] = $request->file('video')->store('story_videos', 'public');
        }

        $video->update($data);
        $video->products()->sync($request->product_ids);

        return redirect()->route('admin.story-videos.index')->with('success', 'Story Video updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = StoryVideo::findOrFail($id);

        // Delete video file
        if ($video->video_path) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->delete();

        return redirect()->route('admin.story-videos.index')->with('success', 'Story Video deleted successfully.');
    }
}
