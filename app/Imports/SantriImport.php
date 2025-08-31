<?php

namespace App\Imports;

use App\Models\Santri;
use App\Models\User;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class SantriImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            if ($key >= 8 && count($row) >= 18) {
                try {
                    // Parsing tanggal lahir khusus untuk format dd/mm/yyyy
                    $tanggalRaw = $row[5];
                    $tanggal_lahir = null;

                    if (is_numeric($tanggalRaw)) {
                        // Format Excel numeric
                        $tanggal_lahir = Date::excelToDateTimeObject($tanggalRaw)->format('Y-m-d');
                    } else {
                        // Coba format dd/mm/yyyy terlebih dahulu
                        $parts = explode('/', $tanggalRaw);
                        if (count($parts) === 3) {
                            // Pastikan format dd/mm/yyyy
                            if (strlen($parts[0]) === 2 && strlen($parts[1]) === 2 && strlen($parts[2]) === 4) {
                                $tanggal_lahir = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                            }
                        }
                        
                        // Jika masih gagal, coba strtotime
                        if (!$tanggal_lahir) {
                            $timestamp = strtotime(str_replace('/', '-', $tanggalRaw));
                            if ($timestamp !== false) {
                                $tanggal_lahir = date('Y-m-d', $timestamp);
                            }
                        }
                    }

                    // Fallback jika parsing gagal
                    if (!$tanggal_lahir) {
                        Log::warning("Format tanggal tidak valid: " . $tanggalRaw);
                        $tanggal_lahir = '2000-01-01';
                    }

                    // Buat user dan santri
                    $user = User::create([
                        'username' => strtolower(str_replace(' ', '', $row[3] . $row[1])),
                        'password' => bcrypt('123456'),
                        'role' => 3,
                        'status' => true,
                    ]);

                    Santri::create([
                        'user_id' => $user->id,
                        'nis' => $row[1],
                        'nisn' => $row[2],
                        'nama_lengkap' => strtoupper($row[3]),
                        'tempat_lahir' => $row[4],
                        'tanggal_lahir' => $tanggal_lahir,
                        'jenis_kelamin' => $row[6],
                        'jenis_pendaftaran' => $row[7],
                        'status_dalam_keluarga' => $row[8],
                        'anak_ke' => $row[9],
                        'alamat' => $row[10],
                        'nomor_hp' => $row[11],
                        'nama_ayah' => $row[12],
                        'nama_ibu' => $row[13],
                        'pekerjaan_ayah' => $row[14],
                        'pekerjaan_ibu' => $row[15],
                        'nama_wali' => $row[16],
                        'pekerjaan_wali' => $row[17],
                        'avatar' => 'default.png',
                        'status' => 1,
                    ]);

                } catch (\Exception $e) {
                    Log::error("Error import baris ke-$key: " . $e->getMessage());
                }
            }
        }
    }
}