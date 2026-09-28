@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><h2 class="fw-bold mb-1">BloodBankSystem Dashboard</h2><p class="text-muted mb-0">Live overview from the blood bank database.</p></div>
        <a class="btn btn-danger" href="{{ route('admin.index') }}">⚙ Manage Backend</a>
    </div>
    <div class="row g-3 mb-4">
        @foreach([['Donors',$donors],['Hospitals',$hospitals],['Approved Hospitals',$approvedHospitals],['Requests',$requests],['Pending Requests',$pendingRequests],['Donations',$donations],['Appointments',$appointments],['Available Units',$availableUnits]] as $s)
        <div class="col-6 col-md-4 col-xl-3"><div class="card p-3 h-100"><div class="text-muted small">{{ $s[0] }}</div><div class="fs-3 fw-bold text-danger">{{ $s[1] }}</div></div></div>
        @endforeach
    </div>
    <div class="card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3"><div><h5 class="fw-bold mb-1">Blood Availability</h5><p class="text-muted mb-0">Current available units from database.</p></div><a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger">Frontend</a></div>
        <div class="row g-3">
        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g)
            <div class="col-6 col-md-3"><div class="bg-light border rounded-3 p-3 text-center"><span class="blood-pill">{{ $g }}</span><div class="fs-4 fw-bold mt-2">{{ ($inventory->get($g) ?? collect())->sum('units') }}</div><div class="small text-muted">available units</div></div></div>
        @endforeach
        </div>
    </div>
    <div class="card p-4"><div class="d-flex justify-content-between align-items-center mb-3"><h5 class="fw-bold mb-0">Recent Blood Requests</h5><a href="{{ route('admin.index') }}#requests" class="btn btn-sm btn-outline-danger">Manage</a></div>
        <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Patient</th><th>Hospital</th><th>Blood</th><th>Units</th><th>Urgency</th><th>Status</th></tr></thead><tbody>
        @forelse($recentRequests as $r)<tr><td>{{ $r->patient_name }}</td><td>{{ $r->hospital?->hospital_name ?? '—' }}</td><td><span class="blood-pill">{{ $r->blood_group }}</span></td><td>{{ $r->units_required }}</td><td>{{ ucfirst($r->urgency) }}</td><td><span class="badge text-bg-{{ in_array($r->status,['approved','fulfilled'])?'success':($r->status==='rejected'?'danger':'warning') }}">{{ ucfirst($r->status) }}</span></td></tr>
        @empty<tr><td colspan="6" class="text-center text-muted">No blood requests found.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div>
@endsection
