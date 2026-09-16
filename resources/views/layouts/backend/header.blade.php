<!-- header -->
<header
   class="flex py-2 sticky top-0 w-full border-b border-slate-300 px-6 dark:border-neutral-700 dark:bg-neutral-900 min-h-[68px] z-20"
   style="background-color: {{ $settings['mainbar_background_color'] ?? '#f8fafc' }}; color: {{ $settings['body_text_color'] ?? '#334155' }};"
   aria-label="header">
   <div class="flex flex-wrap items-center gap-4 w-full">
      <!-- Sidebar Toggle btn -->
      <button type="button" aria-controls="collapseSidebar" aria-expanded="true" aria-haspopup="true"
         id="toggleSidebar"
         class="cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
         <span class="sr-only">Toggle sidebar menu</span>
         <svg xmlns="http://www.w3.org/2000/svg" class="size-[18px] fill-slate-900 dark:fill-slate-50"
            viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd"
               d="M.13 17.05a1.41 1.41 0 0 1 1.41-1.41H10a1.41 1.41 0 1 1 0 2.82H1.54a1.41 1.41 0 0 1-1.41-1.41zm0-14.1a1.41 1.41 0 0 1 1.41-1.41h16.92a1.41 1.41 0 1 1 0 2.82H1.54A1.41 1.41 0 0 1 .13 2.95zm0 7.05a1.41 1.41 0 0 1 1.41-1.41h16.92a1.41 1.41 0 1 1 0 2.82H1.54A1.41 1.41 0 0 1 .13 10z"
               clip-rule="evenodd" data-original="#000000" />
         </svg>
      </button>

      <h1 class="text-xl text-slate-900 font-bold dark:text-slate-50">@yield('title')</h1>

      <div class="flex items-center flex-wrap gap-4 ml-auto">

         <!-- Notification -->
         <div class="relative w-max flex flex-col">

            <div class="relative">
               <button id="notificationToggle" type="button" aria-label="Unread notifications" class="relative"
                  aria-haspopup="true" aria-expanded="false"
                  aria-controls="dropdown-menu">
                  <x-lucide-bell class="size-[24px]  overflow-visible" />
                  <span
                     class="bg-red-500 text-[10px] px-1 font-semibold min-w-[16px] h-4 flex items-center justify-center text-white rounded-full absolute -top-2 left-[60%]">{{ $notificationUnreadCount ?? 0 }}</span>
               </button>
            </div>
            <!-- Dropdown Menu -->
            <div id="dropdown-notification" class="min-w-md mx-auto hidden absolute right-0 top-full mt-2 px-4 py-3 border-b border-slate-300  justify-between items-center dark:border-neutral-700">
               <div aria-labelledby="notification-title"
                  class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-slate-300 dark:border-neutral-700">

                  <div class="px-4 py-3 border-b border-slate-300 flex justify-between items-center dark:border-neutral-700">
                     <h3 id="notification-title" class="text-base font-semibold text-slate-900 dark:text-slate-50">Notifications</h3>
                     <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button type="submit"
                           class="text-xs font-medium text-blue-600 cursor-pointer hover:underline dark:text-blue-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">Mark all as read</button>
                     </form>
                  </div>

                  <ul class="divide-y divide-slate-300 dark:divide-neutral-700">
                     @forelse($notificationItems ?? [] as $notification)
                         @php $payload = json_decode($notification->data, true); @endphp
                         <li>
                            <a href="{{ route('notifications.index') }}"
                               class="block px-4 py-3 flex gap-3 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 hover:bg-slate-50 dark:hover:bg-neutral-700/50">
                               <div class="h-10 w-10 rounded-full flex items-center justify-center shrink-0 bg-slate-100 dark:bg-neutral-900/50">
                                  <x-lucide-bell class="size-[18px] text-slate-600 dark:text-slate-200" />
                               </div>
                               <div class="flex-1">
                                  <p class="text-sm leading-snug text-slate-900 dark:text-slate-50">
                                     {{ $payload['message'] ?? ucfirst(str_replace('.', ' ', $notification->type)) }}
                                  </p>
                                  <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">{{ $notification->created_at?->diffForHumans() ?? '-' }}</p>
                               </div>
                               @if(is_null($notification->read_at))
                                   <div class="w-2 h-2 rounded-full bg-blue-600 mt-2"></div>
                               @endif
                            </a>
                         </li>
                     @empty
                         <li>
                            <div class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">No notifications yet.</div>
                         </li>
                     @endforelse
                  </ul>

                  <a href="{{ route('notifications.index') }}"
                     class="w-full block py-3 text-center text-sm font-semibold rounded-b-md text-slate-900 bg-slate-50 border-t border-slate-300 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 hover:bg-slate-100 dark:border-neutral-700 dark:text-slate-50 dark:bg-neutral-800 dark:hover:bg-neutral-700">
                     View all notifications
                  </a>
               </div>
            </div>
         </div>
         <!-- Profile Dropdown -->
         <div class="relative w-max flex flex-col">
            <button type="button" id="dropdown-toggle" aria-haspopup="true" aria-expanded="false"
               aria-controls="dropdown-menu"
               class="border border-slate-300 rounded-full cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
               <img src="{{ Auth::user()->getFirstMediaUrl('avatars') }}" alt="profile-pic" class="size-9 rounded-full" />
            </button>

            <!-- Dropdown Menu -->
            <ul id="dropdown-menu" aria-labelledby="dropdown-toggle"
               class="hidden absolute right-0 top-full mt-2 p-2 space-y-0.5 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden dark:text-slate-400 dark:bg-neutral-800 dark:border-neutral-700">
               <li>
                  <a href="{{ route('profile.edit') }}"
                     class="dropdown-item w-full p-2 flex items-center gap-2.5 rounded-md cursor-pointer transition-colors hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-700">
                     <x-lucide-circle-user class="size-[18px]  overflow-visible" />
                     My Profile
                  </a>
               </li>
             
                                <li>
                  <a href="{{ route('settings.index') }}"
                     class="dropdown-item w-full p-2 flex items-center gap-2.5 rounded-md cursor-pointer transition-colors hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-700">
                     <x-lucide-settings class="size-[18px]  overflow-visible" />
                      Settings
                  </a>
               </li>
              

              <!--  <li>
                  <a href="#"
                     class="dropdown-item w-full p-2 flex items-center gap-2.5 rounded-md cursor-pointer transition-colors hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-700">
                     <x-lucide-credit-card class="size-[18px] overflow-visible" />
                     Billing & Payments
                  </a>
               </li> -->

               <li>

                  <a href="{{ route('logout') }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                     class="dropdown-item w-full p-2 flex items-center gap-2.5 rounded-md cursor-pointer transition-colors hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-700">
                     <x-lucide-log-out class="size-[18px]  overflow-visible" />
                     Logout
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     @csrf
                  </form>
               </li>
            </ul>
         </div>
      </div>
   </div>
</header>