<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance Claims</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/insurance.css') }}">
</head>
<body>
<img src="{{ asset('images/HealthBridgeLogo.png') }}" alt="Logo" class="logo">
<div class="contact-us-box">
    <img src="{{ asset('images/msg.png') }}" alt="Message Icon" class="contact-icon" style="height: 15px;">
    <a href="mailto:snehakhan52@gmail.com?subject=Support%20Request&body=Hi%20Team,%0A%0AI%20need%20help%20with..." class="contact-us-text">Contact</a>
</div>

<div class="container">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-4">Insurance Claims</h3>
            <!-- Scrollable table container -->
            <div class="table-responsive table-scrollable" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                    <tr>
                        <th>Lab Name</th>
                        <th>Patient Name</th>
                        <th>Claim File</th>
{{--                        <th>Status</th>--}}
                        <th>
                            Status
                            <button class="btn btn-sm btn-light sort-btn" data-sort="asc">▲</button>
                            <button class="btn btn-sm btn-light sort-btn" data-sort="desc">▼</button>
                        </th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($claims as $claim)
                        <tr>
                            <td>{{ $claim->Lab_Name }}</td>
                            <td>{{ $claim->Pt_Name }}</td>
                            <td>
                                <a href="{{ Storage::url($claim->Claim_File) }}" target="_blank">Download</a>
                            </td>
                            <td id="status-{{ $claim->ClaimID }}">{{ ucfirst($claim->Approval_status ?? 'Pending') }}</td>
                            <td>
                                @if ($claim->Approval_status === 'None')
                                    <form method="POST" action="{{ route('Insurance.claim.update') }}" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Hidden input to pass ClaimID -->

                                        <!-- Hidden inputs for ClaimID, PatientID, LabID, and AppointmentID -->
                                        <input type="hidden" name="ClaimID" value="{{ $claim->ClaimID }}">
                                        <input type="hidden" name="PatientID" value="{{ $claim->PatientID }}">
                                        <input type="hidden" name="LabID" value="{{ $claim->LabID }}">
                                        <input type="hidden" name="AppointmentID" value="{{ $claim->AppointmentID }}">

                                        <div class="d-flex align-items-center">
                                            <!-- Dropdown for selecting Accept or Reject -->
                                            <select class="form-select me-2 action-select" name="action" required>
                                                <option value="">Select Action</option>
                                                <option value="accept">Accept</option>
                                                <option value="reject">Reject</option>
                                            </select>

                                            <!-- File input for rejection reason -->
                                            <input type="file" name="rejection_file" class="form-control rejection-file" style="display: none;" accept=".pdf,.jpg,.png">

                                            <!-- Submit button -->
                                            <button type="submit" class="btn custom-btn-claim">Submit</button>
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">No actions available</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Popup for Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="rejection_file" id="rejectionFile" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const actionSelects = document.querySelectorAll('.action-select');

        actionSelects.forEach(select => {
            select.addEventListener('change', function () {
                const fileInput = this.closest('form').querySelector('.rejection-file');
                if (this.value === 'reject') {
                    fileInput.style.display = 'block'; // Show file input
                    fileInput.required = true; // Make file input required
                } else {
                    fileInput.style.display = 'none'; // Hide file input
                    fileInput.required = false; // Remove required attribute
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortButtons = document.querySelectorAll('.sort-btn');

        // Check if buttons are selected correctly
        if (sortButtons.length === 0) {
            console.error("Sort buttons not found. Ensure the '.sort-btn' class is present on the buttons.");
            return;
        }

        sortButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent any default action

                const sortOrder = this.dataset.sort;
                const rows = Array.from(document.querySelectorAll('tbody tr'));

                // Debugging: Check the number of rows
                if (rows.length === 0) {
                    console.error("No rows found in the table. Ensure <tbody> contains rows.");
                    return;
                }

                rows.sort((a, b) => {
                    const statusA = a.querySelector('td:nth-child(4)').textContent.trim();
                    const statusB = b.querySelector('td:nth-child(4)').textContent.trim();

                    const order = { 'None': 1, 'Approved': 2, 'Reject': 3 };

                    // Debugging: Check the extracted statuses
                    console.log(`Sorting: ${statusA} vs ${statusB}`);

                    return sortOrder === 'asc'
                        ? order[statusA] - order[statusB]
                        : order[statusB] - order[statusA];
                });

                const tbody = document.querySelector('tbody');

                // Debugging: Ensure tbody exists
                if (!tbody) {
                    console.error("Table body (<tbody>) not found.");
                    return;
                }

                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });

</script>


<footer class="footer mt-4">
    <p class="text-center">&copy; 2024 HealthBridge. All rights reserved.</p>
</footer>
</body>
</html>
