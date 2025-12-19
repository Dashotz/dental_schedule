@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people"></i> Patients</h2>
    <div>
        <form method="GET" action="{{ route('patients.index') }}" class="d-inline">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search patients..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>
                                <strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong>
                            </td>
                            <td>{{ $patient->email ?? 'N/A' }}</td>
                            <td>{{ $patient->phone ?? 'N/A' }}</td>
                            <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</td>
                            <td>{{ $patient->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No patients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection

