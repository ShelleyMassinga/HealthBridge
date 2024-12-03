@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Search Form -->
        <form action="{{ route('admin.request-claim') }}" method="GET" class="mb-6">
            <div class="relative">
                <input type="text"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Search patients by name..."
                       class="w-full pl-4 pr-10 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>

        <!-- Patients List -->
        <div class="space-y-6">
            @forelse($patients as $patient)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-800 font-bold">{{ substr($patient->Pt_Name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">{{ $patient->Pt_Name }}</h3>
                            <p class="text-sm text-gray-500">Insurance ID: {{ $patient->Ins_member_id }}</p>
                        </div>
                    </div>
                    <button onclick="openClaimModal('{{ $patient->PatientID }}', '{{ $patient->Pt_Name }}')"
                            class="bg-purple-800 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                        <span>Send Claim</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                No patients found
            </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div id="claimModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                <h2 class="text-xl font-bold mb-4">Submit Claim Request</h2>

                <form action="{{ route('admin.submit-claim') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="patient_id" id="patient_id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient Name</label>
                            <input type="text" id="patient_name" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 bg-gray-50" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Claim Details</label>
                            <textarea name="claim_details" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" rows="4"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Attach Documents</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-purple-800 hover:text-purple-700">
                                            <span>Upload a file</span>
                                            <input type="file" name="claim_file" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeClaimModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-purple-800 text-white rounded-lg hover:bg-purple-700">Submit Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openClaimModal(patientId, patientName) {
        document.getElementById('patient_id').value = patientId;
        document.getElementById('patient_name').value = patientName;
        document.getElementById('claimModal').classList.remove('hidden');
    }

    function closeClaimModal() {
        document.getElementById('claimModal').classList.add('hidden');
    }
</script>
@endsection
