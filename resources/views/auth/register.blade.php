@extends('layouts.auth_main')
@section('title', 'Register')

@section('content')
<div class="mx-auto md:flex w-full md:p-6 rounded-[2rem] justify-around  p-6 bg-base-100/95   backdrop-blur-sm dark:border-slate-700/80 dark:bg-slate-950/90">
  <div class="md:w-1/2 flex flex-col items-center">
    <img src="{{ asset('images/auth/auth-hero.png') }}" alt="Healthcare illustration" class="h-full w-full object-contain" />
  
  </div>

<form action="{{ route('register-handle') }}" method="post" class="content-center">
    <div class="mt-6 space-y-2">
      <h1 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">Join Our <br /><span class="text-primary">Professional</span>  <br />Network</h1>
      <p class="text-left text-slate-500 dark:text-slate-400">Find jobs in hospitals and home healthcare.</p>
    </div>

      @csrf

     <fieldset class="fieldset bg-base-100 border-base-300 rounded-box ">
  <legend class="fieldset-legend">Register As</legend>
  <label class="label cursor-pointer">
    <input type="radio" name="role" value="employer" checked class="radio" />
    Employer
  </label>
  <label class="label cursor-pointer">
    <input type="radio" name="role" value="employee" class="radio" />
    Employee
  </label>
</fieldset>



         <fieldset class="fieldset mt-3">
        <legend class="fieldset-legend">Mobile Number</legend>
        <label class="input w-full focus:outline-0">
         <x-lucide-phone class="w-6 h-6" />
          <input class="rounded-2xl grow focus:outline-0" placeholder="Mobile Number" type="text" name="phone" required/>
        </label>
      </fieldset>
      <button class="btn btn-primary btn-wide  mt-4 max-w-full gap-3 md:mt-6" type="submit">
        <span class="iconify lucide--mail-plus size-4"></span>
        Join Now
      </button>

       <p class="text-base-content/80 mt-4 text-center text-sm md:mt-6">
      I have already to
      <a class="text-primary ms-1 hover:underline" href="{{ route('login') }}">
        Login
      </a>
    </p>
    </form>
   
  </div>


</div>
@endsection

