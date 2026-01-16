@extends('admin.layouts.app')

@section('title', 'Story Videos')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="d-flex align-items-center justify-content-between px-4 py-3">
                <h5 class="card-title text-primary mb-0">Story Videos</h5>
                <a href="{{ route('admin.story-videos.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Add Story Video
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Video</th>
                                <th>Title</th>
                                <th>Linked Products</th>
                                <th>Status</th>
                                <th>Always Visible</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($videos as $video)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <video width="100" height="150" style="object-fit: cover; border-radius: 8px;">
                                            <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </td>
                                    <td>{{ $video->title ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $video->products_count }} Products</span>
                                    </td>
                                    <td>
                                        @if ($video->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($video->always_visible)
                                            <span class="badge bg-primary">1</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.story-videos.edit', $video->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.story-videos.destroy', $video->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this story video?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No story videos found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $videos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
