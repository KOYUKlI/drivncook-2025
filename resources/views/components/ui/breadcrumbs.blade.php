@props(['items' => []])

@if(count($items) > 0)
<nav class="flex mb-6" aria-label="{{ __('ui.misc.breadcrumb') }}">
    <ol class="flex items-center space-x-2">
        @foreach($items as $index => $item)
            <li class="flex items-center">
                @if($index > 0)
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-sm text-gray-600 hover:text-gray-900">
                        {{ $item['title'] }}
                    </a>
                @else
                    <span class="text-sm {{ $loop->last ? 'text-gray-900 font-medium' : 'text-gray-600' }}">
                        {{ $item['title'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
