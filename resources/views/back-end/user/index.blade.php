@extends('layouts.main')
@section('title', 'Users')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Users</h1>
            </div>

        </div>
    </div><!-- /.container-fluid -->
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
                                    @can('user-delete')
                                        <div class="mt-2">
                                            <a href="#" class="hidden" id="btn-destroy"><i class="fa fa-trash text-red"></i></a>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="col-md-8">
                                @can('user-create')
                                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-create"><i class="nav-icon fa fa-plus"></i>  Tambah User</button>
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
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="text-center" style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->getRoleNames()->first() ?? '-' }}</td></td>
                                        <td class="text-center text-nowrap">
                                            @can('user-update')
                                            <a href="#" class="edit" id="btn-edit" data-url="{{ route('user.update', $user->id) }}" data-id="{{ $user->id }}" data-get="{{ route('user.show', $user->id) }}">
                                                <i class="fa fa-pen mr-3 text-dark"></i>
                                            </a>
                                            @endcan
                                            @can('user-delete')
                                                <a href="#" id="btn-destroy" data-id="{{ $user->id }}" data-url="{{ route('user.destroy', $user->id) }}"><i class="fa fa-trash text-red"></i></a>
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

@include('back-end.user.create')
@include('back-end.user.edit')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#data-table tbody').on('click', 'a#btn-destroy', function (e) {
            e.preventDefault(); // Mencegah aksi default dari link anchor
            var arrId = $(this).data('id');
            // Pastikan route 'user.destroy' didefinisikan untuk menerima parameter ID.
            // Ganti 'id' dengan nama parameter yang benar jika berbeda (misalnya 'user').
            var url = $(this).data('url');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning", // Menggunakan 'icon' bukan 'type' untuk SweetAlert2
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                // closeOnConfirm: false, // Tidak diperlukan untuk SweetAlert2 modern dengan .then()
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
                            // Server akan melakukan redirect dan menampilkan flash message.
                            // Cukup reload halaman untuk melihat perubahan dan pesan.
                            // Swal di sini bersifat opsional untuk feedback instan sebelum reload.
                            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success").then(function(){
                                location.reload();
                            });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            // Error pada request AJAX (misal server error 500, 403).
                            // Server mungkin juga sudah menyiapkan flash message error saat redirect.
                            // console.log(xhr.responseText);

                            Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus data. Silakan coba lagi.", "error").then(function(){
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        $('#data-table tbody').on('click', '#btn-edit', function () {
            var id = $(this).data('id');
            var url = $(this).data('url');
            var url_hit = $(this).data('get');
            $.ajax({
                url: url_hit,
                type: 'GET',
            })
            .done(function (response) {
                if(response && response.status){ // Pastikan response dan response.status ada
                    $('#name_edit').val(response.data.name);
                    $('#email_edit').val(response.data.email);
                    // $('#password_edit').val(response.data.password);

                    let option_role = "";
                    // Periksa apakah response.roles adalah array sebelum iterasi
                    if (response.roles && Array.isArray(response.roles)) {
                        for (let i = 0; i < response.roles.length; i++) {
                            let selected_role = response.roles[i].selected ? "selected" : ""; // Atribut selected tidak memerlukan nilai true/false
                            option_role += "<option value='"+response.roles[i].id+"' "+selected_role+">"+response.roles[i].name+"</option>";
                        }
                    } else {
                        console.warn("Data roles tidak ditemukan atau formatnya salah dalam respons:", response.roles);
                        // Anda bisa menambahkan opsi default jika tidak ada role
                        // option_role = "<option value=''>Tidak ada role tersedia</option>";
                    }
                    $('#role_id_edit').html(option_role); // Perbaiki selector ke ID

					let option_store = "";
                    // Periksa apakah response.stores adalah array sebelum iterasi (jika digunakan)
                    if (response.stores && Array.isArray(response.stores)) {
                        for (let i = 0; i < response.stores.length; i++) {
                            let selected_store = response.stores[i].selected ? "selected" : "";
                            option_store += "<option value='"+response.stores[i].code+"' "+selected_store+">"+response.stores[i].name+"</option>";
                        }
                        // Jika ada elemen untuk store, perbarui di sini. Contoh: $('#store_id_edit').html(option_store);
                    }
                    $("#form-edit").attr('action', url);
                    $('#modal-edit').modal('show');
                } else {
                    console.error("Gagal memuat data user atau format respons tidak valid:", response);
                    Swal.fire("Gagal!", "Tidak dapat memuat data pengguna untuk diedit.", "error");
                }
            })
            .fail(function () {
                console.log("error");
            });
        });

    });
</script>
@endpush
