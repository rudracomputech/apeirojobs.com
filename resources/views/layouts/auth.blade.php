<!doctype html>
<html lang="en" data-theme="light">
  <head>
    <title>@yield('title') - Medistaff</title>
    <meta charset="UTF-8" />
   
    <meta name="keywords" content="Medistaff, healthcare, jobs" />
    <meta name="description" content="Medistaff is a platform for finding healthcare jobs." />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('images/favicon-dark.png') }}" media="(prefers-color-scheme: dark)" />
    <link rel="shortcut icon" href="{{ asset('images/favicon-light.png') }}" media="(prefers-color-scheme: light)" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @yield('page-css')
  

   @vite('resources/css/app.css')

  </head>
  <body>
    <div class="w-full  h-screen ">
     
      <div class="col-span-12 md:w-full  lg:col-span-12 xl:col-span-12 xl:flex 2xl:col-span-12 justify-center">
        <div class="flex md:w-full flex-col items-stretch  ">
          
          @yield('content')
        </div>
      </div>
    </div>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/simplebar/6.2.7/simplebar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplebar/6.2.7/simplebar.css" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
   @vite(['resources/js/app.js'])

<script src="https://code.iconify.design/iconify-icon/2.3.0/iconify-icon.min.js"></script>

    @yield('page-js')
  </body>
</html>