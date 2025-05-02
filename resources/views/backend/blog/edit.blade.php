<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.blog.update', ['blog' => $post->slug]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label d-block">Slug</label>
                    <button type="button" class="btn btn-outline-warning btn-sm mb-3" onclick="this.nextElementSibling.removeAttribute('disabled')">Edit Slug</button>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ $post->slug }}" disabled>
                </div>
                <div class="mb-3">
                    <label for="summary" class="form-label">Summary</label>
                    <textarea class="form-control" id="summary" name="summary">{{ $post->summary }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label d-block">Content</label>
                    <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="changeEditor(this)">Edit HTML</button>
                    <textarea class="form-control" id="content" name="content">{!! $post->content !!}</textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label d-block">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="1" @if ($post->published == true) selected @endif>Published</option>
                        <option value="0" @if ($post->published == false) selected @endif>Documentation</option>
                    </select>
                </div>
                @if ($thumbnail)
                    <div class="mb-3" style="background-size: cover;">
                        <label class="form-label d-block">Current Thumbnail</label>
                        <img src="{{ asset('storage/' . $thumbnail->path) }}" alt="" class="img-fluid" style="max-width: 300px">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/png, image/jpeg">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="featured" name="featured" @if ($post->featured) selected @endif>
                    <label class="form-check-label" for="featured">Featured News</label>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.blog.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>

    <x-slot:css>
        <style>
            .ck-editor__editable_inline, #content {
                min-height: 300px !important;
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
                        .catch( error => {
                            console.log( error );
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