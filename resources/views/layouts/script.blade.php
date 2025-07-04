<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.is_select2').select2();
		$('*select[data-selectjs="true"]').select2({
			width: '100%',
        });
		$('*select[data-selectTagjs="true"]').select2({
			width: '100%',
			tags: true
        });
		$('body').Layout('fixLayoutHeight')

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        @if (Session::has('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}'
            })
        @endif

        @if (Session::has('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ Session::get('error') }}'
            })
        @endif

        @if (Session::has('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ Session::get('warning') }}'
            })
        @endif

        // $('#data-table').DataTable();
        $(".number-only").keyup(function(e) {
            var regex = /^[0-9]+$/;
            if (regex.test(this.value) !== true) {
                this.value = this.value.replace(/[^0-9]+/, '');
            }
        });
        $(".currency").on("keyup", function() {
            value = $(this).val().replace(/,/g, '');
            if (!$.isNumeric(value) || value == NaN) {
                $(this).val('0').trigger('change');
                value = 0;
            }
            $(this).val(parseFloat(value, 10).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });
		$(".numbersymbol").keyup(function(e) {
			var regex = /^[0-9(&).,:\-/ X]+$/;
			if (regex.test(this.value) !== true) {
				this.value = this.value.replace(/[^0-9(&).,:\-/ X]+/, '');
			}
		});


      $("#data-table").DataTable({
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
    });
</script>
@stack('scripts')

