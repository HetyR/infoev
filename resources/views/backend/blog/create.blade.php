<x-layouts.backend title="Create New Blog Post">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Add New Blog
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.blog.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary</label>
                            <textarea class="form-control" id="summary" name="summary"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label d-block">Content</label>
                            <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="changeEditor(this)">Edit HTML</button>
                            <textarea class="form-control" id="content" name="content"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label d-block">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="1" selected>Published</option>
                                <option value="0">Documentation</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="featured" name="featured">
                            <label class="form-check-label" for="featured">Featured News</label>
                        </div>

                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Submit
                            </button>
                            <a href="{{ route('backend.blog.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot:css>
        <style>
            .ck-editor__editable_inline, #content {
                min-height: 300px !important;
            }

            .custom-hover:hover {
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1), 0px 1px 3px rgba(0, 0, 0, 0.08);
            }
        </style>
    </x-slot>

    <x-slot:js>
        <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
        <script>
            let editorState = true;
            let editor;
            ClassicEditor
                .create(document.querySelector('#content'))
                .then(ed => {
                    editor = ed;
                })
                .catch(error => {});

            function changeEditor(btn) {
                if (editorState) {
                    editor.destroy()
                        .catch(error => {
                            console.log(error);
                        });
                    editorState = false;
                    btn.innerText = 'Switch Editor';
                } else {
                    ClassicEditor
                        .create(document.querySelector('#content'))
                        .then(ed => {
                            editor = ed;
                        })
                        .catch(error => {});
                    editorState = true;
                    btn.innerText = 'Edit HTML';
                }
            }
        </script>
    </x-slot>
</x-layouts.backend>
