<?php
// Simpan token API di environment variable atau file konfigurasi
// Contoh: $token = getenv('FONNTE_API_TOKEN');
$token = 'YOUR_API_TOKEN'; // Ganti dengan token Anda

// Daftar nama surat berdasarkan nomor surat
$daftarSurat = [
    1 => "Al-Fatihah",
    2 => "Al-Baqarah",
    3 => "Ali Imran",
    4 => "An-Nisa",
    5 => "Al-Maidah",
    6 => "Al-An'am",
    7 => "Al-A'raf",
    8 => "Al-Anfal",
    9 => "At-Taubah",
    10 => "Yunus",
    11 => "Hud",
    12 => "Yusuf",
    13 => "Ar-Ra'd",
    14 => "Ibrahim",
    15 => "Al-Hijr",
    16 => "An-Nahl",
    17 => "Al-Isra",
    18 => "Al-Kahf",
    19 => "Maryam",
    20 => "Taha",
    21 => "Al-Anbiya",
    22 => "Al-Hajj",
    23 => "Al-Mu'minun",
    24 => "An-Nur",
    25 => "Al-Furqan",
    26 => "Asy-Syu'ara",
    27 => "An-Naml",
    28 => "Al-Qasas",
    29 => "Al-Ankabut",
    30 => "Ar-Rum",
    31 => "Luqman",
    32 => "As-Sajdah",
    33 => "Al-Ahzab",
    34 => "Saba",
    35 => "Fatir",
    36 => "Yasin",
    37 => "As-Saffat",
    38 => "Sad",
    39 => "Az-Zumar",
    40 => "Ghafir",
    41 => "Fussilat",
    42 => "Asy-Syura",
    43 => "Az-Zukhruf",
    44 => "Ad-Dukhan",
    45 => "Al-Jasiyah",
    46 => "Al-Ahqaf",
    47 => "Muhammad",
    48 => "Al-Fath",
    49 => "Al-Hujurat",
    50 => "Qaf",
    51 => "Adz-Dzariyat",
    52 => "At-Tur",
    53 => "An-Najm",
    54 => "Al-Qamar",
    55 => "Ar-Rahman",
    56 => "Al-Waqi'ah",
    57 => "Al-Hadid",
    58 => "Al-Mujadilah",
    59 => "Al-Hasyr",
    60 => "Al-Mumtahanah",
    61 => "As-Saff",
    62 => "Al-Jumu'ah",
    63 => "Al-Munafiqun",
    64 => "At-Taghabun",
    65 => "At-Talaq",
    66 => "At-Tahrim",
    67 => "Al-Mulk",
    68 => "Al-Qalam",
    69 => "Al-Haqqah",
    70 => "Al-Ma'arij",
    71 => "Nuh",
    72 => "Al-Jinn",
    73 => "Al-Muzzammil",
    74 => "Al-Muddaththir",
    75 => "Al-Qiyamah",
    76 => "Al-Insan",
    77 => "Al-Mursalat",
    78 => "An-Naba",
    79 => "An-Nazi'at",
    80 => "Abasa",
    81 => "At-Takwir",
    82 => "Al-Infitar",
    83 => "Al-Mutaffifin",
    84 => "Al-Insyiqaq",
    85 => "Al-Buruj",
    86 => "At-Tariq",
    87 => "Al-A'la",
    88 => "Al-Ghasyiyah",
    89 => "Al-Fajr",
    90 => "Al-Balad",
    91 => "Asy-Syams",
    92 => "Al-Lail",
    93 => "Ad-Duha",
    94 => "Al-Insyirah",
    95 => "At-Tin",
    96 => "Al-'Alaq",
    97 => "Al-Qadr",
    98 => "Al-Bayyinah",
    99 => "Az-Zalzalah",
    100 => "Al-'Adiyat",
    101 => "Al-Qari'ah",
    102 => "At-Takathur",
    103 => "Al-'Asr",
    104 => "Al-Humazah",
    105 => "Al-Fil",
    106 => "Quraisy",
    107 => "Al-Ma'un",
    108 => "Al-Kausar",
    109 => "Al-Kafirun",
    110 => "An-Nasr",
    111 => "Al-Lahab",
    112 => "Al-Ikhlas",
    113 => "Al-Falaq",
    114 => "An-Nas",
];

