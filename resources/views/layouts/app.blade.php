<x-layouts.base>
    @php
        $routeName = request()->route()->getName();
    @endphp

    @if(in_array($routeName, [
        'dashboard', 'profile', 'profile-example', 'users', 'bootstrap-tables', 'transactions',
        'buttons','forms', 'modals', 'notifications', 'typography', 'upgrade-to-pro',
        'stock.index','transaction-create.', 'transaction.in', 'transaction.out',
        'stocktaking.gdtp','stocktaking.production','transaction.return',
        'stocktaking.create','stocktaking.detail'
    ]))
        {{-- Nav --}}
        @include('layouts.nav')
        {{-- SideNav --}}
        @include('layouts.sidenav')

        <main class="content">
            {{-- TopBar --}}
            @include('layouts.topbar')

            {{ $slot }}

            {{-- Footer --}}
            @include('layouts.footer')
        </main>

    @elseif(in_array($routeName, [
        'register', 'register-example', 'login', 'login-example',
        'forgot-password', 'forgot-password-example',
        'reset-password','reset-password-example'
    ]))
        {{ $slot }}
        {{-- Footer --}}
        @include('layouts.footer2')

    @elseif(in_array($routeName, ['404', '500', 'lock']))
        {{ $slot }}
    @endif

    {{-- Vite & Livewire --}}
    @livewireStyles
    @livewireScripts
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}"></script>


</x-layouts.base>
