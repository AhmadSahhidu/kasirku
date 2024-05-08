@extends('component.layout.app')
@push('style')
    <link href="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('component.partial.alert')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Hak Akses</h1>
        <a href="{{ route('users.create_users') }}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Data</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Akses User</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('users.save_permission') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row">
                    <input type="hidden" name="user_id" value="{{ request('idUser') }}" />
                    <div class="col-md-12">
                        <div class="card shadow mb-4">
                            @foreach ($groups as $index => $itemsGroup)
                                <!-- Card Header - Accordion -->
                                <a href="#collapse-{{ $index }}" class="d-block card-header py-3"
                                    data-toggle="collapse" role="button" aria-expanded="true"
                                    aria-controls="collapse-{{ $index }}">
                                    <h6
                                        class="m-0 text-capitalize
                                font-weight-bold text-primary">
                                        {{ $itemsGroup->group }}</h6>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse" id="collapse-{{ $index }}">
                                    <div class="card-body">
                                        @php
                                            $dataPermission = permissionByGroup($itemsGroup->group);
                                        @endphp

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input check-all-group-permissions"
                                                        data-index="{{ $index }}" type="checkbox" value="">
                                                    <label class="form-check-label" for="check-all-group-permissions">
                                                        Semua
                                                    </label>
                                                </div>
                                            </div>
                                            @foreach ($dataPermission as $indexItems => $items)
                                                <div class="col-md-12 col-xl-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $items->id }}" name="permission[]"
                                                            {{ checkPermission($items->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            {{ $items->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('./assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('./assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('./assets/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.check-all-group-permissions').on('click', function(e) {
                const index = $(this).data('index');
                const checkboxes = $('#collapse-' + index).find('input[type=checkbox]');
                checkboxes.prop('checked', $(this).is(':checked'));
            });
        })
    </script>
@endpush
