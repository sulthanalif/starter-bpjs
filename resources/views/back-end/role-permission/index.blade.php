@extends('layouts.main')
@section('title', 'Role Permission')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Role & Permission</h1>
            </div>

        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    @can('role-delete')
                                        <div class="mt-2">
                                            <a href="#" class="hidden" id="btn-destroy"><i class="fa fa-trash text-red"></i></a>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-md-8 float-right">
                                @can('role-create')
                                    <button class="btn btn-primary float-right" id="btn-create" data-toggle="modal" data-target="#modal-create"><i class="nav-icon fa fa-plus"></i>  Tambah role</button>
                                @endcan
                                @can('manage-permissions')
                                    <button class="btn btn-primary float-right mr-2" id="btn-permission">  Permission</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="data-table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center" style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td class="text-center text-nowrap">
                                            @can('role-update')
                                            <a href="#" class="edit-role" data-url="{{ route('role-permission.update', $role->id) }}" data-id="{{ $role->id }}" data-get="{{ route('role-permission.show', $role->id) }}">
                                                <i class="fa fa-pen mr-3 text-dark"></i>
                                            </a>
                                            @endcan
                                            @can('role-delete')
                                                <a href="#"  id="btn-destroy" data-url="{{ route('role.destroy', $role->id) }}" data-id="{{ $role->id }}"><i class="fa fa-trash text-red"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('back-end.role-permission.create')
@include('back-end.role-permission.edit')
@include('back-end.role-permission.permission-create')
@endsection

@push('scripts')
<script>
    // Pastikan variabel ini diisi oleh controller Anda saat me-render view index.
    // Contoh di controller: return view('back-end.master.role-permission.index', ['roles' => $roles, 'permissions' => $allSystemPermissions]);
    var all_permissions_global = @json($permissions ?? []); // Default ke array kosong jika $permissions tidak di-set
</script>
<script>
    $(document).ready(function() {

        $('#btn-permission').on('click', function() {
            $('#modal-permission-create').modal('show');
        });

        $('#btn-permission-destroy').click(function(e) {
            e.preventDefault();
            var arrId = $(this).data('id');
            var url = $(this).data('url');
            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success").then(function(){
                                location.reload();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data. Silakan coba lagi.", "error").then(function(){
                                location.reload();
                            });
                        }
                    })
                }
            })
        })


        $('#btn-create').on('click', function() {
            $('#modal-create').modal('show');
            $('#name').val('');
            $('#permissions-container').html('');
            $('#check-all-permissions').prop('checked', false);

        });



        $('#check-all-permissions-edit').on('click', function() {
            var isChecked = $(this).is(':checked');
            $('#permissions-edit-container input[name="permissions[]"]').prop('checked', isChecked);
        });

        // Individual checkbox click for Edit Modal
        $('#permissions-edit-container').on('click', 'input[name="permissions[]"]', function() {
            if ($('#permissions-edit-container input[name="permissions[]"]:checked').length == $('#permissions-edit-container input[name="permissions[]"]').length) {
                $('#check-all-permissions-edit').prop('checked', true);
            } else {
                $('#check-all-permissions-edit').prop('checked', false);
            }
        });
        // Handle Edit Role
        $('#data-table tbody').on('click', '.edit-role', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url_update = $(this).data('url');
            var url_show = $(this).data('get');

            $('#form-edit').attr('action', url_update);
            $('#modal-edit').modal('show');

            // Reset previous state
            $('#name_edit').val('');
            $('#permissions-edit-container').html('');
            $('#check-all-permissions-edit').prop('checked', false);

            $.ajax({
                url: url_show,
                type: 'GET',
                success: function(response) {
                    // Berdasarkan struktur JSON yang diberikan:
                    // response.data berisi detail role, mis. {id, name}
                    // response.permissions berisi permission yang sudah di-assign ke role, mis. [{id, name, pivot}, ...]
                    if (response.status) { // Asumsi response.data selalu ada jika status true
                        $('#name_edit').val(response.data.name);

                        var permissionsHtml = '';
                        // Ambil ID dari permission yang sudah di-assign ke role ini
                        var assignedPermissionIds = response.permissions.map(p => p.id);

                        var totalSystemPermissions = 0;
                        var checkedPermissionsCount = 0;

                        // Iterasi melalui semua permission yang ada di sistem (dari variabel global)
                        if (Array.isArray(all_permissions_global)) {
                            totalSystemPermissions = all_permissions_global.length;
                            all_permissions_global.forEach(function(permission) {
                                var isChecked = assignedPermissionIds.includes(permission.id);
                                if (isChecked) {
                                    checkedPermissionsCount++;
                                }
                                permissionsHtml += `
                                    <div class="col-md-4">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" name="permissions[]" id="permission-edit-${permission.id}" value="${permission.id}" ${isChecked ? 'checked' : ''}>
                                            <label class="custom-control-label" for="permission-edit-${permission.id}">${permission.name}</label>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            console.error("Variabel all_permissions_global bukan array atau tidak terdefinisi. Periksa data dari controller.");
                            }

                        $('#permissions-edit-container').html(permissionsHtml);

                        // Update status checkbox "Check All Permissions"
                        if (totalSystemPermissions > 0 && checkedPermissionsCount === totalSystemPermissions) {
                            $('#check-all-permissions-edit').prop('checked', true);
                        } else {
                            $('#check-all-permissions-edit').prop('checked', false);
                        }
                    } else {
                        Swal.fire("Error!", response.message || "Gagal mengambil data role.", "error");
                    }
                },
                error: function(xhr) {
                    Swal.fire("Error!", "Terjadi kesalahan saat mengambil data: " + xhr.statusText, "error");
                }
            });
        });

        // Reset form and checkboxes when edit modal is hidden
        $('#modal-edit').on('hidden.bs.modal', function () {
            $('#form-edit')[0].reset(); // Reset form fields
            $('#permissions-edit-container').html(''); // Clear permissions
            $('#check-all-permissions-edit').prop('checked', false); // Uncheck "check all"
        });

        $('#data-table tbody').on('click', 'a#btn-destroy', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url');
            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed){
                    $.ajax({
                        url: url, // Menggunakan URL yang sudah dikonstruksi dengan benar
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            // "id": arrId // Mungkin tidak diperlukan jika ID sudah ada di URL dan controller mengambil dari route parameter.
                                         // Jika controller Anda secara spesifik membutuhkan 'id' di body POST, biarkan ini.
                        },
                        success: function (response) {
                            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success").then(function(){
                                location.reload();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {

                            Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data. Silakan coba lagi.", "error").then(function(){
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

    });
</script>
@endpush
