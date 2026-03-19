@props ([
    'tittle' => config('app.name','Laravel'),
    'breadcrumbs' => [] //array de breadcrumbs con formato: [
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $tittle }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{--Sweetalert--}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{--WireUI--}}
        <wireui:scripts />


        <!-- Styles -->
        @livewireStyles

        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        
    </head>
    <body class="font-sans antialiased bg-gray-70">
        
      @include('layouts.includes.admin.navigation')

      @include('layouts.includes.admin.sidebar')



      <div class="p-4 sm:ml-64 mt-14">
         <div class="mt-14 flex justify-between items-center w-full">
            @include('layouts.includes.admin.breadcrumb')
            @isset($action)
                <div>
                    {{ $action }}
                </div>
                
            @endisset
         </div>
         {{ $slot }}
      </div>

      @stack('modals')

      {{--mostrar el sweetalert--}}
    @if (session('swal'))
        <script>
            Swal.fire(@json(session('swal')));
        </script>
    @endif

      @livewireScripts

      <script>
        // Confirm delete using SweetAlert2 for any form marked with data-swal-confirm
        document.addEventListener('submit', function (event) {
          const form = event.target.closest('form[data-swal-confirm]');
          if (!form) {
            return;
          }

          event.preventDefault();

          Swal.fire({
            title: '¿Estás seguro?',
            text: 'No podrás revertir esto',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      </script>

      <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
      <script src="https://kit.fontawesome.com/2081fc20de.js" crossorigin="anonymous"></script>

    </body>
</html>
