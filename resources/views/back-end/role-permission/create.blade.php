<div class="modal fade" id="modal-create" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Role</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="check-all-permissions">
                            <label class="custom-control-label" for="check-all-permissions">Check All Permissions</label>
                        </div>
                        <hr>
                        <div class="row">
                            @foreach ($permissions as $key => $permission)
                                <div class="col-md-4">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#check-all-permissions').on('click', function() {
            var isChecked = $(this).is(':checked');
            $('input[name="permissions[]"]').prop('checked', isChecked);
        });

        $('input[name="permissions[]"]').on('click', function() {
            if ($('input[name="permissions[]"]:checked').length == $('input[name="permissions[]"]').length) {
                $('#check-all-permissions').prop('checked', true);
            } else {
                $('#check-all-permissions').prop('checked', false);
            }
        });

        // Reset check-all when modal is hidden (optional, but good practice)
        $('#modal-create').on('hidden.bs.modal', function () {
            $('#check-all-permissions').prop('checked', false);
            $('input[name="permissions[]"]').prop('checked', false);
        });
    });
</script>
@endpush
