
<div class="row g-2">
<div class="col-md-4"><label class="form-label">Name *</label><input name="name" class="form-control" required value="{{ old('name',$value?->name) }}"></div>
<div class="col-md-4"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required value="{{ old('email',$value?->email) }}"></div>
<div class="col-md-4"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone',$value?->phone) }}"></div>
<div class="col-md-4"><label class="form-label">Password {{ $value ? '(leave blank to keep)' : '' }}</label><input type="password" name="password" class="form-control" {{ $value ? '' : 'placeholder=Donor@12345' }}></div>
<div class="col-md-2"><label class="form-label">Blood Group *</label><select name="blood_group" class="form-select">@foreach($groups as $g)<option value="{{ $g }}" @selected(old('blood_group',$value?->donorProfile?->blood_group)===$g)>{{ $g }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth',$value?->donorProfile?->date_of_birth?->format('Y-m-d')) }}"></div>
<div class="col-md-3"><label class="form-label">Gender</label><input name="gender" class="form-control" value="{{ old('gender',$value?->donorProfile?->gender) }}"></div>
<div class="col-md-3"><label class="form-label">City</label><input name="city" class="form-control" value="{{ old('city',$value?->donorProfile?->city) }}"></div>
<div class="col-md-5"><label class="form-label">Address</label><input name="address" class="form-control" value="{{ old('address',$value?->donorProfile?->address) }}"></div>
<div class="col-md-3"><label class="form-label">Emergency Contact</label><input name="emergency_contact" class="form-control" value="{{ old('emergency_contact',$value?->donorProfile?->emergency_contact) }}"></div>
<div class="col-md-3"><label class="form-label">Last Donation</label><input type="date" name="last_donation_date" class="form-control" value="{{ old('last_donation_date',$value?->donorProfile?->last_donation_date?->format('Y-m-d')) }}"></div>
<div class="col-md-2 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="eligible" value="1" id="eligible{{ $value?->id ?? 'new' }}" @checked(old('eligible',$value?->donorProfile?->eligible ?? true))><label class="form-check-label" for="eligible{{ $value?->id ?? 'new' }}">Eligible</label></div></div>
<div class="col-md-2 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="active{{ $value?->id ?? 'new' }}" @checked(old('is_active',$value?->is_active ?? true))><label class="form-check-label" for="active{{ $value?->id ?? 'new' }}">Active</label></div></div>
</div>
