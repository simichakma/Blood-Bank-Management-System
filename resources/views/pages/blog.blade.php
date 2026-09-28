@extends('layouts.app', ['title'=>'Blog'])
@section('content')
@include('pages.style')
<main class="public-page">
 <div class="crumb"><div class="page-wrap"><a href="{{ route('home') }}">Home</a> / Blog</div></div>
 <header class="page-hero"><div class="page-wrap"><h1>BloodBankSystem Blog</h1><p>Insights, tips, and stories about blood donation and health</p><form class="blog-search" method="GET" action="{{ route('blog.index') }}"><input aria-label="Search articles" name="search" placeholder="Search articles..." value="{{ request('search') }}"><input type="hidden" name="category" value="{{ request('category') }}"><button>Search</button></form></div></header>
 <section class="page-section"><div class="page-wrap"><nav class="filter-tabs" aria-label="Article categories">@foreach([''=>'All','awareness'=>'Awareness','education'=>'Education','tips'=>'Tips'] as $key=>$label)<a class="{{ request('category','') === $key ? 'active' : '' }}" href="{{ route('blog.index', ['category'=>$key,'search'=>request('search')]) }}">{{ $label }}</a>@endforeach</nav><div class="empty-list"><h2>No articles yet</h2><p>There are no published articles to display.</p></div></div></section>
</main>
@endsection
