<?php

use App\Http\Controllers\Admin\CetakRaportSemesterSantriController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\TapelController;
use App\Http\Controllers\Admin\PondokController;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Guru\NilaiUasController;
use App\Http\Controllers\Admin\TglRaportController;
use App\Http\Controllers\Guru\KirimNilaiController;
use App\Http\Controllers\Guru\LihatNilaiController as GuruLihatNilaiController;
use App\Http\Controllers\Guru\NilaiAbsenController;
use App\Http\Controllers\Admin\MapingMapelController;
use App\Http\Controllers\Guru\NilaiSetoranController;
use App\Http\Controllers\Admin\PembelajaranController;
use App\Http\Controllers\Admin\StatusPenilaianController;
use App\Http\Controllers\Admin\PengelolaanNilaiController;
use App\Http\Controllers\Admin\GuruController as AdminGuruController;
use App\Http\Controllers\Admin\KehadiranSantriController as AdminKehadiranSantriController;
use App\Http\Controllers\Admin\LegerSantriController as AdminLegerSantriController;
use App\Http\Controllers\Admin\NilaiRaportSemesterSantriController as AdminNilaiRaportSemesterSantriController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Guru\DashboardController as GuruDasboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDasboardController;
use App\Http\Controllers\Guru\BobotPenilaianController as GuruBobotPenilaianController;

Route::get('/unauthorized', function () {
    $title = 'Unauthorized';
    return view('errorpage.401', compact('title'));
});

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'store'])->name('login');
Route::post('/settingtapel', [AuthController::class, 'setting_tapel'])->name('setting.tapel');

Route::middleware('isAdmin', 'verified')->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminDasboardController::class, 'index'])->name('admin.dashboard');
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile');
    Route::put('profile/{id}', [AdminProfileController::class, 'update'])->name('admin.updateProfile');
    Route::get('password', [AdminProfileController::class, 'password'])->name('admin.password');
    Route::put('password', [AdminProfileController::class, 'password'])->name('admin.password');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    //user
    Route::resource('user', UserController::class)->only(['index', 'store', 'update']);
    Route::get('user/export', [UserController::class, 'export'])->name('user.export');

    //pondok
    // Route::resource('sekolah', PondokController::class)->only(['index', 'update']);
    Route::get('pondok', [PondokController::class, 'index'])->name('admin.pondok');
    Route::put('pondok/{id}', [PondokController::class, 'update'])->name('admin.pondok.update');

    //guru
    Route::get('guru/export', [AdminGuruController::class, 'export'])->name('guru.export');
    Route::get('guru/import', [AdminGuruController::class, 'format_import'])->name('guru.format_import');
    Route::post('guru/import', [AdminGuruController::class, 'import'])->name('guru.import');
    Route::resource('guru', AdminGuruController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::resource('tapel', TapelController::class)->only(['index', 'store']);

    //kelas
    Route::post('kelas/anggota', [KelasController::class, 'store_anggota'])->name('kelas.anggota');
    Route::delete('kelas/anggota/{anggota}', [KelasController::class, 'delete_anggota'])->name('kelas.anggota.delete');
    Route::resource('kelas', KelasController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    //santri
    Route::get('santri/export', [SantriController::class, 'export'])->name('santri.export');
    Route::get('santri/import', [SantriController::class, 'format_import'])->name('santri.format_import');
    Route::post('santri/import', [SantriController::class, 'import'])->name('santri.import');
    Route::post('santri/registrasi', [SantriController::class, 'registrasi'])->name('santri.registrasi');
    Route::resource('santri', SantriController::class)->only(['index', 'store', 'update', 'destroy']);

    //mapel
    Route::get('mapel/import', [MapelController::class, 'format_import'])->name('mapel.format_import');
    Route::post('mapel/import', [MapelController::class, 'import'])->name('mapel.import');
    Route::resource('mapel', MapelController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('pembelajaran/export', [PembelajaranController::class, 'export'])->name('pembelajaran.export');
    Route::get('/pembelajaran/settings', [PembelajaranController::class, 'showSettings'])->name('pembelajaran.settings.show');
    Route::post('/pembelajaran/settings', [PembelajaranController::class, 'processSettings'])->name('pembelajaran.settings.process');
    Route::resource('pembelajaran', PembelajaranController::class)->only(['index', 'store']);

    //mapping mapel
    Route::resource('mapping', MapingMapelController::class)->only(['index', 'store']);
    Route::resource('tglraport', TglRaportController::class)->only(['index', 'store', 'update', 'destroy']);

    //nilai
    Route::resource('statuspenilaian', StatusPenilaianController::class)->only(['index', 'store']);
    Route::resource('pengelolaannilai', PengelolaanNilaiController::class)->only(['index', 'store']);

    Route::resource('kehadiran', AdminKehadiranSantriController::class)->only(['index', 'create', 'store']);
    Route::resource('leger', AdminLegerSantriController::class)->only(['index', 'show', 'store']);
    Route::get('leger/cetak/{kelas_id}', [AdminLegerSantriController::class, 'cetak'])->name('leger.cetak');
    Route::resource('nilairaport', AdminNilaiRaportSemesterSantriController::class)->only(['index', 'store']);
    Route::resource('cetakraport', CetakRaportSemesterSantriController::class)->only(['index', 'store', 'show']);
    Route::post('cetakraport/bulk', [CetakRaportSemesterSantriController::class, 'bulk'])->name('cetakraport.bulk');
});


Route::middleware('isGuru', 'verified')->prefix('guru')->group(function () {
    Route::get('dashboard', [GuruDasboardController::class, 'index'])->name('guru.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    //bobot penilaian
    Route::resource('bobot', GuruBobotPenilaianController::class)->only(['index', 'store', 'update']);

    //nilai
    Route::resource('nilaiabsen', NilaiAbsenController::class)->only(['index', 'create', 'store', 'update']);
    Route::resource('nilaisetoran', NilaiSetoranController::class)->only(['index', 'create', 'store', 'update']);
    Route::resource('nilaiuas', NilaiUasController::class)->only(['index', 'create', 'store', 'update']);
    Route::resource('kirimnilai', KirimNilaiController::class)->only(['index', 'create', 'store']);
    Route::resource('lihatnilai', GuruLihatNilaiController::class)->only(['index', 'create']);
});
