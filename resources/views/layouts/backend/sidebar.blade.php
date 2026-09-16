<!-- sidebar -->
<aside class="sm:w-0   overflow-hidden opacity-100 transition-all duration-300 ease-in-out" id="sidebar"
   aria-label="Sidebar navigation">
   <div id="sidebar-inner"
      class="fixed top-0 left-0 w-[264px] h-full flex flex-col overflow-auto py-6 px-4 border-r border-slate-300 dark:border-neutral-700"
      style="background-color: {{ $settings['sidebar_background_color'] ?? '#ffffff' }}; color: {{ $settings['body_text_color'] ?? '#334155' }};">

      <div class="mb-8 justify-center flex items-center gap-2">
         <a href="#"
            class="min-h-9 inline-flex gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
            @if(isset($settings['logo_image']) && $settings['logo_image'])
               <img src="{{ $settings['logo_image'] }}" alt="Logo" class="h-9 w-auto">
               <span
                  class="text-2xl  font-bold bg-gradient-to-r from-primary to-black bg-clip-text text-transparent">{{ $settings['sidebar_title'] ?? 'H.S Coaching' }}</span>

            @else
               <span
                  class="text-3xl font-bold bg-gradient-to-r from-primary to-black bg-clip-text text-transparent">{{ $settings['sidebar_title'] ?? 'H.S Coaching' }}</span>
            @endif
         </a>
      </div>


      <nav class="flex-1" aria-label="Primary sidebar navigation">

         <ul class="space-y-2 text-sm text-slate-800 dark:text-slate-400 font-medium">
            <li>
               <a href="{{ route('dashboard') }}"
                  class="{{ request()->routeIs('dashboard') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                  <x-lucide-layout-dashboard class="size-[18px] fill-current overflow-visible" />
                  <span style="color: {{ $settings['link_color'] ?? '#2563eb' }};">Dashboard</span>
               </a>
            </li>

            @if(!auth()->user()->hasRole('Student'))
               <div>
                  <button type="button" aria-controls="sub-menu-1"
                     aria-expanded="{{ request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? 'true' : 'false' }}"
                     class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                     <x-lucide-indian-rupee class="size-[18px]  overflow-visible" />

                     <span class="flex-1">Leads</span>

                     <svg xmlns="http://www.w3.org/2000/svg"
                        class="arrow size-3 fill-current transition-all {{ request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? '' : '-rotate-90' }}"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path
                           d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                           data-name="16" data-original="#000000" />
                     </svg>
                  </button>

                  <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                     style="{{ request()->routeIs('leads.*') || request()->routeIs('statuses.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">


                     <li>
                        <a href="{{ route('leads.index') }}"
                           class="{{ request()->routeIs('leads.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">

                           <span style="color: {{ $settings['link_color'] ?? '#2563eb' }};">Leads</span>
                        </a>
                     </li>
                     @if (auth()->user()->hasRole('Admin'))
                        <li>
                           <a href="{{ route('statuses.index') }}"
                              class="{{ request()->routeIs('statuses.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">

                              <span style="color: {{ $settings['link_color'] ?? '#2563eb' }};">Lead Status</span>
                           </a>
                        </li>
                     @endif
                  </ul>
               </div>
            @endif

            @if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Team Manager'))

               <li>
                  <a href="{{ route('users.index') }}"
                     class="{{ request()->routeIs('users.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                     <x-lucide-user class="size-[18px]  overflow-visible" />
                     <span>User</span>
                  </a>
               </li>



               <li>
                  <a href="{{ route('students.index') }}"
                     class="{{ request()->routeIs('students.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                     <x-lucide-users class="size-[18px]  overflow-visible" />
                     <span>Student</span>
                  </a>
               </li>

               @if (auth()->user()->hasRole('Admin'))
                   <!-- Migration Certificate -->
                   <div>
                      <button type="button" aria-controls="sub-menu-migration"
                         aria-expanded="{{ request()->routeIs('migration.*') ? 'true' : 'false' }}"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <x-lucide-scroll class="size-[18px] overflow-visible text-emerald-600" />
                         <span class="flex-1">Migration</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all {{ request()->routeIs('migration.*') ? '' : '-rotate-90' }}"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="{{ request()->routeIs('migration.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                         <li>
                            <a href="{{ route('migration.records') }}"
                               class="{{ request()->routeIs('migration.records') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Certificates</span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ route('migration.index') }}"
                               class="{{ request()->routeIs('migration.index') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Migration Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Admit Card -->
                   <div>
                      <button type="button" aria-controls="sub-menu-admitcard"
                         aria-expanded="{{ request()->routeIs('admitcard.*') ? 'true' : 'false' }}"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <x-lucide-badge-check class="size-[18px] overflow-visible text-blue-600" />
                         <span class="flex-1">Admit Card</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all {{ request()->routeIs('admitcard.*') ? '' : '-rotate-90' }}"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="{{ request()->routeIs('admitcard.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                         <li>
                            <a href="{{ route('admitcard.records') }}"
                               class="{{ request()->routeIs('admitcard.records') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Admit Cards</span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ route('admitcard.index') }}"
                               class="{{ request()->routeIs('admitcard.index') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Admit Card Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Marksheet -->
                   <div>
                      <button type="button" aria-controls="sub-menu-marksheet"
                         aria-expanded="{{ request()->routeIs('marksheet.*') ? 'true' : 'false' }}"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <x-lucide-file-text class="size-[18px] overflow-visible text-cyan-600" />
                         <span class="flex-1">Marksheet</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all {{ request()->routeIs('marksheet.*') ? '' : '-rotate-90' }}"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="{{ request()->routeIs('marksheet.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                         <li>
                            <a href="{{ route('marksheet.records') }}"
                               class="{{ request()->routeIs('marksheet.records') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Marksheets</span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ route('marksheet.index') }}"
                               class="{{ request()->routeIs('marksheet.index') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Marksheet Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- Diploma -->
                   <div>
                      <button type="button" aria-controls="sub-menu-diploma"
                         aria-expanded="{{ request()->routeIs('diploma.*') ? 'true' : 'false' }}"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <x-lucide-award class="size-[18px] overflow-visible text-amber-600" />
                         <span class="flex-1">Diploma</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all {{ request()->routeIs('diploma.*') ? '' : '-rotate-90' }}"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="{{ request()->routeIs('diploma.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                         <li>
                            <a href="{{ route('diploma.records') }}"
                               class="{{ request()->routeIs('diploma.records') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All Diplomas</span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ route('diploma.index') }}"
                               class="{{ request()->routeIs('diploma.index') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>Diploma Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>

                   <!-- ID Card -->
                   <div>
                      <button type="button" aria-controls="sub-menu-idcard"
                         aria-expanded="{{ request()->routeIs('idcard.*') ? 'true' : 'false' }}"
                         class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                         <x-lucide-id-card class="size-[18px] overflow-visible text-indigo-600" />
                         <span class="flex-1">ID Card</span>
                         <svg xmlns="http://www.w3.org/2000/svg"
                            class="arrow size-3 fill-current transition-all {{ request()->routeIs('idcard.*') ? '' : '-rotate-90' }}"
                            viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435" />
                         </svg>
                      </button>
                      <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                         style="{{ request()->routeIs('idcard.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                         <li>
                            <a href="{{ route('idcard.records') }}"
                               class="{{ request()->routeIs('idcard.records') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>All ID Cards</span>
                            </a>
                         </li>
                         <li>
                            <a href="{{ route('idcard.index') }}"
                               class="{{ request()->routeIs('idcard.index') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                               <span>ID Card Studio</span>
                            </a>
                         </li>
                      </ul>
                   </div>
                @endif

               @if (auth()->user()->hasRole('Admin'))
                     <li>
                        <a href="{{ route('clients.index') }}"
                           class="{{ request()->routeIs('clients.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                           <x-lucide-user-star class="size-[18px]  overflow-visible" />
                           <span>Clients</span>
                        </a>
                     </li>

                     <div>
                        <button type="button" aria-controls="sub-menu-1"
                           aria-expanded="{{ request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? 'true' : 'false' }}"
                           class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                           <x-lucide-book-open class="size-[18px]  overflow-visible" />


                           <span class="flex-1">Courses</span>

                           <svg xmlns="http://www.w3.org/2000/svg"
                              class="arrow size-3 fill-current transition-all {{ request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? '' : '-rotate-90' }}"
                              viewBox="0 0 24 24" aria-hidden="true">
                              <path
                                 d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                                 data-name="16" data-original="#000000" />
                           </svg>
                        </button>

                        <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                           style="{{ request()->routeIs('courses.*') || request()->routeIs('course-types.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">


                           <li>
                              <a href="{{ route('courses.index') }}"
                                 class="{{ request()->routeIs('courses.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                                 Courses
                              </a>


                           </li>
                           <li>

                              <a href="{{ route('course-types.index') }}"
                                 class="{{ request()->routeIs('course-types.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                                 Course Types
                              </a>

                           </li>
                        </ul>
                     </div>

                     <li>
                        <a href="{{ route('batches.index') }}"
                           class="{{ request()->routeIs('batches.*') ? ' bg-slate-100 ' : '' }} flex items-center gap-2.5 hover:text-slate-900 hover:bg-slate-100 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:hover:text-slate-50 dark:hover:bg-neutral-800">
                           <x-lucide-calendar-days class="size-[18px]  overflow-visible" />
                           <span>Batch</span>
                        </a>
                     </li>




                  </ul>


                  <div>
                     <button type="button" aria-controls="sub-menu-1"
                        aria-expanded="{{ request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? 'true' : 'false' }}"
                        class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <x-lucide-indian-rupee class="size-[18px]  overflow-visible" />

                        <span class="flex-1">Finance</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                           class="arrow size-3 fill-current transition-all {{ request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? '' : '-rotate-90' }}"
                           viewBox="0 0 24 24" aria-hidden="true">
                           <path
                              d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                              data-name="16" data-original="#000000" />
                        </svg>
                     </button>

                     <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                        style="{{ request()->routeIs('invoices.*') || request()->routeIs('payments.*') || request()->routeIs('installments.*') ? 'max-height: 156px;' : 'max-height: 0;' }}">
                        <li>
                           <a href="{{ route('invoices.index') }}"
                              class="{{ request()->routeIs('invoices.*') ? ' bg-slate-100 ' : '' }} block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Invoices
                           </a>
                        </li>
                        <li>
                           <a href="{{ route('payments.index') }}"
                              class="{{ request()->routeIs('payments.*') ? ' bg-slate-100 ' : '' }} block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Payments
                           </a>
                        </li>
                        <li>
                           <a href="{{ route('installments.index') }}"
                              class="{{ request()->routeIs('installments.*') ? ' bg-slate-100 ' : '' }} block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Installments
                           </a>
                        </li>


                        <!--     <li>
                            <a href="#"
                               class="block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                               Expenses
                            </a>
                         </li>
                         <li>
                            <a href="#"
                               class="block hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                               Estimates
                            </a>
                         </li> -->


                     </ul>
                  </div>

                  <div>
                     <button type="button" aria-controls="sub-menu-1"
                        aria-expanded="{{ request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? 'true' : 'false' }}"
                        class="flex items-center gap-2.5 cursor-pointer w-full text-sm text-slate-800 dark:text-slate-400 font-medium text-left group collapsible-toggle hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        <x-lucide-chart-line class="size-[18px]  overflow-visible" />


                        <span class="flex-1">Reports</span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                           class="arrow size-3  fill-current transition-all {{ request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? '' : '-rotate-90' }}"
                           viewBox="0 0 24 24" aria-hidden="true">
                           <path
                              d="M12.016 18a1.5 1.5 0 0 1-1.065-.434l-9-9a1.506 1.506 0 0 1 2.13-2.13l7.935 7.95L19.95 6.45a1.5 1.5 0 0 1 2.115 2.115l-9 9a1.5 1.5 0 0 1-1.05.435"
                              data-name="16" data-original="#000000" />
                        </svg>
                     </button>

                     <ul class="space-y-1 text-sm text-slate-600 dark:text-slate-400 font-medium px-3 my-1 max-h-0 overflow-hidden transition-all duration-300"
                        style="{{ request()->routeIs('activity-logs.*') || request()->routeIs('reports.*') ? 'max-height: 192px;' : 'max-height: 0;' }}">
                        <li>
                           <a href="{{ route('reports.earnings') }}"
                              class="block {{ request()->routeIs('reports.earnings') ? ' bg-slate-100 ' : '' }} hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Earnings
                           </a>
                        </li>
                        <li>
                           <a href="{{ route('reports.refunds') }}"
                              class="block {{ request()->routeIs('reports.refunds') ? ' bg-slate-100 ' : '' }} hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Refunds
                           </a>
                        </li>

                        <li>
                           <a href="{{ route('activity-logs.index') }}"
                              class="block {{ request()->routeIs('activity-logs.*') ? ' bg-slate-100 ' : '' }} hover:text-slate-900 dark:hover:text-slate-50 hover:bg-slate-100 dark:hover:bg-neutral-800 rounded-md px-3 py-2 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                              Activity Log
                           </a>
                        </li>

                     </ul>
                  </div>



               @endif
            @endif

      </nav>

      <a href="#"
         class="flex flex-wrap items-center gap-4 rounded-md mt-6 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
         <img src="{{ Auth::user()->getFirstMediaUrl('avatars') }}"
            class="w-10 h-10 rounded-md border border-slate-300 dark:border-neutral-700" alt="User avatar" />
         <div>
            <p class="text-sm text-slate-800 dark:text-slate-400 font-medium">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->roles->pluck('name')->join(', ') }}</p>
         </div>
      </a>
   </div>
</aside>