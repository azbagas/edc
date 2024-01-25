<div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Riwayat Pertemuan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">

                        @if ($appointmentHistories->count())
                            <!-- Main node for this component -->
                            <div class="timeline">
                                @foreach ($appointmentHistories as $appointmentHistory)
                                    <!-- Timeline time label -->
                                    <div class="time-label">
                                        <span class="bg-success font-weight-normal">{{ \Carbon\Carbon::parse($appointmentHistory->created_at)->translatedFormat('l, j F Y') }}</span>
                                    </div>
                                    <div>
                                        <!-- Before each timeline item corresponds to one icon on the left scale -->
                                        <i class="fas fa-notes-medical bg-blue"></i>
                                        <!-- Timeline item -->
                                        <div class="timeline-item mb-4">
                                            <!-- Time -->
                                            {{-- <span class="time"><i class="fas fa-clock"></i> 12:05</span> --}}
                                            <!-- Header. Optional -->
                                            <h3 class="timeline-header">
                                                <div class="row">
                                                    <div class="col-md-auto order-md-last mb-3">
                                                        <span
                                                            class="badge badge-{{ $appointmentHistory->status->type }}">{{ $appointmentHistory->status->name }}</span>
                                                            <span class="badge badge-{{ ($appointmentHistory->payment->status ?? '') == 'Lunas' ? 'success' : 'danger' }}">{{ $appointmentHistory->payment->status ?? '' }}</span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="row mb-3 mb-md-2">
                                                            <div class="col-lg-2 mb-1 mb-md-0">
                                                                <b class="mb-0">Dokter</b>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <span><span
                                                                        class="d-none d-lg-inline mr-2">:</span>{{ $appointmentHistory->doctor->user->name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3 mb-md-2">
                                                            <div class="col-lg-2 mb-1 mb-md-0">
                                                                <b class="mb-0">Asisten</b>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <span><span
                                                                        class="d-none d-lg-inline mr-2">:</span>{{ $appointmentHistory->assistant->name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-2 mb-1 mb-md-0">
                                                                <b class="mb-0">Admin</b>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <span><span
                                                                        class="d-none d-lg-inline mr-2">:</span>{{ $appointmentHistory->admin->user->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </h3>
                                            <!-- Body -->
                                            <div class="timeline-body">
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <b class="d-block">Keluhan</b>
                                                        <span>{{ $appointmentHistory->complaint }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <b class="d-block">Diagnosa</b>
                                                        <ul>
                                                            @forelse ($appointmentHistory->diagnoses as $diagnosis)
                                                                <li
                                                                    class="@if (!$loop->last) mb-2 @endif">
                                                                    {{ $diagnosis->name }}
                                                                    ({{ $diagnosis->diagnosis_code }})<br>
                                                                    <i>Catatan: </i>
                                                                    @if ($diagnosis->pivot->note)
                                                                        {{ $diagnosis->pivot->note }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </li>
                                                            @empty
                                                                <li>Tidak ada diagnosa</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <b class="d-block">Tindakan</b>
                                                        <ul>
                                                            @forelse ($appointmentHistory->treatments as $treatment)
                                                                <li
                                                                    class="@if (!$loop->last) mb-2 @endif">
                                                                    {{ $treatment->treatment_type->name }}:
                                                                    {{ $treatment->name }}<br>
                                                                    <i>Catatan: </i>
                                                                    @if ($treatment->pivot->note)
                                                                        {{ $treatment->pivot->note }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </li>
                                                            @empty
                                                                <li>Tidak ada tindakan</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <b class="d-block">Obat</b>
                                                        <ul>
                                                            @forelse ($appointmentHistory->medicines as $medicine)
                                                                <li
                                                                    class="@if (!$loop->last) mb-2 @endif">
                                                                    {{ $medicine->name }}
                                                                    {{ $medicine->dose }}
                                                                    x{{ $medicine->pivot->quantity }}
                                                                    {{ $medicine->unit }}
                                                                </li>
                                                            @empty
                                                                <li>Tidak ada obat</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Placement of additional controls. Optional -->
                                            <div class="timeline-footer">
                                                <a href="/appointments/{{ $appointmentHistory->id }}"
                                                    class="btn btn-primary btn-sm">Lihat Detail</a>
                                                {{-- <a class="btn btn-danger btn-sm">Delete</a> --}}
                                            </div>
                                        </div>
                                    </div>

                                    @if ($loop->last)
                                        <!-- The last icon means the story is complete -->
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="mb-3">Belum ada pertemuan.</div>
                        @endif

                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
