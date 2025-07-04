<div class="modal fade" id="modal-permission-create" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Role</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('permission.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Masukan Nama" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="data-table2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center" style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a href="#" id="btn-permission-destroy" data-id="{{ $permission->id }}" data-url="{{ route('permission.destroy', $permission->id) }}"><i class="fa fa-trash text-red"></i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#data-table2").DataTable({
                "responsive": true, // Membuat tabel responsif untuk berbagai ukuran layar
                "lengthChange": false, // Menyembunyikan dropdown "Show X entries"
                "autoWidth": false, // Menonaktifkan penyesuaian lebar kolom otomatis
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"], // Mengaktifkan tombol ekspor/visibilitas kolom
                // Opsi kustomisasi lainnya bisa ditambahkan di sini, misalnya:
                "paging": true, // Mengaktifkan pagination
                // "ordering": true, // Mengaktifkan sorting kolom
                // "info": true, // Menampilkan info jumlah entri
                // "searching": true, // Mengaktifkan kolom pencarian
                "language": { // Opsi: Ubah teks menjadi Bahasa Indonesia
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Tidak ada entri",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Awal",
                        "last": "Akhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                    "aria": {
                        "sortAscending": ": aktifkan untuk mengurutkan kolom secara naik",
                        "sortDescending": ": aktifkan untuk mengurutkan kolom secara menurun"
                    }
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        })
    </script>
@endpush
