@php($settings = auth()->check() ? (auth()->user()->backend_settings ?? []) : [])
<!doctype html>
<html lang="en" data-theme="light">

<head>
  <title>@yield('title') - HSCouching</title>
  <meta charset="UTF-8" />

  <meta name="keywords" content="HSCouching, healthcare, jobs" />
  <meta name="description" content="HSCouching is a platform for finding healthcare jobs." />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="{{ asset('images/favicon-dark.png') }}" media="(prefers-color-scheme: dark)" />
  <link rel="shortcut icon" href="{{ asset('images/favicon-light.png') }}" media="(prefers-color-scheme: light)" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">



  @vite('resources/css/backend.css')

  @yield('page-css')



</head>

<body style="color: {{ $settings['body_text_color'] ?? '#334155' }}; background-color: {{ $settings['mainbar_background_color'] ?? '#f8fafc' }};">
  <main data-simplebar  class="h-screen ">
    <div class="flex items-start h-full">


      @include('layouts.backend.sidebar')

      <div class="w-full  overflow-x-hidden">
        @include('layouts.backend.header')

        <div class="m-6 h-full ">
          <div class="space-y-6 px-4  my-6">
            @include('layouts.alerts')
          </div>
          @yield('content')

        </div>
     

      </div>

    </div>

  </main>
  
  

  @vite(['resources/js/backend.js'])

  

  
  @yield('page-js')
</body>

</html>