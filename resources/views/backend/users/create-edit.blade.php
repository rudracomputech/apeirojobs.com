   @extends('layouts.backend')
   @section('title', 'Users')


   @section('content')


   <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">


      <div class="mb-12">
         <h2 class="text-2xl font-bold text-slate-900 mb-1 md:text-2xl dark:text-slate-50">
            @if(isset($user))
               Edit User
            @else
               Create User
            @endif
         </h2>
         <p class="text-base leading-relaxed text-slate-600 dark:text-slate-400">
            Have a question, need support, or want to discuss your next project? We’re here to help.
         </p>
      </div>

      @if ($errors->any())
         <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <strong class="block font-semibold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside">
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @endif

      <form class="space-y-6" method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
         enctype="multipart/form-data">
         @csrf
         @if(isset($user))
            @method('PUT')
         @endif

         <div class="grid gap-6 lg:grid-cols-2">
            <div>
               <label for="username" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Username</label>
               <input type="text" id="username" name="username" value="{{ old('username', $user->username ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Name</label>
               <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="first_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">First Name</label>
               <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="last_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Last Name</label>
               <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="email" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Email</label>
               <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="email_verified_at" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Email Verified At</label>
               <input type="datetime-local" id="email_verified_at" name="email_verified_at" value="{{ old('email_verified_at', isset($user) && $user->email_verified_at ? date('Y-m-d\TH:i', strtotime($user->email_verified_at)) : '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="password" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Password</label>
               <input type="password" id="password" name="password"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Confirm Password</label>
               <input type="password" id="password_confirmation" name="password_confirmation"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="mobile" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mobile</label>
               <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="gender" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Gender</label>
               <select id="gender" name="gender"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                  <option value=""{{ old('gender', $user->gender ?? '') === '' ? ' selected' : '' }}>Select gender</option>
                  <option value="male"{{ old('gender', $user->gender ?? '') === 'male' ? ' selected' : '' }}>Male</option>
                  <option value="female"{{ old('gender', $user->gender ?? '') === 'female' ? ' selected' : '' }}>Female</option>
                  <option value="other"{{ old('gender', $user->gender ?? '') === 'other' ? ' selected' : '' }}>Other</option>
               </select>
            </div>

            <div>
               <label for="date_of_birth" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Date of Birth</label>
               <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', isset($user) && $user->date_of_birth ? date('Y-m-d', strtotime($user->date_of_birth)) : '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div class="lg:col-span-2">
               <label for="address" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Address</label>
               <textarea id="address" name="address" rows="3"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('address', $user->address ?? '') }}</textarea>
            </div>

            <div class="lg:col-span-2">
               <label for="bio" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Bio</label>
               <textarea id="bio" name="bio" rows="4"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">{{ old('bio', $user->bio ?? '') }}</textarea>
            </div>

            <div>
               <label for="father_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Father Name</label>
               <input type="text" id="father_name" name="father_name" value="{{ old('father_name', $user->father_name ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="mother_name" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Mother Name</label>
               <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name', $user->mother_name ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

          

            <div>
               <label for="avatar" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Avatar</label>
               <input type="file" id="avatar" name="avatar" accept="image/*"
                  class="w-full rounded-md border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-200 dark:border-neutral-700 dark:bg-neutral-800 dark:text-slate-50" />
            </div>

            @if(isset($user) && $user->getFirstMedia('avatars'))
               <div class="flex items-center gap-4 rounded-md border border-slate-200 p-4 dark:border-neutral-700 lg:col-span-2">
                  <img src="{{ $user->getFirstMedia('avatars')->getUrl() }}" alt="Avatar preview" class="h-20 w-20 rounded-full object-cover" />
                  <div>
                     <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">Current avatar</p>
                     <p class="text-sm text-slate-600 dark:text-slate-400">Upload a new image to replace it.</p>
                  </div>
               </div>
            @endif

            <div>
               <label for="last_ip" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Last IP</label>
               <input type="text" id="last_ip" name="last_ip" value="{{ old('last_ip', $user->last_ip ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="login_count" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Login Count</label>
               <input type="number" id="login_count" name="login_count" value="{{ old('login_count', $user->login_count ?? '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="last_login" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Last Login</label>
               <input type="datetime-local" id="last_login" name="last_login" value="{{ old('last_login', isset($user) && $user->last_login ? date('Y-m-d\TH:i', strtotime($user->last_login)) : '') }}"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600" />
            </div>

            <div>
               <label for="roles" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Roles</label>
               <select id="roles" name="roles[]" multiple
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                  @foreach($roles as $id => $name)
                     <option value="{{ $id }}"{{ isset($user) && $user->roles->contains('id', $id) ? ' selected' : '' }}>{{ $name }}</option>
                  @endforeach
               </select>
            </div>

            <div>
               <label for="status" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Status</label>
               <select data-tags="true" id="status" name="status"
                  class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                  <option value=""{{ old('status', $user->status ?? '') === '' ? ' selected' : '' }}>Select status</option>
                  <option value="1"{{ old('status', $user->status ?? '') === '1' ? ' selected' : '' }}>Active</option>
                  <option value="0"{{ old('status', $user->status ?? '') === '0' ? ' selected' : '' }}>Inactive</option>
                  <option value="2"{{ old('status', $user->status ?? '') === '2' ? ' selected' : '' }}>Pending</option>
               </select>
            </div>

      @if(auth()->user()->hasRole('Admin'))
           
            <div>
                <label for="created_by" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Created By</label>
                <select id="created_by" name="created_by" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select user</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('created_by', $student->created_by ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

                 <div>
                <label for="updated_by" class="mb-2 block text-sm font-medium text-slate-900 dark:text-slate-50">Updated By</label>
                <select id="updated_by" name="updated_by" class="px-3 py-2.5 text-sm text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 dark:text-slate-50 dark:bg-neutral-700 dark:outline-neutral-600">
                    <option value="">Select user</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}"{{ old('created_by', $student->created_by ?? '') == $id ? ' selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            @endif

        
         </div>

         <button type="submit"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            {{ isset($user) ? 'Update User' : 'Create User' }}
         </button>
      </form>

   </div>

   @endsection

   @section('page-js')
   @endsection