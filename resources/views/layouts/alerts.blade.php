@if($errors->any())
<!-- Error -->
<div
  class="bg-red-50 text-sm p-3 rounded-md flex flex-col gap-3 border border-red-100 sm:items-center sm:flex-row dark:bg-red-900/20 dark:border-red-800/40"
  role="alert">
  <div class="flex items-center gap-2.5 text-red-900 font-medium dark:text-red-300">
    <x-lucide-circle-x class="size-[18px]  overflow-visible" />
    <p>Error!</p>
  </div>
  @foreach ($errors->all() as $error)
  <li>{{ $error }}</li>

  @endforeach
</div>

<!-- error -->
<div
  class="bg-red-50 text-sm p-3 rounded-md flex items-start gap-3 border border-red-100 dark:bg-red-900/20 dark:border-red-800/40"
  role="alert">
  <div class="flex items-start gap-2.5 text-red-900 dark:text-red-300">
    <x-lucide-circle-x class="size-[18px]  overflow-visible" />
    <div>
      <p class="font-medium leading-tight">Error!</p>
      <ul class="list-disc pl-4 mt-2 text-red-900 dark:text-red-400 space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>

        @endforeach
      </ul>
    </div>
  </div>
  <button type="button" aria-label="Dismiss error alert"
    class="dismiss-btn ml-auto flex items-center opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
    <x-lucide-x class="size-[18px]  overflow-visible" />
  </button>
</div>


@endif
@if (session('status') === 'success')
<!-- success -->
<div
  class="bg-green-50 text-sm p-3 rounded-md flex items-start gap-3 border border-green-100 dark:bg-green-900/20 dark:border-green-800/40"
  role="alert">
  <div class="flex items-start gap-2.5 text-green-900 dark:text-green-300">
    <x-lucide-circle-check-big class="size-[18px]  overflow-visible" />
    <div>
      <p class="font-medium leading-tight">Success!</p>
      <ul class="list-disc pl-4 mt-2 text-green-900 dark:text-green-400 space-y-1">
        <li>{{ session('message') }}</li>
      </ul>
    </div>
  </div>
  <button type="button" aria-label="Dismiss success alert"
    class="dismiss-btn ml-auto flex items-center opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
    <x-lucide-x class="size-[18px]  overflow-visible" />
  </button>
</div>


@endif
@if (session('status') === 'warning')
<!-- warning -->
<div
  class="bg-yellow-50 text-sm p-3 rounded-md flex items-start gap-3 border border-yellow-100 dark:bg-yellow-900/20 dark:border-yellow-800/40"
  role="alert">
  <div class="flex items-start gap-2.5 text-yellow-900 dark:text-yellow-300">
    <x-lucide-alert-triangle class="size-[18px]  overflow-visible" />
    <div>
      <p class="font-medium leading-tight">Warning!</p>
      <ul class="list-disc pl-4 mt-2 text-yellow-900 dark:text-yellow-400 space-y-1">
        <li>{{ session('message') }}</li>
      </ul>
    </div>
  </div>
  <button type="button" aria-label="Dismiss warning alert"
    class="dismiss-btn ml-auto flex items-center opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
    <x-lucide-x class="size-[18px]  overflow-visible" />
  </button>
</div>
@endif
@if (session('status') === 'info')
<!-- info -->
<div
  class="bg-blue-50 text-sm p-3 rounded-md flex items-start gap-3 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800/40"
  role="alert">
  <div class="flex items-start gap-2.5 text-blue-900 dark:text-blue-300">
    <x-lucide-info class="size-[18px]  overflow-visible" />
    <div>
      <p class="font-medium leading-tight">Info!</p>
      <ul class="list-disc pl-4 mt-2 text-blue-900 dark:text-blue-400 space-y-1">
        <li>{{ session('message') }}</li>
      </ul>
    </div>
  </div>
  <button type="button" aria-label="Dismiss info alert"
    class="dismiss-btn ml-auto flex items-center opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
    <x-lucide-x class="size-[18px]  overflow-visible" />
  </button>
</div>
@endif
@if (session('status') === 'fail')
<!-- error -->
<div
  class="bg-red-50 text-sm p-3 rounded-md flex items-start gap-3 border border-red-100 dark:bg-red-900/20 dark:border-red-800/40"
  role="alert">
  <div class="flex items-start gap-2.5 text-red-900 dark:text-red-300">
    <x-lucide-circle-x class="size-[18px]  overflow-visible" />
    <div>
      <p class="font-medium leading-tight">Error!</p>
      <ul class="list-disc pl-4 mt-2 text-red-900 dark:text-red-400 space-y-1">
        <li>{{ session('message') }}</li>
      </ul>
    </div>
  </div>
  <button type="button" aria-label="Dismiss error alert"
    class="dismiss-btn ml-auto flex items-center opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
    <x-lucide-x class="size-[18px]  overflow-visible" />
  </button>
</div>
@endif
