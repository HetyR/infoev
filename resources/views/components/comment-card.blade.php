@props(['comment'])
@php
    if ($comment->hide_name) {
        $name = '[Nama melanggar ketentuan]';
    } else {
        $name = $comment->name ?: 'Anonim';
    }
@endphp

<div class="mb-6" data-comment-container>
    <article class="p-4 bg-gray-50 border border-gray-100" data-comment-id="{{ $comment->id }}">
        <header class="flex justify-between items-center mb-2">
            <div class="flex items-center">
                <img class="mr-4 w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(preg_replace('/[\[\]]/', '', $name)) }}" alt="{{ $name }}">
                <div class="inline-flex flex-col">
                    <p class="text-base text-gray-900 font-semibold">
                        {{ $name }}
                    </p>
                    <p class="text-xs text-gray-600">
                        <time pubdate datetime="{{ $comment->created_at->toDateString() }}" title="{{ $comment->created_at->format('d F Y') }}">
                            {{ $comment->created_at->format('d M Y') }}
                        </time>
                    </p>
                </div>
            </div>
        </header>

        <p class="py-1.5 text-gray-500">
            @if ($comment->hide_comment)
                <span class="italic text-sm">Komentar dihapus oleh admin karena dianggap tidak pantas atau spam</span>
            @else
                {{ $comment->comment }}
            @endif
        </p>
        <div class="flex items-center mt-4 space-x-4">
            <button type="button" onclick="reply(this)"
                class="flex items-center text-sm text-gray-500 hover:underline font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1.5 w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                Balas
            </button>
        </div>
    </article>

    @foreach ($comment->replies as $reply)
        @php
            $replyName = $reply->name ?: 'Anonim';
        @endphp
        <article class="mt-6 ml-6 p-4 bg-gray-50 border border-gray-100 lg:ml-12">
            <header class="flex justify-between items-center mb-2">
                <div class="flex items-center">
                    <img class="mr-4 w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($replyName) }}" alt="{{ $replyName }}">
                    <div class="inline-flex flex-col">
                        <p class="text-base text-gray-900 font-semibold">
                            {{ $replyName }}
                        </p>
                        <p class="text-xs text-gray-600">
                            <time pubdate datetime="{{ $reply->created_at->toDateString() }}" title="{{ $reply->created_at->format('d F Y') }}">
                                {{ $reply->created_at->format('d M Y') }}
                            </time>
                        </p>
                    </div>
                </div>
            </header>
            <p class="py-1.5 text-gray-500">{{ $reply->comment }}</p>
            <div class="flex items-center mt-4 space-x-4">
                <button type="button" onclick="reply(this)"
                    class="flex items-center text-sm text-gray-500 hover:underline font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1.5 w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    Balas
                </button>
            </div>
        </article>
    @endforeach
</div>