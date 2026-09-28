@extends('layouts.app')
@section('content')
@php
$thanas = [
 'Dhaka'=>['Adabor','Badda','Bangshal','Cantonment','Chak Bazar','Dakshinkhan','Demra','Dhanmondi','Gulshan','Jatrabari','Kafrul','Khilgaon','Khilkhet','Kotwali','Lalbagh','Mirpur','Mohammadpur','Motijheel','Pallabi','Paltan','Ramna','Rampura','Sabujbagh','Shahbagh','Sutrapur','Tejgaon','Turag','Uttara','Dhamrai','Dohar','Keraniganj','Nawabganj','Savar'],
 'Chattogram'=>['Anwara','Banshkhali','Boalkhali','Chandanaish','Fatikchhari','Hathazari','Karnaphuli','Lohagara','Mirsharai','Patiya','Rangunia','Raozan','Sandwip','Satkania','Sitakunda','Bakalia','Bandar','Bayezid','Chandgaon','Double Mooring','Halishahar','Khulshi','Kotwali','Pahartali','Panchlaish','Patenga'],
 'Gazipur'=>['Gazipur Sadar','Kaliakair','Kaliganj','Kapasia','Sreepur'],
 'Narayanganj'=>['Araihazar','Bandar','Narayanganj Sadar','Rupganj','Sonargaon'],
 'Cumilla'=>['Barura','Brahmanpara','Burichang','Chandina','Chauddagram','Daudkandi','Debidwar','Homna','Laksam','Meghna','Muradnagar','Nangalkot','Titas'],
 'Sylhet'=>['Balaganj','Beanibazar','Bishwanath','Companiganj','Dakshin Surma','Fenchuganj','Golapganj','Gowainghat','Jaintiapur','Kanaighat','Osmani Nagar','Sylhet Sadar','Zakiganj'],
 'Rajshahi'=>['Bagha','Bagmara','Charghat','Durgapur','Godagari','Mohanpur','Paba','Puthia','Tanore','Boalia','Matihar','Rajpara','Shah Makhdum'],
 'Khulna'=>['Batiaghata','Dacope','Dumuria','Dighalia','Koyra','Paikgachha','Phultala','Rupsa','Terokhada','Daulatpur','Khalishpur','Khulna Sadar','Sonadanga'],
 'Barishal'=>['Agailjhara','Babuganj','Bakerganj','Banaripara','Barishal Sadar','Gournadi','Hizla','Mehendiganj','Muladi','Wazirpur'],
 'Rangpur'=>['Badarganj','Gangachara','Kaunia','Mithapukur','Pirgachha','Pirganj','Rangpur Sadar','Taraganj'],
 'Mymensingh'=>['Bhaluka','Dhobaura','Fulbaria','Gaffargaon','Gauripur','Haluaghat','Ishwarganj','Muktagachha','Mymensingh Sadar','Nandail','Phulpur','Tarakanda','Trishal']
];
@endphp
<main class="directory-page">
 <div class="container directory-layout">
  <aside class="filter-panel">
   <div class="filter-heading"><h1>Filters</h1><a href="{{ route('donors.index') }}">Reset</a></div>
   <form id="filterForm" method="GET" action="{{ route('donors.index') }}">
    <label>Search by Name or Area<div class="input-wrap"><span>⌕</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Type a name..."></div></label>
    <label>Division<select name="division" id="filterDivision"><option value="">All Divisions</option>@foreach(array_keys($locations) as $division)<option value="{{ $division }}" @selected(request('division')===$division)>{{ $division }}</option>@endforeach</select></label>
    <label>District<select name="district" id="filterDistrict"><option value="">All Districts</option></select></label>
    <label>Thana<select name="thana" id="filterThana"><option value="">All Thanas</option></select></label>
    <fieldset><legend>Blood Group</legend><div class="blood-options">@foreach($groups as $group)<label><input type="radio" name="blood_group" value="{{ $group }}" @checked(request('blood_group')===$group)><span>{{ $group }}</span></label>@endforeach</div></fieldset>
    <label>Availability<select name="availability"><option value="">All Status</option><option value="available" @selected(request('availability')==='available')>Available</option><option value="unavailable" @selected(request('availability')==='unavailable')>Unavailable</option></select></label>
    <fieldset><legend>Gender</legend><div class="gender-options"><label><input type="radio" name="gender" value="male" @checked(request('gender')==='male')><span>Male</span></label><label><input type="radio" name="gender" value="female" @checked(request('gender')==='female')><span>Female</span></label></div></fieldset>
    <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort','name') }}">
    <button class="apply-filter" type="submit">Apply Filters</button>
   </form>
  </aside>

  <section class="directory-results">
   <header class="result-toolbar"><div><strong>{{ $donors->count() }}</strong> donors found</div><label>Sort by<select id="sortSelect"><option value="name" @selected(request('sort','name')==='name')>Name</option><option value="newest" @selected(request('sort')==='newest')>Newest</option><option value="blood_group" @selected(request('sort')==='blood_group')>Blood Group</option><option value="last_donation" @selected(request('sort')==='last_donation')>Last Donation</option></select></label></header>
   <div class="directory-grid">
    @forelse($donors as $profile)
     @php
      $user=$profile->user;
      $age=$profile->date_of_birth ? $profile->date_of_birth->age : null;
      $initials=collect(explode(' ',trim($user?->name ?? 'Donor')))->filter()->take(2)->map(fn($word)=>strtoupper(substr($word,0,1)))->join('');
     @endphp
     <article class="directory-card">
      <div class="card-top"><div class="donor-avatar">{{ $initials }}</div><div class="donor-name"><h2>{{ $user?->name ?? 'Blood Donor' }}</h2><p>{{ $profile->gender ? ucfirst($profile->gender) : 'Donor' }}{{ $age ? ', '.$age.' yrs' : '' }}</p></div><span class="card-blood">{{ $profile->blood_group }}</span></div>
      <div class="donor-meta"><p><span>⌖</span>{{ $profile->address ?: ($profile->city ?: 'Bangladesh') }}</p><p><span>□</span>Last donation: {{ $profile->last_donation_date?->format('d/m/Y') ?? 'No record' }}</p><p><span>♙</span>{{ $user?->donations_count ?? 0 }} donations total</p></div>
      <div class="card-footer"><span class="{{ $profile->eligible ? 'available' : 'unavailable' }}">● {{ $profile->eligible ? 'Available' : 'Unavailable' }}</span>@if($user?->is_active)<span class="verified">✓ Verified</span>@endif</div>
      <div class="contact-links">@if($user?->phone)<a href="tel:{{ $user->phone }}">☎ {{ $user->phone }}</a>@endif<a href="mailto:{{ $user?->email }}">✉ Email</a></div>
     </article>
    @empty
     <div class="no-donors"><div>⌕</div><h2>No donors found</h2><p>Try changing or clearing some filters.</p><a href="{{ route('donors.index') }}">Clear all filters</a></div>
    @endforelse
   </div>
  </section>
 </div>
