@extends('layouts.app', ['title'=>'About'])
@section('content')
@include('pages.style')
<main class="public-page">
 <div class="crumb"><div class="page-wrap"><a href="{{ route('home') }}">Home</a> / About</div></div>
 <header class="page-hero"><div class="page-wrap"><h1>About BloodBankSystem</h1><p>Connecting donors and patients. Bringing communities together.</p></div></header>
 <section class="page-section"><div class="page-wrap page-grid mission-grid">
  <article class="page-card"><span class="page-icon" aria-hidden="true">♡</span><h2>Our Mission</h2><p>BloodBankSystem has a single mission: to help ensure that no one in Bangladesh goes without blood because they could not find a donor in time. We connect voluntary blood donors with patients who urgently need blood, making the process simple, fast, and transparent.</p></article>
  <article class="page-card"><span class="page-icon vision-icon" aria-hidden="true">◎</span><h2>Our Vision</h2><p>We envision a Bangladesh where blood is always available when needed — where every willing donor is just a search away, and every patient has hope. We strive to build a nationwide network of voluntary blood donors.</p></article>
 </div></section>
 <section class="page-section white-section"><div class="page-wrap"><header class="page-heading"><h2>Our Core Values</h2><p>The principles that guide everything we do</p></header><div class="page-grid values-grid">
  <article class="page-card"><span class="page-icon" aria-hidden="true">♡</span><h3>Compassion</h3><p>We are driven by empathy for those in need. Every action we take is centered on helping people find the support they need.</p></article>
  <article class="page-card"><span class="page-icon" aria-hidden="true">♢</span><h3>Trust &amp; Safety</h3><p>We value accurate donor information, respectful communication, and the safety of both donors and recipients.</p></article>
  <article class="page-card"><span class="page-icon" aria-hidden="true">♧</span><h3>Community</h3><p>We build a community of willing donors who are ready to help at a moment's notice.</p></article>
  <article class="page-card"><span class="page-icon" aria-hidden="true">ϟ</span><h3>Transparency</h3><p>We value open and honest communication with our donors, recipients, and partners.</p></article>
 </div></div></section>
 <section class="page-section"><div class="page-wrap"><header class="page-heading"><h2>Our Journey</h2><p>From a simple idea toward a nationwide community</p></header><div class="journey-list"><article><h3>A shared purpose</h3><p>Our journey starts with a simple goal: make it easier for people who need blood to find willing donors.</p></article><article><h3>Connecting people</h3><p>BloodBankSystem brings donor search, location filters, and a blood bank directory together in one place.</p></article><article><h3>Growing together</h3><p>Our next step is to reach more communities and encourage more volunteers to participate across Bangladesh.</p></article></div></div></section>
 <section class="mission-cta"><h2>Join Our Mission</h2><p>Whether you need blood or want to donate, we're here for you.</p><div><a href="{{ route('admin.index') }}#donors">♡ Become a Donor</a><a href="#get-in-touch">Get in Touch</a></div></section>
 <section class="page-section white-section" id="get-in-touch"><div class="page-wrap"><header class="page-heading"><h2>Get in Touch</h2><p>Contact a listed donor or blood bank directly using their published contact details.</p></header><div class="filter-tabs" style="justify-content:center"><a href="{{ route('donors.index') }}">Find Donors →</a><a href="{{ route('banks.index') }}">Blood Bank Centers →</a></div></div></section>
</main>
@endsection
