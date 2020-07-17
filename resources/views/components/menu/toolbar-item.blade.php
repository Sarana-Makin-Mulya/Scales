<a href="{{ $href ?? '#' }}" class="toolbar-item {{ request()->is($active ?? '') ? 'active' : ''}}">
    <i class="nav-icon fas {{ $icon ?? 'fas fa-circle' }}"></i>
    <p>{{ $text }}</p>
</a>
