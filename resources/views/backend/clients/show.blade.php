@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h3 class="mb-0">{{ $client->client_name }}</h3>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">
                <i class="lucide-pencil"></i> Edit
            </a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="lucide-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Date:</th>
                            <td>{{ $client->date ? $client->date->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Contact Person:</th>
                            <td>{{ $client->contact_person_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number:</th>
                            <td>{{ $client->contact_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Mobile No.:</th>
                            <td>{{ $client->mobile_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $client->email_id ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Location Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Area:</th>
                            <td>{{ $client->area ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{ $client->city ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>{{ $client->state ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Details & Feedback</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="mb-2"><strong>Client Details</strong></h6>
                            <p class="mb-0 text-muted">{{ $client->client_details ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2"><strong>Feedback</strong></h6>
                            <p class="mb-0 text-muted">{{ $client->feedback ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-2"><strong>Final Remark</strong></h6>
                            <p class="mb-0 text-muted">{{ $client->final_remark ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Calling Status</h6>
                    @if($client->calling_status)
                        <span class="badge bg-info" style="font-size: 14px;">{{ $client->calling_status }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Vacancy Status</h6>
                    @if($client->vacancy_status)
                        <span class="badge bg-success" style="font-size: 14px;">{{ $client->vacancy_status }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Requirement Status</h6>
                    @if($client->requirement_status)
                        <span class="badge bg-warning text-dark" style="font-size: 14px;">{{ $client->requirement_status }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Proposal Status</h6>
                    @if($client->proposal_status)
                        <span class="badge bg-primary" style="font-size: 14px;">{{ $client->proposal_status }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Empannel</h6>
                    @if($client->empannel)
                        <span class="badge bg-dark" style="font-size: 14px;">{{ $client->empannel }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Internship Payment</h6>
                    @if($client->internship_payment)
                        <span class="badge bg-info" style="font-size: 14px;">{{ $client->internship_payment }}</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Timestamps</h6>
                    <p class="mb-1"><small>Created: {{ $client->created_at->format('d-m-Y H:i:s') }}</small></p>
                    <p class="mb-0"><small>Updated: {{ $client->updated_at->format('d-m-Y H:i:s') }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
