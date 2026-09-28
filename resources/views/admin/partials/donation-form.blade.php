
<div class="row g-2">
<div class="col-md-4"><label class="form-label">Donor *</label><select name="donor_id" class="form-select" required><option value="">Select donor</option>@foreach($donorUsers as $d)<option value="{{ $d->id }}" @selected(old('donor_id',$value?->donor_id)==$d->id)>{{ $d->name }} — {{ $d->donorProfile?->blood_group ?? '' }}</option>@endforeach</select></div>
<div class="col-md-2"><label class="form-label">Blood Group *</label><select name="blood_group" class="form-select">@foreach($groups as $g)<option value="{{ $g }}" @selected(old('blood_group',$value?->blood_group)===$g)>{{ $g }}</option>@endforeach</select></div>
<div class="col-md-2"><label class="form-label">Units *</label><input type="number" min="1" name="units" class="form-control" required value="{{ old('units',$value?->units ?? 1) }}"></div>
<div class="col-md-2"><label class="form-label">Donated At *</label><input type="date" name="donated_at" class="form-control" required value="{{ old('donated_at',$value?->donated_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"></div>
<div class="col-md-2"><label class="form-label">Screening</label><select name="screening_status" class="form-select">@foreach(['pending','approved','rejected'] as $s)<option value="{{ $s }}" @selected(old('screening_status',$value?->screening_status ?? 'pending')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$value?->notes) }}</textarea></div>
</div>
