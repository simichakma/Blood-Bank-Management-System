@extends('layouts.app', ['title'=>'Blood Bank Centers'])
@section('content')
@include('pages.style')
<main class="public-page">
 <div class="crumb"><div class="page-wrap"><a href="{{ route('home') }}">Home</a> / Blood Banks</div></div>
 <header class="page-hero"><div class="page-wrap"><h1>Blood Bank Centers</h1><p>Find blood banks and donation centers across Bangladesh</p></div></header>
 <section class="page-section"><div class="page-wrap">
  <nav class="filter-tabs" aria-label="Filter blood banks by division"><span>Filter by division:</span><a class="{{ $selected === '' ? 'active' : '' }}" href="{{ route('banks.index') }}">All</a>@foreach($divisions as $division => $districts)<a class="{{ $selected === $division ? 'active' : '' }}" href="{{ route('banks.index', ['division'=>$division]) }}">{{ $division }}</a>@endforeach</nav>
  <div class="page-grid">
   @forelse($banks as $bank)
   <article class="page-card"><span class="page-icon" aria-hidden="true">✚</span><h2>{{ $bank->hospital_name }}</h2><p>{{ $bank->address ?: 'Address not provided' }}</p><p>{{ $bank->city }}{{ $bank->division_label ? ' · '.$bank->division_label : '' }}</p>@if($bank->phone)<p><a href="tel:{{ $bank->phone }}">{{ $bank->phone }}</a></p>@endif</article>
   @empty
   <div class="empty-list" style="grid-column:1/-1"><h2>No blood banks found</h2><p>No approved centers are listed{{ $selected ? ' in '.$selected : '' }} yet.</p></div>
   @endforelse
  </div>
 </div></section>
</main>
@endsection