// Membaca file JSON
$file = 'data/tahfidz_db.json'; // Gunakan slash untuk kompatibilitas lintas platform
if (file_exists($file)) {
    $json = file_get_contents($file);
    $data = json_decode($json, true);

    // Validasi data JSON
    if (isset($data['guru']) && is_array($data['guru']) && isset($data['siswa']) && is_array($data['siswa'])) {
        $guruList = $data['guru']; // Daftar guru
        $siswaList = $data['siswa']; // Daftar siswa
        $kelasList = array_unique(array_column($siswaList, 'kelas')); // Ambil daftar kelas unik
    } else {
        $guruList = [];
        $siswaList = [];
        $kelasList = [];
        echo "<div class='alert alert-warning'>Data guru atau siswa tidak valid.</div>";
    }
} else {
    $guruList = [];
    $siswaList = [];
    $kelasList = [];
    echo "<div class='alert alert-danger'>File JSON tidak ditemukan.</div>";
}

// Proses form submission
if (isset($_POST["submit"])) {
    // Validasi input
    $kelas = htmlspecialchars($_POST["kelas"] ?? '');
    $siswaId = intval($_POST["siswa"] ?? 0); // ID siswa
    $suratId = intval($_POST["surat"] ?? 0); // ID surat
    $ayat = htmlspecialchars($_POST["ayat"] ?? ''); // Multi-ayat (contoh: 1-5 atau 1,2,3)
    $baris = htmlspecialchars($_POST["baris"] ?? ''); // Kolom baris
    $keterangan = htmlspecialchars($_POST["keterangan"] ?? '');
    $whatsapp = preg_replace('/[^0-9]/', '', $_POST["whatsapp"] ?? ''); // Hanya angka
    $token = htmlspecialchars($_POST["token"] ?? '');
    $menghafal = htmlspecialchars($_POST["menghafal"] ?? ''); // Menghafal atau tidak
    $alasan = htmlspecialchars($_POST["alasan"] ?? ''); // Alasan (opsional)


    // Cari nama siswa berdasarkan ID
    $namaSiswa = '';
    foreach ($siswaList as $siswa) {
        if ($siswa['id'] == $siswaId) {
            $namaSiswa = $siswa['nama'];
            break;
        }
    }

    // Cari nama surat berdasarkan ID
    $namaSurat = $daftarSurat[$suratId] ?? 'Surat Tidak Diketahui';

    // Cek apakah semua field terisi (kecuali alasan)
    if (empty($kelas) || empty($siswaId) || empty($suratId) || empty($ayat) || empty($baris) || empty($keterangan) || empty($whatsapp) || empty($token) || empty($namaSiswa) || empty($menghafal)) {
        echo "<div class='alert alert-danger'>Semua field harus diisi (kecuali alasan).</div>";
    } else {
        // Format pesan berdasarkan pilihan menghafal atau tidak
        if ($menghafal === 'ya') {
            $message = "Alhamdulillah ananda $namaSiswa sudah menambah ziyadah hafalan baru surat $namaSurat ayat $ayat sebanyak $baris baris. $keterangan";
        } else {
            $message = "Assalamu'alaikum ayah/bunda dari ananda $namaSiswa, seharusnya hari ini ananda menyetorkan hafalan baru surat $namaSurat ayat $ayat, namun qodarulloh ananda $alasan. $keterangan";
        }

        // Kirim pesan menggunakan API Fonnte
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $whatsapp,
                'message' => $message,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo "<div class='alert alert-danger'>Gagal mengirim pesan: " . curl_error($curl) . "</div>";
        } else {
            $result = json_decode($response, true);
            if ($result["status"]) {
                echo "<div class='alert alert-success'>Pesan berhasil dikirim: " . $result["detail"] . "</div>";
            } else {
                echo "<div class='alert alert-danger'>Gagal mengirim pesan: " . $result["reason"] . "</div>";
            }
        }
        curl_close($curl);
    }
}
if (isset($_POST["submit"])) {
    
    // ... (validasi input dan pengiriman pesan WhatsApp)
// Cari nama guru berdasarkan ID
$namaGuru = '';
foreach ($guruList as $guru) {
    if ($guru['id'] == $_POST["guru"]) {
        $namaGuru = $guru['nama'];
        break;
    }
}
// Notifikasi sukses atau gagal

    // Data yang akan dikirim ke Google Spreadsheet
    $dataSpreadsheet = [
        'guru' => $namaGuru,
        'kelas' => $kelas,
        'namaSiswa' => $namaSiswa,
        'menghafal' => $menghafal,
        'alasan' => $alasan,
        'surat' => $namaSurat,
        'ayat' => $ayat,
        'baris' => $baris,
        'keterangan' => $keterangan,
        'whatsapp' => $whatsapp,
    ];

    // Kirim data ke Google Spreadsheet
    $urlGoogleScript = 'https://script.google.com/macros/s/AKfycbxMHNzLNTzJxdSKn4rh2J08bTndcPUw8ze8VEwfMtF2mZO6y1iu7rezrGBFTlaV_gn6oA/exec'; // Ganti dengan URL Web App dari Google Apps Script
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($dataSpreadsheet),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($urlGoogleScript, false, $context);

    // Cek apakah data berhasil dikirim ke Google Spreadsheet
    if ($result === FALSE) {
        echo "<div class='alert alert-danger'>Gagal menyimpan data ke spreadsheet.</div>";
    } else {
        $response = json_decode($result, true);
        if ($response['status'] === 'success') {
            echo "<script>
            document.getElementById('notificationMessage').innerText = 'Pesan berhasil dikirim dan data berhasil disimpan ke spreadsheet.';
            new bootstrap.Modal(document.getElementById('notificationModal')).show();
          </script>";
        } else {
            echo "<script>
            document.getElementById('notificationMessage').innerText = 'Gagal mengirim pesan atau menyimpan data ke spreadsheet.';
            new bootstrap.Modal(document.getElementById('notificationModal')).show();
          </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hafalan Quran</title>
    <style>
        /* Custom CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group input[readonly] {
            background-color: #f9f9f9;
        }
        .btn-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: lightseagreen;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }

    </style>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript untuk mengatur token otomatis
        function setToken() {
            const guruSelect = document.getElementById('guru');
            const tokenInput = document.getElementById('token');
            const guruData = <?= json_encode($guruList); ?>; // Data guru dari PHP

            guruSelect.addEventListener('change', function () {
                const selectedGuruId = this.value;
                const selectedGuru = guruData.find(guru => guru.id == selectedGuruId);
                if (selectedGuru) {
                    tokenInput.value = selectedGuru.token; // Set token berdasarkan guru yang dipilih
                }
            });
        }

        // Fungsi untuk memfilter siswa berdasarkan kelas
        function updateSiswaList() {
            const kelasSelect = document.getElementById('kelas');
            const siswaSelect = document.getElementById('siswa');
            const whatsappInput = document.getElementById('whatsapp');
            const siswaData = <?= json_encode($siswaList); ?>; // Data siswa dari PHP

            const selectedKelas = kelasSelect.value;
            const filteredSiswa = siswaData.filter(siswa => siswa.kelas === selectedKelas); // Filter siswa berdasarkan kelas

            siswaSelect.innerHTML = '<option value="">Pilih Nama Siswa</option>';
            filteredSiswa.forEach(siswa => {
                const option = document.createElement('option');
                option.value = siswa.id;
                option.textContent = siswa.nama;
                siswaSelect.appendChild(option);
            });

            whatsappInput.value = ''; // Reset nomor WhatsApp
        }

        // Fungsi untuk mengisi nomor WhatsApp siswa
        function updateWhatsapp() {
            const siswaSelect = document.getElementById('siswa');
            const whatsappInput = document.getElementById('whatsapp');
            const siswaData = <?= json_encode($siswaList); ?>; // Data siswa dari PHP

            const selectedSiswaId = siswaSelect.value;
            const selectedSiswa = siswaData.find(siswa => siswa.id == selectedSiswaId);
            if (selectedSiswa) {
                whatsappInput.value = selectedSiswa.whatsapp; // Set nomor WhatsApp
            }
        }

        // Memanggil semua fungsi saat halaman dimuat
        function allFunctions() {
            setToken(); // Atur token otomatis
            updateSiswaList(); // Perbarui daftar siswa
            document.getElementById('kelas').addEventListener('change', updateSiswaList); // Event listener untuk kelas
            document.getElementById('siswa').addEventListener('change', updateWhatsapp); // Event listener untuk siswa
        }

        // Jalankan fungsi saat halaman dimuat
        window.onload = allFunctions;
    </script>
</head>

<body>
    <div style="width: 100%; max-width: 600px; margin: auto; border-radius: 10px; box-shadow: 0px 0px 10px -7px; padding: 20px;">
        <h2 style="text-align: center;">Form Hafalan Quran</h2>
        <form action="" method="POST">
            <!-- Guru -->
            <div class="mb-3">
                <label for="guru" class="form-label">Guru</label>
                <select class="form-select" id="guru" name="guru" required>
                    <option value="" disabled selected>Pilih Guru</option>
                    <?php foreach ($guruList as $guru): ?>
                        <option value="<?= $guru['id'] ?>"><?= htmlspecialchars($guru['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Token (Hidden) -->
            <div class="mb-3" style="display: none;">
                <label for="token" class="form-label">Token</label>
                <input type="text" class="form-control" id="token" name="token" readonly required>
            </div>

            <!-- Kelas -->
            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <select class="form-select" id="kelas" name="kelas" required>
                    <option value="" disabled selected>Pilih Kelas</option>
                    <?php foreach ($kelasList as $kelas): ?>
                        <option value="<?= htmlspecialchars($kelas) ?>"><?= htmlspecialchars($kelas) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Siswa -->
            <div class="mb-3">
                <label for="siswa" class="form-label">Nama Siswa</label>
                <select class="form-select" id="siswa" name="siswa" required>
                    <option value="">Pilih Nama Siswa</option>
                </select>
            </div>

            <!-- Menghafal atau Tidak -->
            <div class="mb-3">
                <label for="menghafal" class="form-label">Menghafal?</label>
                <select class="form-select" id="menghafal" name="menghafal" required>
                    <option value="" disabled selected>Pilih Status</option>
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>
            </div>

            <!-- Alasan (Opsional) -->
            <div class="mb-3">
                <label for="alasan" class="form-label">Alasan (Opsional)</label>
                <input type="text" class="form-control" id="alasan" name="alasan" placeholder="Contoh: Sakit, izin, dll">
            </div>

            <!-- Nomor WhatsApp -->
            <div class="mb-3">
                <label for="whatsapp" class="form-label">Nomor WhatsApp Siswa</label>
                <input type="text" class="form-control" id="whatsapp" name="whatsapp" readonly required>
            </div>

            <!-- Surat -->
            <div class="mb-3">
                <label for="surat" class="form-label">Surat</label>
                <select class="form-select" id="surat" name="surat" required>
                    <?php foreach ($daftarSurat as $id => $nama): ?>
                        <option value="<?= $id ?>"><?= $id ?>. <?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Ayat -->
            <div class="mb-3">
                <label for="ayat" class="form-label">Ayat</label>
                <input type="text" class="form-control" id="ayat" name="ayat" placeholder="Contoh: 1-5 atau 1,2,3" required>
            </div>

            <!-- Baris -->
            <div class="mb-3">
                <label for="baris" class="form-label">Baris</label>
                <input type="text" class="form-control" id="baris" name="baris" placeholder="Contoh: 1-3 atau 1,2,3" required>
            </div>

            <!-- Keterangan -->
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="form-group">
                <button type="submit" name="submit" class="btn-submit">Submit</button>
            </div>
        </form>
    </div>
    <!-- Modal untuk Notifikasi -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="notificationMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.reload()">OK</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
