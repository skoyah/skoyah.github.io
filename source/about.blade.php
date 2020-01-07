@extends('_layouts.master')

@push('meta')
    <meta property="og:title" content="About {{ $page->siteName }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="A little bit about {{ $page->siteName }}" />
@endpush

@section('body')
    <h1>About Me</h1>

    <img src="/assets/img/avatar.jpg"
        alt="About image"
        class="flex rounded-full h-64 w-64 bg-contain mx-auto md:float-right my-6 md:ml-10">

    <p class="mb-6">
        Before switching careers, I was a marine biologist with a special interest in shark research. Now, I work as a full-time web developer with PHP, Laravel and Vue.
    </p>

    <p class="mb-6">
        You can find me at twitter at <a href="https://twitter.com/joaoecoceanus">@skoyah</a>
    </p>
@endsection
