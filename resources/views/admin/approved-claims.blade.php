@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Approved Claims</h2>

        <div class="bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Claim ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lab Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                File
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($claims as $claim)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $claim->ClaimID }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $claim->Pt_Name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $claim->Lab_Name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ asset($claim->File) }}"
                                       class="text-purple-600 hover:text-purple-900"
                                       target="_blank">
                                        View File
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No approved claims found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
