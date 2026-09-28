
<div class="row g-2">
<div class="col-md-6"><label class="form-label">Hospital *</label><select name="hospital_id" class="form-select" required>@foreach($hospitalRows as $h)<option value="{{ $h->id }}" @selected(old('hospital_id',$value?->hospital_id)==$h->id)>{{ $h->hospital_name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Patient Name *</label><input name="patient_name" class="form-control" required value="{{ old('patient_name',$value?->patient_name) }}"></div>
<div class="col-md-2"><label class="form-label">Age</label><input type="number" min="0" max="130" name="patient_age" class="form-control" value="{{ old('patient_age',$value?->patient_age) }}"></div>
<div class="col-md-3"><label class="form-label">Blood Group *</label><select name="blood_group" class="form-select">@foreach($groups as $g)<option value="{{ $g }}" @selected(old('blood_group',$value?->blood_group)===$g)>{{ $g }}</option>@endforeach</select></div>
<div class="col-md-2"><label class="form-label">Units *</label><input type="number" min="1" max="50" name="units_required" class="form-control" required value="{{ old('units_required',$value?->units_required ?? 1) }}"></div>
<div class="col-md-2"><label class="form-label">Urgency</label><select name="urgency" class="form-select">@foreach(['normal','urgent','critical'] as $s)<option value="{{ $s }}" @selected(old('urgency',$value?->urgency ?? 'normal')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label">Needed By</label><input type="datetime-local" name="needed_by" class="form-control" value="{{ old('needed_by',$value?->needed_by?->format('Y-m-d\TH:i')) }}"></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['pending','approved','fulfilled','rejected','cancelled'] as $s)<option value="{{ $s }}" @selected(old('status',$value?->status ?? 'pending')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-md-8"><label class="form-label">Reason</label><input name="reason" class="form-control" value="{{ old('reason',$value?->reason) }}"></div>
<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$value?->notes) }}</textarea></div>
</div>
