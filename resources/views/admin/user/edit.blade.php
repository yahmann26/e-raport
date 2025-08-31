<!-- Modal Edit -->
<div class="modal fade" id="modal-edit{{ $user->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit {{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap</label>
                        <div class="col-sm-9">
                            @switch($user->role)
                                @case(1)
                                    <input type="text" class="form-control"
                                        value="{{ optional($user->admin)->nama_lengkap }}" readonly>
                                @break

                                @case(2)
                                    <input type="text" class="form-control" value="{{ optional($user->guru)->nama_lengkap }}"
                                        readonly>
                                @break

                                @case(3)
                                    <input type="text" class="form-control"
                                        value="{{ optional($user->santri)->nama_lengkap }}" readonly>
                                @break

                                @case(4)
                                    <input type="text" class="form-control"
                                        value="{{ optional($user->kepala_sekolah)->nama_lengkap }}" readonly>
                                @break

                                @case(5)
                                    <input type="text" class="form-control"
                                        value="{{ optional($user->wakilkurikulum)->nama_lengkap }}" readonly>
                                @break

                                @default
                                    <input type="text" class="form-control" value="-" readonly>
                            @endswitch
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" placeholder="Password Baru">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-3 col-form-label">Status Akun</label>
                        <div class="col-sm-9 pt-1">
                            <label class="radio-inline mr-3">
                                <input type="radio" name="status" value="1" {{ $user->status ? 'checked' : '' }}
                                    required> Aktif
                            </label>
                            <label class="radio-inline mr-3">
                                <input type="radio" name="status" value="0"
                                    {{ !$user->status ? 'checked' : '' }} required> Non Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Edit -->
