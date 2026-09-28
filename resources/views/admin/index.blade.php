@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><h2 class="fw-bold mb-1">Admin Management</h2><p class="text-muted mb-0">Manage donors, hospitals, inventory, donations, appointments and blood requests.</p></div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-bold mb-1">Please fix the following:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $editAction = match($editType) {
            'donor' => $editing ? route('admin.donors.update',$editing) : route('admin.donors.store'),
            'hospital' => $editing ? route('admin.hospitals.update',$editing) : route('admin.hospitals.store'),
            'inventory' => $editing ? route('admin.inventory.update',$editing) : route('admin.inventory.store'),
            'donation' => $editing ? route('admin.donations.update',$editing) : route('admin.donations.store'),
            'appointment' => $editing ? route('admin.appointments.update',$editing) : route('admin.appointments.store'),
            'request' => $editing ? route('admin.requests.update',$editing) : route('admin.index'),
            default => null
        };
    @endphp

    <div class="row g-3 mb-4">
        @foreach([['Donors',$donors->count(),'#donors'],['Hospitals',$hospitals->count(),'#hospitals'],['Inventory',$inventoryRows->count(),'#inventory'],['Donations',$donationsRows->count(),'#donations'],['Appointments',$appointmentsRows->count(),'#appointments'],['Requests',$requestsRows->count(),'#requests']] as $s)
            <div class="col-6 col-md-4 col-xl-2"><a href="{{ $s[2] }}" class="text-decoration-none"><div class="card p-3 h-100"><div class="small text-muted">{{ $s[0] }}</div><div class="fs-3 fw-bold text-danger">{{ $s[1] }}</div></div></a></div>
        @endforeach
    </div>

    @if($editing && $editType !== 'request')
    <div class="card p-4 mb-4 border border-danger-subtle">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Edit {{ ucfirst($editType) }}</h5>
            <a href="{{ route('admin.index') }}" class="btn btn-sm btn-light">Cancel</a>
        </div>
        <form method="POST" action="{{ $editAction }}">
            @csrf @method('PUT')
            @if($editType==='donor')
                @include('admin.partials.donor-form',['value'=>$editing])
            @elseif($editType==='hospital')
                @include('admin.partials.hospital-form',['value'=>$editing])
            @elseif($editType==='inventory')
                @include('admin.partials.inventory-form',['value'=>$editing])
            @elseif($editType==='donation')
                @include('admin.partials.donation-form',['value'=>$editing])
            @elseif($editType==='appointment')
                @include('admin.partials.appointment-form',['value'=>$editing])
            @endif
            <button class="btn btn-danger px-4">Update {{ ucfirst($editType) }}</button>
        </form>
    </div>
    @endif

    <div class="accordion" id="managementAccordion">
        <div class="accordion-item card mb-3" id="donors">
            <h2 class="accordion-header"><button class="accordion-button fw-bold" data-bs-toggle="collapse" data-bs-target="#donorPanel">Donor Management</button></h2>
            <div id="donorPanel" class="accordion-collapse collapse show"><div class="accordion-body">
                @if(!$editing || $editType!=='donor')
                <form method="POST" action="{{ route('admin.donors.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Donor</h6>
                    @include('admin.partials.donor-form',['value'=>null])
                    <button class="btn btn-danger">+ Add Donor</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Name</th><th>Blood</th><th>City</th><th>Status</th><th>Eligible</th><th>Actions</th></tr></thead><tbody>
                @forelse($donors as $d)<tr><td>#{{ $d->id }}</td><td><b>{{ $d->name }}</b><div class="small text-muted">{{ $d->email }}</div></td><td><span class="blood-pill">{{ $d->donorProfile?->blood_group ?? 'N/A' }}</span></td><td>{{ $d->donorProfile?->city ?? '—' }}</td><td>{!! $d->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>' !!}</td><td>{{ $d->donorProfile?->eligible ? 'Yes' : 'No' }}</td><td class="text-nowrap"><a href="{{ route('admin.index',['edit'=>'donor','id'=>$d->id]) }}#donors" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.donors.delete',$d) }}" onsubmit="return confirm('Delete this donor?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="7" class="text-center text-muted">No donors found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>

        <div class="accordion-item card mb-3" id="hospitals">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#hospitalPanel">Hospital Management</button></h2>
            <div id="hospitalPanel" class="accordion-collapse collapse"><div class="accordion-body">
                @if(!$editing || $editType!=='hospital')
                <form method="POST" action="{{ route('admin.hospitals.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Hospital</h6>
                    @include('admin.partials.hospital-form',['value'=>null])
                    <button class="btn btn-danger">+ Add Hospital</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Hospital</th><th>Registration</th><th>Contact</th><th>City</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($hospitals as $h)<tr><td><b>{{ $h->hospital_name }}</b><div class="small text-muted">{{ $h->user?->email }}</div></td><td>{{ $h->registration_no }}</td><td>{{ $h->contact_person ?? '—' }}<div class="small text-muted">{{ $h->phone }}</div></td><td>{{ $h->city ?? '—' }}</td><td><span class="badge text-bg-{{ $h->status==='approved'?'success':($h->status==='suspended'?'secondary':'warning') }}">{{ ucfirst($h->status) }}</span></td><td class="text-nowrap">@if($h->status!=='approved')<form class="d-inline" method="POST" action="{{ route('admin.hospitals.approve',$h) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>@endif <a href="{{ route('admin.index',['edit'=>'hospital','id'=>$h->id]) }}#hospitals" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.hospitals.delete',$h) }}" onsubmit="return confirm('Delete this hospital?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No hospitals found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>

        <div class="accordion-item card mb-3" id="inventory">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#inventoryPanel">Blood Inventory</button></h2>
            <div id="inventoryPanel" class="accordion-collapse collapse"><div class="accordion-body">
                @if(!$editing || $editType!=='inventory')
                <form method="POST" action="{{ route('admin.inventory.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Inventory</h6>@include('admin.partials.inventory-form',['value'=>null])<button class="btn btn-danger">+ Add Inventory</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Hospital</th><th>Blood</th><th>Units</th><th>Location</th><th>Expiry</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($inventoryRows as $i)<tr><td>{{ $i->hospital?->hospital_name ?? 'Central inventory' }}</td><td><span class="blood-pill">{{ $i->blood_group }}</span></td><td class="fw-bold">{{ $i->units }}</td><td>{{ $i->storage_location ?? '—' }}</td><td>{{ $i->expiry_date?->format('d M Y') ?? '—' }}</td><td><span class="badge text-bg-{{ $i->status==='available'?'success':($i->status==='expired'?'danger':'warning') }}">{{ ucfirst($i->status) }}</span></td><td class="text-nowrap"><a href="{{ route('admin.index',['edit'=>'inventory','id'=>$i->id]) }}#inventory" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.inventory.delete',$i) }}" onsubmit="return confirm('Delete this inventory row?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No inventory records found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>

        <div class="accordion-item card mb-3" id="donations">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#donationPanel">Donation Records</button></h2>
            <div id="donationPanel" class="accordion-collapse collapse"><div class="accordion-body">
                @if(!$editing || $editType!=='donation')
                <form method="POST" action="{{ route('admin.donations.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Donation</h6>@include('admin.partials.donation-form',['value'=>null])<button class="btn btn-danger">+ Add Donation</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Donor</th><th>Blood</th><th>Units</th><th>Date</th><th>Screening</th><th>Actions</th></tr></thead><tbody>
                @forelse($donationsRows as $d)<tr><td>{{ $d->donor?->name ?? '—' }}</td><td><span class="blood-pill">{{ $d->blood_group }}</span></td><td>{{ $d->units }}</td><td>{{ $d->donated_at?->format('d M Y') }}</td><td><span class="badge text-bg-{{ $d->screening_status==='approved'?'success':($d->screening_status==='rejected'?'danger':'warning') }}">{{ ucfirst($d->screening_status) }}</span></td><td class="text-nowrap"><a href="{{ route('admin.index',['edit'=>'donation','id'=>$d->id]) }}#donations" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.donations.delete',$d) }}" onsubmit="return confirm('Delete this donation record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No donation records found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>

        <div class="accordion-item card mb-3" id="appointments">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#appointmentPanel">Appointments</button></h2>
            <div id="appointmentPanel" class="accordion-collapse collapse"><div class="accordion-body">
                @if(!$editing || $editType!=='appointment')
                <form method="POST" action="{{ route('admin.appointments.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Appointment</h6>@include('admin.partials.appointment-form',['value'=>null])<button class="btn btn-danger">+ Add Appointment</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Donor</th><th>Date & Time</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($appointmentsRows as $a)<tr><td>{{ $a->donor?->name ?? '—' }}</td><td>{{ $a->appointment_at?->format('d M Y, h:i A') }}</td><td>{{ $a->location ?? '—' }}</td><td><span class="badge text-bg-{{ $a->status==='completed'?'success':($a->status==='cancelled'?'danger':'warning') }}">{{ ucfirst($a->status) }}</span></td><td class="text-nowrap"><a href="{{ route('admin.index',['edit'=>'appointment','id'=>$a->id]) }}#appointments" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.appointments.delete',$a) }}" onsubmit="return confirm('Delete this appointment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">No appointments found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>

        <div class="accordion-item card mb-3" id="requests">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" data-bs-target="#requestPanel">Blood Requests</button></h2>
            <div id="requestPanel" class="accordion-collapse collapse"><div class="accordion-body">
                @if(!$editing || $editType!=='request')
                <form method="POST" action="{{ route('admin.requests.store') }}" class="border rounded-3 p-3 bg-light mb-4">@csrf
                    <h6 class="fw-bold mb-3">Add Blood Request</h6>
                    @include('admin.partials.request-form',['value'=>null])
                    <button class="btn btn-danger">+ Add Blood Request</button>
                </form>
                @endif
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Patient</th><th>Hospital</th><th>Blood</th><th>Units</th><th>Urgency</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($requestsRows as $r)<tr><td><b>{{ $r->patient_name }}</b><div class="small text-muted">{{ $r->patient_age ? $r->patient_age.' years' : '' }}</div></td><td>{{ $r->hospital?->hospital_name ?? '—' }}</td><td><span class="blood-pill">{{ $r->blood_group }}</span></td><td>{{ $r->units_required }}</td><td>{{ ucfirst($r->urgency) }}</td><td><span class="badge text-bg-{{ in_array($r->status,['fulfilled','approved'])?'success':($r->status==='rejected'?'danger':'warning') }}">{{ ucfirst($r->status) }}</span></td><td class="text-nowrap"><a href="{{ route('admin.index',['edit'=>'request','id'=>$r->id]) }}#requests" class="btn btn-sm btn-outline-primary">Edit</a><form class="d-inline" method="POST" action="{{ route('admin.requests.delete',$r) }}" onsubmit="return confirm('Delete this blood request?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>@empty<tr><td colspan="7" class="text-center text-muted">No blood requests found.</td></tr>@endforelse
                </tbody></table></div>
            </div></div>
        </div>
    </div>
</div>

@if($editing && $editType==='request')
<div class="modal fade show" style="display:block;background:rgba(0,0,0,.45)" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Edit Blood Request</h5><a href="{{ route('admin.index') }}" class="btn-close"></a></div>
<form method="POST" action="{{ route('admin.requests.update',$editing) }}">@csrf @method('PUT')
<div class="modal-body">@include('admin.partials.request-form',['value'=>$editing])</div>
<div class="modal-footer"><a href="{{ route('admin.index') }}" class="btn btn-light">Cancel</a><button class="btn btn-danger">Update Request</button></div>
</form></div></div></div>
@endif
@endsection
