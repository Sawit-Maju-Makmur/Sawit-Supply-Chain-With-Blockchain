<?php

namespace Tests\Feature\Supplier;

use App\Models\Document;
use App\Models\Lahan;
use App\Models\SelfAssessment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EndToEndSprintTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_sprint_1_flow_from_registration_to_management_review(): void
    {
        Storage::fake('local');

        // Pastikan role Spatie ada
        Role::findOrCreate('pemasok', 'web');
        Role::findOrCreate('management', 'web');

        // 1. Pemasok Register (PBI 1)
        $registerResponse = $this->post('/register', [
            'name' => 'Pak Joko Widodo',
            'email' => 'joko@sawit.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'pemasok',
        ]);

        $registerResponse->assertRedirect(route('dashboard'));

        $user = User::where('email', 'joko@sawit.test')->firstOrFail();
        $this->assertTrue($user->hasRole('pemasok'));

        // Pastikan profil pemasok otomatis dibuat (PBI 4 auto-creation)
        $supplier = $user->supplier;
        $this->assertNotNull($supplier);
        $this->assertSame('Pak Joko Widodo', $supplier->name);

        // 2. Pemasok Update Profil Usaha (PBI 4)
        $this->actingAs($user)
            ->put(route('pemasok.profile.update'), [
                'name' => 'UD Sawit Berkah Joko',
                'type' => 'mandiri',
                'nik' => '1234567890123456',
                'npwp' => '12.345.678.9-012.000',
                'nib' => '9876543210123',
                'address' => 'Jl. Kebun Sawit Km 12',
                'regency' => 'Kabupaten Kampar',
                'phone' => '081234567890',
                'email' => 'joko@sawit.test',
            ])
            ->assertRedirect(route('pemasok.profile.edit'))
            ->assertSessionHas('success');

        $supplier->refresh();
        $this->assertSame('UD Sawit Berkah Joko', $supplier->name);
        $this->assertSame('9876543210123', $supplier->nib);
        $this->assertSame('Kabupaten Kampar', $supplier->regency);

        // 3. Pemasok Input Data Lahan dengan Poligon (PBI 5 & PBI 6)
        $polygonCoords = [
            [0.512345, 101.456789],
            [0.513345, 101.457789],
            [0.514345, 101.456789],
        ];

        $this->actingAs($user)
            ->post(route('lahan.store'), [
                'nama_kebun' => 'Kebun Sentosa 1',
                'lokasi' => 'Desa Makmur, Kampar',
                'latitude' => 0.513345,
                'longitude' => 101.456789,
                'luas_ha' => 7.5,
                'status_lahan' => 'SHM',
                'polygon_coordinates' => json_encode($polygonCoords),
            ])
            ->assertRedirect(route('lahan.index'))
            ->assertSessionHas('success');

        $lahan = Lahan::where('nama_kebun', 'Kebun Sentosa 1')->firstOrFail();
        $this->assertSame($supplier->id, $lahan->supplier_id);
        $this->assertCount(3, $lahan->polygon_coordinates);

        // 4. Pemasok Upload Dokumen Legalitas untuk Lahan (PBI 7)
        $this->actingAs($user)
            ->post(route('pemasok.documents.store', $lahan), [
                'type' => 'SHM',
                'expires_at' => today()->addYears(5)->toDateString(),
                'file' => UploadedFile::fake()->create('shm_kebun_sentosa.pdf', 200, 'application/pdf'),
            ])
            ->assertRedirect(route('pemasok.documents.index'))
            ->assertSessionHas('success');

        $doc = Document::where('lahan_id', $lahan->id)->firstOrFail();
        $this->assertSame('pending', $doc->status);
        $this->assertSame('shm_kebun_sentosa.pdf', $doc->original_filename);

        // 5. Pemasok Mengisi Self-Assessment Sustainability & Traceability (PBI 8)
        $answers = [
            'legalitas_lahan' => 'ya',
            'koordinat_kebun' => 'ya',
            'catatan_panen' => 'ya',
            'nota_pengiriman' => 'ya',
            'asal_bibit' => 'ya',
            'tanpa_bakar' => 'ya',
            'tanpa_deforestasi' => 'ya',
            'pestisida_aman' => 'ya',
            'pupuk_berimbang' => 'ya',
            'sempadan_air' => 'ya',
            'pengelolaan_limbah' => 'ya',
            'apd_pekerja' => 'ya',
            'tanpa_pekerja_anak' => 'ya',
            'anggota_kelompok' => 'ya',
            'sertifikasi' => 'ya',
        ];

        $this->actingAs($user)
            ->post(route('assessments.store'), [
                'nama_petani' => 'Pak Joko Widodo',
                'nama_kebun' => 'Kebun Sentosa 1',
                'lokasi' => 'Desa Makmur, Kampar',
                'luas_lahan' => 7.5,
                'tahun_tanam' => 2019,
                'catatan' => 'Lahan sudah tersertifikasi',
                'answers' => $answers,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $assessment = SelfAssessment::where('supplier_id', $supplier->id)->firstOrFail();
        $this->assertSame('Tinggi', $assessment->level);
        $this->assertEquals(100.0, (float) $assessment->percentage);

        // 6. Management Melihat Detail Pemasok (PBI 9)
        $mgmtUser = User::factory()->create(['role' => 'management', 'name' => 'Manager Sawit']);
        $mgmtUser->assignRole('management');

        $this->actingAs($mgmtUser)
            ->get(route('management.suppliers.show', $supplier))
            ->assertOk()
            ->assertSee('UD Sawit Berkah Joko')
            ->assertSee('9876543210123') // NIB
            ->assertSee('Kebun Sentosa 1') // Lahan
            ->assertSee('Ada (3 titik)') // Poligon
            ->assertSee('Menunggu 1') // Status dokumen
            ->assertSee('Level: Tinggi') // Self-assessment
            ->assertSee('100%');
    }
}
