<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body
    class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs  text-sm"
    data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">
        @include('layouts.navigation')
        @include('layouts.navbar')
        @if (session('success'))
            <script>
                alert_toast("{{ session('success') }}", 'success');
            </script>
        @endif
        <div class="content-wrapper pt-3 pb-4" style="min-height: 567.854px;">
            <section class="content text-dark">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>


            @include('components.modals')
        </div>

        @include('layouts.footer')
    </div>
</body>

</html>