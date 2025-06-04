<x-layouts.backend title="Website Assets (Banner & Logo)">
    <form action="{{ route('backend.option.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Banner Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Banner Settings</h5>
                    </div>
                    <div class="card-body">
                        {{-- Blog Banner --}}
                        @php
                            $blogBanner = $banners->firstWhere('name', 'blog');
                        @endphp
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Blog</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        @if($blogBanner && $blogBanner->thumbnail && $blogBanner->thumbnail->path)
                                            <img src="{{ asset('storage/' . $blogBanner->thumbnail->path) }}" class="img-fluid" alt="Blog Banner">
                                        @else
                                            <img src="https://via.placeholder.com/600x200?text=No+Banner" class="img-fluid" alt="No Banner">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="{{ $blogBanner ? $blogBanner->id : '' }}" data-multiple accept="image/*">
                                    @if($blogBanner)
                                        <input type="hidden" name="banner_id[]" value="{{ $blogBanner->id }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Brand Banner --}}
                        @php
                            $brandBanner = $banners->firstWhere('name', 'brand');
                        @endphp
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Brand</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        @if($brandBanner && $brandBanner->thumbnail && $brandBanner->thumbnail->path)
                                            <img src="{{ asset('storage/' . $brandBanner->thumbnail->path) }}" class="img-fluid" alt="Brand Banner">
                                        @else
                                            <img src="https://via.placeholder.com/600x200?text=No+Banner" class="img-fluid" alt="No Banner">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="{{ $brandBanner ? $brandBanner->id : '' }}" data-multiple accept="image/*">
                                    @if($brandBanner)
                                        <input type="hidden" name="banner_id[]" value="{{ $brandBanner->id }}">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Type Banner --}}
                        @php
                            $typeBanner = $banners->firstWhere('name', 'type');
                        @endphp
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Type</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        @if($typeBanner && $typeBanner->thumbnail && $typeBanner->thumbnail->path)
                                            <img src="{{ asset('storage/' . $typeBanner->thumbnail->path) }}" class="img-fluid" alt="Type Banner">
                                        @else
                                            <img src="https://via.placeholder.com/600x200?text=No+Banner" class="img-fluid" alt="No Banner">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="{{ $typeBanner ? $typeBanner->id : '' }}" data-multiple accept="image/*">
                                    @if($typeBanner)
                                        <input type="hidden" name="banner_id[]" value="{{ $typeBanner->id }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Logo Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Logo Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold text-muted">Current Logo</h6>
                                <div class="border rounded overflow-hidden">
                                    @if($logo && $logo->thumbnail && $logo->thumbnail->path)
                                        <img src="{{ asset('storage/' . $logo->thumbnail->path) }}" class="img-fluid" alt="Current Logo">
                                    @else
                                        <img src="https://via.placeholder.com/200x200?text=No+Logo" class="img-fluid" alt="No Logo">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold text-muted">Upload New Logo</h6>
                                <input type="file" class="form-control" name="logo" data-type="logo" data-id="{{ $logo ? $logo->id : '' }}" accept="image/*">
                                @if($logo)
                                    <input type="hidden" name="logo_id" value="{{ $logo->id }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
            <i class="fas fa-save me-1"></i> Submit
        </button>
    </form>

    <x-slot:js>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>
    </x-slot>
</x-layouts.backend>