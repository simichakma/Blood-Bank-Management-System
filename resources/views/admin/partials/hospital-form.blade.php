
<div class="row g-2">
<div class="col-md-5"><label class="form-label">Hospital Name *</label><input name="hospital_name" class="form-control" required value="{{ old('hospital_name',$value?->hospital_name) }}"></div>
<div class="col-md-3"><label class="form-label">Registration No. *</label><input name="registration_no" class="form-control" required value="{{ old('registration_no',$value?->registration_no) }}"></div>
<div class="col-md-4"><label class="form-label">Contact Person</label><input name="contact_person" class="form-control" value="{{ old('contact_person',$value?->contact_person) }}"></div>
<div class="col-md-4"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required value="{{ old('email',$value?->user?->email) }}"></div>
<div class="col-md-4"><label class="form-label">Phone</label><input name="phone" class="form-control" value="{{ old('phone',$value?->phone) }}"></div>
<div class="col-md-4"><label class="form-label">Password {{ $value ? '(leave blank to keep)' : '' }}</label><input type="password" name="password" class="form-control" {{ $value ? '' : 'placeholder=Hospital@12345' }}></div>
<div class="col-md-4"><label class="form-label">City</label><input name="city" class="form-control" value="{{ old('city',$value?->city) }}"></div>
<div class="col-md-8"><label class="form-label">Address</label><input name="address" class="form-control" value="{{ old('address',$value?->address) }}"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['pending','approved','suspended'] as $s)<option value="{{ $s }}" @selected(old('status',$value?->status)===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-md-3 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="hospitalActive{{ $value?->id ?? 'new' }}" @checked(old('is_active',$value?->user?->is_active ?? true))><label class="form-check-label" for="hospitalActive{{ $value?->id ?? 'new' }}">Account Active</label></div></div>
</div>
