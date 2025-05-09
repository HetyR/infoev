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
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Blog</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        <img src="https://infoev.id/storage/banner/zprW0yYoi3XL3KbUKcBtm3JpcFIKI52NV9pqAnyh.jpg" class="img-fluid" alt="Blog Banner">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="4" data-multiple accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- Brand Banner --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Brand</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        <img src="https://infoev.id/storage/banner/K9EPWJb92H5AW7ZqqQsWkmQRnNSvgs8vknQtSuE7.png" class="img-fluid" alt="Brand Banner">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="3" data-multiple accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- Type Banner --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Type</h6>
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2">
                                    <h6>Current Banner</h6>
                                    <div class="border rounded overflow-hidden">
                                        <img src="https://infoev.id/storage/banner/Dv3KTQbw3JABjjgsl56MBYBofXn2F9kYfBYgWQOf.jpg" class="img-fluid" alt="Type Banner">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <h6>Upload New Banner</h6>
                                    <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="2" data-multiple accept="image/*">
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
                                    <img src="https://infoev.id/storage/assets/uqXtvwpd1ZEowh1iZrHNoRQj8t0ZAdeBg7V2j04v.png" class="img-fluid" alt="Current Logo">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold text-muted">Upload New Logo</h6>
                                <input type="file" class="form-control" name="logo" data-type="logo" data-id="1" accept="image/*">
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
    <script>
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => input.addEventListener('change', inputChange));

            function inputChange(e) {
                const target = e.target;

                if (target.nextElementSibling == null) {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', `${target.dataset.type}_id${target.dataset.multiple != undefined ? '[]' : ''}`);
                    input.value = target.dataset.id;
                    target.after(input);
                }
            }
        </script>
    </x-slot>
</x-layouts.backend>
