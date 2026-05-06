@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">

        <ul class="inline-flex items-center space-x-1">

            {{-- First Page --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 bg-gray-200/40 text-gray-400 border border-white/30
                            dark:bg-white/5 dark:text-gray-500 dark:border-white/10
                            backdrop-blur-md cursor-not-allowed rounded">
                            «
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->url(1) }}"
                       class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                        «
                    </a>
                </li>
            @endif

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-2 bg-gray-200/40 text-gray-400 border border-white/30
                            dark:bg-white/5 dark:text-gray-500 dark:border-white/10
                            backdrop-blur-md cursor-not-allowed rounded">
                            ‹
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                        ‹
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp

            {{-- First page --}}
            @if ($start > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">1</a>
                </li>

                @if ($start > 2)
                    <li><span class="px-3 py-2 text-gray-400">...</span></li>
                @endif
            @endif

            {{-- Page range --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <li>
                        <span class="relative px-3 py-2 rounded-lg font-bold transform scale-105
                            bg-blue-500/80 text-white border border-blue-300/40
                            backdrop-blur-md shadow-lg transition
                            animate-pulse-slow">

                            {{ $i }}

                            <!-- Glow ring -->
                            <span class="absolute inset-0 rounded-lg bg-blue-400/20 blur-md animate-ping opacity-40"></span>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($i) }}" 
                        class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                            {{ $i }}
                        </a>
                    </li>
                @endif
            @endfor

            {{-- Last page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li><span class="px-3 py-2 text-gray-400">...</span></li>
                @endif

                <li>
                    <a href="{{ $paginator->url($last) }}" 
                    class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                        hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                        dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                        dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                        backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                        {{ $last }}
                    </a>
                </li>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                        ›
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 bg-gray-200/40 text-gray-400 border border-white/30
                            dark:bg-white/5 dark:text-gray-500 dark:border-white/10
                            backdrop-blur-md cursor-not-allowed rounded">
                            ›
                    </span>
                </li>
            @endif

            {{-- Last Page --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->url($paginator->lastPage()) }}"
                       class="flight-data-page px-3 py-2 bg-white/30 text-gray-700 border border-white/40
                            hover:bg-blue-100/70 hover:text-blue-700 hover:border-blue-300 hover:shadow-md hover:shadow-blue-400/20
                            dark:bg-white/10 dark:text-gray-200 dark:border-white/10
                            dark:hover:bg-blue-500/30 dark:hover:text-white dark:hover:border-blue-400/40
                            backdrop-blur-md shadow-sm transition transform hover:scale-105 rounded">
                        »
                    </a>
                </li>
            @else
                <li>
                    <span class="px-3 py-2 bg-gray-200/40 text-gray-400 border border-white/30
                            dark:bg-white/5 dark:text-gray-500 dark:border-white/10
                            backdrop-blur-md cursor-not-allowed rounded">
                            »
                    </span>
                </li>
            @endif

        </ul>

    </nav>
@endif