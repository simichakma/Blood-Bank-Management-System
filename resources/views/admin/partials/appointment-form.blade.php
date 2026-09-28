
<div class="row g-2">
<div class="col-md-4"><label class="form-label">Donor *</label><select name="donor_id" class="form-select" required><option value="">Select donor</option>@foreach($donorUsers as $d)<option value="{{ $d->id }}" @selected(old('donor_id',$value?->donor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Appointment *</label><input type="datetime-local" name="appointment_at" class="form-control" required value="{{ old('appointment_at',$value?->appointment_at?->format('Y-m-d\TH:i')) }}"></div>
<div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control" value="{{ old('location',$value?->location) }}"></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['scheduled','completed','cancelled'] as $s)<option value="{{ $s }}" @selected(old('status',$value?->status ?? 'scheduled')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-md-9"><label class="form-label">Notes</label><input name="notes" class="form-control" value="{{ old('notes',$value?->notes) }}"></div>
</div>
