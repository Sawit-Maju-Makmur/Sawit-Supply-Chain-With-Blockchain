<?php

/*
 * SCRUM-25 - Daftar pertanyaan self-assessment sustainability & traceability.
 * Jawaban: ya = 2 poin, sebagian = 1 poin, tidak = 0 poin.
 */
return [
    'options' => [
        'ya'        => ['label' => 'Ya',        'score' => 2],
        'sebagian'  => ['label' => 'Sebagian',  'score' => 1],
        'tidak'     => ['label' => 'Tidak',     'score' => 0],
    ],

    'categories' => [
        'traceability' => [
            'title' => 'A. Ketertelusuran (Traceability)',
            'questions' => [
                'legalitas_lahan'   => 'Kebun memiliki legalitas lahan (SHM/STDB/surat kepemilikan yang sah).',
                'koordinat_kebun'   => 'Titik koordinat/batas kebun sudah dipetakan dan terdokumentasi.',
                'catatan_panen'     => 'Hasil panen TBS dicatat secara rutin (tanggal, jumlah, asal blok).',
                'nota_pengiriman'   => 'Setiap pengiriman TBS memiliki nota/dokumen yang dapat dilacak.',
                'asal_bibit'        => 'Asal bibit/benih sawit diketahui dan bersertifikat.',
            ],
        ],
        'lingkungan' => [
            'title' => 'B. Praktik Ramah Lingkungan',
            'questions' => [
                'tanpa_bakar'       => 'Pembukaan/pembersihan lahan dilakukan tanpa pembakaran.',
                'tanpa_deforestasi' => 'Tidak ada pembukaan hutan/lahan gambut baru untuk kebun.',
                'pestisida_aman'    => 'Pestisida/herbisida digunakan sesuai anjuran dan tidak memakai bahan terlarang.',
                'pupuk_berimbang'   => 'Pemupukan dilakukan secara berimbang sesuai dosis rekomendasi.',
                'sempadan_air'      => 'Area sempadan sungai/sumber air dilindungi (tidak ditanami/diracun).',
                'pengelolaan_limbah'=> 'Limbah (kemasan pestisida, oli, pelepah) dikelola dengan benar.',
            ],
        ],
        'sosial' => [
            'title' => 'C. Sosial & Kepatuhan',
            'questions' => [
                'apd_pekerja'       => 'Pekerja kebun menggunakan alat pelindung diri (APD).',
                'tanpa_pekerja_anak'=> 'Tidak mempekerjakan anak di bawah umur.',
                'anggota_kelompok'  => 'Petani tergabung dalam koperasi/kelompok tani.',
                'sertifikasi'       => 'Memiliki atau sedang mengikuti sertifikasi (ISPO/RSPO/ISCC).',
            ],
        ],
    ],

    // Batas persentase tingkat kepatuhan
    'levels' => [
        ['min' => 80, 'label' => 'Tinggi',  'class' => 'badge-high',   'note' => 'Praktik sudah sangat baik, pertahankan dan siapkan sertifikasi.'],
        ['min' => 60, 'label' => 'Sedang',  'class' => 'badge-medium', 'note' => 'Cukup baik, perbaiki poin yang masih "Sebagian" atau "Tidak".'],
        ['min' => 0,  'label' => 'Rendah',  'class' => 'badge-low',    'note' => 'Perlu pendampingan dan perbaikan praktik secara bertahap.'],
    ],
];
