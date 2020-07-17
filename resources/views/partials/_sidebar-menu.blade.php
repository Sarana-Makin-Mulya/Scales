<nav class="mt-0">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        @include('components.menu.nav-item', [
            'text' =>  __('Penimbangan'),
            'href' => route('home'),
            'active' => '',
            'icon' => 'fas fa-balance-scale',
        ])

    </ul>
</nav>
