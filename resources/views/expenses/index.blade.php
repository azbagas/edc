@extends('layouts.app')

@section('title', 'Pendapatan')

@section('styles')
    <style>
        #date_range:hover {
            cursor: pointer;
        }

        #date_range {
            caret-color: transparent;
        }

        #date-order {
            cursor: pointer;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengeluaran</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <a href="/expenses/create" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>Tambah pengeluaran
                    </a>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/expenses" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="date_range">Waktu</label>
                            
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="start_date">
                                                <input type="hidden" name="end_date">
                                                <input type="text" class="form-control" id="date_range">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="total_expenses">Total Pengeluaran</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input id="total_expenses" type="text" class="form-control rupiah" placeholder="0,00" value="{{ $total_expenses }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Pengeluaran</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari pengeluaran..." value="{{ request('name') }}">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-info">
                                                      <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="date_order" value="{{ request('date_order') ?? 'asc' }}">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/expenses" class="btn btn-default btn-sm">
                                            Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="text-nowrap" id="date-order">
                                            <div class="d-flex justify-content-between">
                                                <div>Tanggal</div>
                                                <div>
                                                    @if (request('date_order') == 'desc')
                                                        <i class="fas fa-angle-down"></i>
                                                    @else
                                                        <i class="fas fa-angle-up"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </th>
                                        <th>Nama Pengeluaran</th>
                                        <th>Jumlah (Rp)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $expense)
                                        <tr>
                                            <td class="col-1">{{ $expenses->firstItem() + $loop->index }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('d-m-Y', $expense->date)->translatedFormat('j M Y') }}</td>
                                            <td>{{ $expense->name }}</td>
                                            <td class="text-right">{{ change_decimal_format_to_currency($expense->amount) }}</td>
                                            <td class="text-nowrap col-1">
                                                <form action="/expenses/{{ $expense->id }}" method="POST" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="/expenses/{{ $expense->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>Tidak ada pengeluaran.</span>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col">
                    {{ $expenses->onEachSide(1)->links() }}
                </div>
            </div>

        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let filterForm = $('#filter-form');

        let dateOrder = $('#date-order');
        dateOrder.on('click', function() {
            let way = $('input[name="date_order"]').val();
            if (way == 'asc') {
                $('input[name="date_order"]').val('desc');
            } else {
                $('input[name="date_order"]').val('asc');
            }
            filterForm.submit();
        });


        // Daterange
        let start = moment();
        @if(request('start_date'))
            startStr = "{{ request('start_date') }}";
            start = moment(startStr, "DD-MM-YYYY");
        @endif
        $('input[name="start_date"]').val(start.format('DD-MM-YYYY'));

        let end = moment();
        @if(request('end_date'))
            endStr = "{{ request('end_date') }}";
            end = moment(endStr, "DD-MM-YYYY");
        @endif
        $('input[name="end_date"]').val(end.format('DD-MM-YYYY'));

        $('#date_range').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Semua waktu': [moment(0), moment().add(5, 'years').endOf('year')]
            },
            locale: {
                format: 'D MMMM YYYY'
            }
        }, function(start, end, label) {
            $('input[name="start_date"]').val(start.format('DD-MM-YYYY'));
            $('input[name="end_date"]').val(end.format('DD-MM-YYYY'));
            filterForm.submit();
        });

        // Sweet alert delete expense
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus pengeluaran ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        $(`.rupiah`).inputmask("currency", {
            radixPoint: ',',
            allowMinus: false,
        });

    });
</script>
@endpush