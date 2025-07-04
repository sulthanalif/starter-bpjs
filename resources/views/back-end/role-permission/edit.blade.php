<div class="modal fade" id="modal-edit" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Role</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form action="" method="POST" class="form-horizontal" id="form-edit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name_edit" class="form-label">Nama</label>
                                <input type="text" name="name_edit" class="form-control @error('name_edit') is-invalid @enderror" id="name_edit" placeholder="Masukan Nama" value="">
                                @error('name_edit')
                                    <div class="invalid-feedback" id="name_edit_error_placeholder">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Permissions</label>
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input" id="check-all-permissions-edit">
                            <label class="custom-control-label" for="check-all-permissions-edit">Check All Permissions</label>
                        </div>
                        <hr>
                        <div class="row" id="permissions-edit-container">

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
