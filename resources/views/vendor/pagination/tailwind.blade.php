@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-3">

        {{-- Mobile Controls --}}
        <div class="flex gap-2 items-center justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-[#588157] transition shadow-xs">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            <span class="text-xs text-slate-500 font-medium">
                Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-[#588157] transition shadow-xs">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Controls --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    Menampilkan <span class="font-bold text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-700">{{ $paginator->total() }}</span> hasil
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg text-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 text-slate-700 bg-white border border-slate-200 rounded-lg text-xs hover:bg-slate-50 hover:text-[#588157] hover:border-slate-300 transition shadow-xs" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold text-slate-400 bg-transparent">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-[#588157] border border-[#588157] rounded-lg shadow-xs">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:text-[#588157] hover:border-slate-300 transition shadow-xs" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 text-slate-700 bg-white border border-slate-200 rounded-lg text-xs hover:bg-slate-50 hover:text-[#588157] hover:border-slate-300 transition shadow-xs" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-slate-50 border border-slate-200 cursor-not-allowed rounded-lg text-xs" aria-hidden="true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