</main>

<style>
.directory-page{background:#f7f8fa;min-height:calc(100vh - 68px);padding:34px 0 65px;color:#07162e}.directory-layout{display:grid;grid-template-columns:360px 1fr;gap:28px;align-items:start}.filter-panel,.result-toolbar,.directory-card{background:#fff;border:1px solid #e6e8ec;border-radius:18px;box-shadow:0 2px 7px #1018280b}.filter-panel{padding:27px 25px;position:sticky;top:90px}.filter-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}.filter-heading h1{font-size:1.22rem;font-weight:850;margin:0}.filter-heading a{font-size:.83rem;color:#e3262e;text-decoration:none;font-weight:700}.filter-panel label,.filter-panel legend{display:block;font-size:.89rem;font-weight:650;margin-bottom:18px}.filter-panel select,.filter-panel input[type=search]{width:100%;height:45px;border:1px solid #d4dae3;border-radius:10px;background:#fff;color:#5f6d82;padding:0 13px;margin-top:7px;outline:none}.filter-panel select:focus,.filter-panel input:focus{border-color:#e3262e;box-shadow:0 0 0 3px #e3262e16}.input-wrap{position:relative}.input-wrap span{position:absolute;left:13px;top:18px;color:#98a2b3}.input-wrap input[type=search]{padding-left:39px}.filter-panel fieldset{border:0;padding:0;margin:0 0 18px}.filter-panel legend{margin-bottom:9px}.blood-options{display:grid;grid-template-columns:repeat(4,1fr);gap:9px}.blood-options label,.gender-options label{margin:0}.blood-options input,.gender-options input{position:absolute;opacity:0}.blood-options span,.gender-options span{height:42px;border:1px solid #dce1e8;border-radius:9px;display:grid;place-items:center;font-weight:800;cursor:pointer;transition:.15s}.blood-options input:checked+span,.gender-options input:checked+span{background:#fff0f1;border-color:#e3262e;color:#e3262e}.gender-options{display:grid;grid-template-columns:1fr 1fr;gap:10px}.apply-filter{width:100%;border:0;border-radius:10px;background:#e3262e;color:#fff;padding:12px;font-weight:800}.result-toolbar{min-height:111px;padding:20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:23px}.result-toolbar>div{font-size:1.02rem}.result-toolbar>div strong{font-weight:900}.result-toolbar label{font-size:.86rem;font-weight:650;margin:0}.result-toolbar select{display:block;width:300px;height:44px;border:1px solid #d4dae3;border-radius:10px;background:#fff;padding:0 13px;margin-top:7px}.directory-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:23px}.directory-card{padding:26px;min-height:335px;display:flex;flex-direction:column}.card-top{display:grid;grid-template-columns:60px 1fr auto;gap:14px;align-items:start}.donor-avatar{width:60px;height:60px;border-radius:50%;display:grid;place-items:center;background:#fff0f1;color:#e3262e;font-size:1.25rem;font-weight:900}.donor-name h2{font-size:1.05rem;font-weight:850;margin:7px 0 2px;line-height:1.35}.donor-name p{font-size:.82rem;color:#69758a;margin:0}.card-blood{display:grid;place-items:center;min-width:58px;height:58px;padding:0 10px;border-radius:14px;background:#fff0f1;color:#e3262e;font-size:1.12rem;font-weight:900}.donor-meta{margin:23px 0 16px}.donor-meta p{display:grid;grid-template-columns:20px 1fr;gap:8px;color:#46546b;line-height:1.4;margin:11px 0;font-size:.9rem}.donor-meta p span{color:#9aa6b7}.card-footer{border-top:1px solid #edf0f4;padding-top:14px;display:flex;align-items:center;justify-content:space-between;font-size:.79rem;font-weight:700;margin-top:auto}.available{background:#d9fbe7;color:#059447;border-radius:20px;padding:4px 10px}.unavailable{background:#edf0f4;color:#667085;border-radius:20px;padding:4px 10px}.verified{color:#06a34f}.contact-links{display:flex;gap:12px;margin-top:14px}.contact-links a{font-size:.78rem;color:#e3262e;text-decoration:none;font-weight:750}.no-donors{grid-column:1/-1;text-align:center;background:#fff;border:1px solid #e6e8ec;border-radius:18px;padding:75px 20px}.no-donors>div{font-size:2.4rem;color:#aab2c0}.no-donors h2{font-size:1.3rem;font-weight:850}.no-donors p{color:#667085}.no-donors a{color:#e3262e;font-weight:750;text-decoration:none}
@media(max-width:1200px){.directory-layout{grid-template-columns:300px 1fr}.directory-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:850px){.directory-layout{grid-template-columns:1fr}.filter-panel{position:static}.directory-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.directory-page{padding-top:20px}.directory-grid{grid-template-columns:1fr}.result-toolbar{align-items:flex-start;gap:15px;flex-direction:column}.result-toolbar select{width:100%}.result-toolbar label{width:100%}.blood-options{grid-template-columns:repeat(4,1fr)}.directory-card{min-height:auto}}
</style>
<script>
document.addEventListener('DOMContentLoaded',()=>{const locations=@json($locations),thanas=@json($thanas),division=document.getElementById('filterDivision'),district=document.getElementById('filterDistrict'),thana=document.getElementById('filterThana'),selectedDistrict=@json(request('district')),selectedThana=@json(request('thana'));const fill=(select,items,label,selected='')=>{select.innerHTML='<option value="">'+label+'</option>';items.forEach(item=>{const option=new Option(item,item);option.selected=item===selected;select.add(option)})};function loadDistricts(selected=''){fill(district,division.value?(locations[division.value]||[]):[],'All Districts',selected)}function loadThanas(selected=''){fill(thana,district.value?(thanas[district.value]||[district.value+' Sadar']):[],'All Thanas',selected)}loadDistricts(selectedDistrict);loadThanas(selectedThana);division.addEventListener('change',()=>{loadDistricts();loadThanas()});district.addEventListener('change',()=>loadThanas());document.getElementById('sortSelect').addEventListener('change',function(){document.getElementById('hiddenSort').value=this.value;document.getElementById('filterForm').submit()})});
</script>
@endsection
