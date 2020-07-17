<aside class="main-sidebar sidebar-dark-secondary elevation-0 pt-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link logo-switch">
        <img
            src="{{ asset('images/logo-smm-no-text-mini.png') }}"
            alt="SMM"
            class="brand-image-xl logo-xs"
        >
        <img
            src="{{ asset('images/logo-smm-no-text-large.png') }}"
            alt="SMM"
            class="brand-image-xs logo-xl"
            style="left: 12px;opacity: .8"
        >
    </a>

    <div class="sidebar">
        @include('partials._sidebar-menu')
    </div>
</aside>
