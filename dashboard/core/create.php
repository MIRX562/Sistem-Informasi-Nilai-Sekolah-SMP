<?php 
	//Admin Create
	if (isset($_POST['create-admin'])) {
		$username 	=	$_POST['username'];
		$password 	=	$_POST['password'];
		$name 		=	$_POST['name'];
		$telp 		=	$_POST['telp'];
		$status 	=	$_POST['status'];
		$alamat 	=	$_POST['alamat'];
		$kelamin 	=	$_POST['jenis_kelamin'];

		$admin 		= 	mysql_query("INSERT INTO users (`id`, `nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`) 
									VALUES (NULL, NULL, '$name', '$username', '$password', '$telp', '$alamat', '$status', '$kelamin', NULL, 'admin')");

		if ($admin) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=admin'>";
		}
	}
?>
<?php 
	//Guru Create
	if (isset($_POST['create-guru'])) {
		$nip	 	=	$_POST['nip'];
		$username 	=	$_POST['username'];
		$password 	=	$_POST['password'];
		$name 		=	$_POST['name'];
		$telp 		=	$_POST['telp'];
		$status 	=	$_POST['status'];
		$alamat 	=	$_POST['alamat'];
		$kelamin 	=	$_POST['jenis_kelamin'];
		$kelas 		=	$_POST['kelas'];

		$guru 		= 	mysql_query("INSERT INTO users (`id`, `nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`) 
									VALUES (NULL, '$nip', '$name', '$username', '$password', '$telp', '$alamat', '$status', '$kelamin', '$kelas', 'guru')");

		if ($guru) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=guru'>";
		}
	}
?>
<?php 
	//name Create
	if (isset($_POST['create-siswa'])) {
		$nis	 	=	$_POST['nis'];
		$username 	=	$_POST['username'];
		$password 	=	$_POST['password'];
		$name 		=	$_POST['name'];
		$telp 		=	$_POST['telp'];
		$status 	=	$_POST['status'];
		$alamat 	=	$_POST['alamat'];
		$kelamin 	=	$_POST['jenis_kelamin'];
		$kelas 		=	$_POST['kelas'];

		$name 		= 	mysql_query("INSERT INTO users (`id`, `nomor_induk`, `name`, `username`, `password`, `telp`, `alamat`, `status`, `jenis_kelamin`, `kelas_id`, `access`) 
									VALUES (NULL, '$nis', '$name', '$username', '$password', '$telp', '$alamat', '$status', '$kelamin', '$kelas', 'orang_tua')");

		if ($name) {
			echo "<meta http-equiv='refresh' content='0;URL=?users=orang_tua'>";
		}
	}
?>
<?php 
	//Kelas Create
	if (isset($_POST['create-kelas'])) {
		$kelasnama	=	$_POST['kelas'];

		$kelas 		= 	mysql_query("INSERT INTO kelas (`kelas_id`, `kelas_nama`) 
									VALUES (NULL, '$kelasnama')");

		if ($kelas) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=kelas'>";
		}
	}
?>
<?php 
	//Tahun Create
	if (isset($_POST['create-tahun'])) {
		$tahunnama	=	$_POST['tahun'];

		$tahun 		= 	mysql_query("INSERT INTO tahun (`tahun_id`, `tahun_nama`) 
									VALUES (NULL, '$tahunnama')");

		if ($tahun) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=tahun'>";
		}
	}
?>
<?php 
	//Mata Pelajaran Create
	if (isset($_POST['create-pelajaran'])) {
		$pelajarannama	=	$_POST['pelajaran'];

		$pelajaran 		= 	mysql_query("INSERT INTO pelajaran (`pelajaran_id`, `pelajaran_nama`) 
									VALUES (NULL, '$pelajarannama')");

		if ($pelajaran) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=pelajaran'>";
		}
	}
?>
<?php 
	//Kategori Create
	if (isset($_POST['create-kategori'])) {
		$kategorinama		=	$_POST['nama'];
		$kategorideskripsi	=	$_POST['deskripsi'];

		$kategori 			= 	mysql_query("INSERT INTO kategori (`kategori_id`, `kategori_nama`, `kategori_deskripsi`) 
									VALUES (NULL, '$kategorinama', '$kategorideskripsi')");

		if ($kategori) {
			echo "<meta http-equiv='refresh' content='0;URL=?artikel=kategori'>";
		}
	}
?>
<?php 
	//Sekolah Create
	if (isset($_POST['create-sekolah'])) {
		$nama		=	$_POST['nama'];
		$alamat		=	$_POST['alamat'];
		$telp		=	$_POST['telp'];
		$visi		=	$_POST['visi'];
		$misi		=	$_POST['misi'];

		$sekolah 			= 	mysql_query("INSERT INTO sekolah (`sekolah_id`, `sekolah_nama`, `sekolah_alamat`, `sekolah_telp`, `sekolah_visi`, `sekolah_misi`) 
									VALUES (NULL, '$nama', '$alamat', '$telp', '$visi', '$misi')");

		if ($sekolah) {
			echo "<meta http-equiv='refresh' content='0;URL=?akademik=sekolah'>";
		}
	}
?>
<?php 
	if (isset($_POST['input-proses'])) {
		$name 		=	$_POST['name'];
		$pelajaran	=	$_POST['pelajaran'];
		$semester 	=	$_POST['semester'];
		$jenis 		=	$_POST['jenis'];
		$tahun 		=	$_POST['tahun'];
		$kkm 		=	$_POST['kkm'];
		// $nilaipoin	=	$_POST['nilai'];
		$uh		=	$_POST['uh'];
		$pas		=	$_POST['pas'];
		$p5ra		=	$_POST['p5ra'];
		$tugas		=	$_POST['tugas'];
		$kehadiran		=	$_POST['kehadiran'];
		$keaktifan		=	$_POST['keaktifan'];
		$kekompakan		=	$_POST['kekompakan'];
		$jumlahdata	=	count($name);

		for($x=0; $x<$jumlahdata; $x++) {
            // Hitung nilai_akhir berdasarkan rumus
            $nilai_akhir = 0;
            
            // Konversi nilai ke float untuk perhitungan
            $uh_value = floatval($uh[$x]);
            $pas_value = floatval($pas[$x]);
            $p5ra_value = floatval($p5ra[$x]);
            $tugas_value = floatval($tugas[$x]);
            $kehadiran_value = floatval($kehadiran[$x]);
            $keaktifan_value = floatval($keaktifan[$x]);
            $kekompakan_value = floatval($kekompakan[$x]);
            
            // Hitung nilai_akhir berdasarkan keberadaan nilai p5ra
            if (!empty($p5ra[$x]) && $p5ra_value > 0) {
                // Jika p5ra ada
                $nilai_akhir = ($uh_value * 0.20) + ($pas_value * 0.30) + ($p5ra_value * 0.20) + 
                               ($tugas_value * 0.15) + ($kehadiran_value * 0.05) + 
                               ($keaktifan_value * 0.05) + ($kekompakan_value * 0.05);
            } else {
                // Jika p5ra tidak ada
                $nilai_akhir = ($uh_value * 0.25) + ($pas_value * 0.35) + ($tugas_value * 0.20) + 
                               ($kehadiran_value * 0.075) + ($keaktifan_value * 0.0625) + 
                               ($kekompakan_value * 0.0625);
            }
            
            // Bulatkan nilai_akhir ke 2 desimal
            $nilai_akhir = round($nilai_akhir, 2);
            
            // Simpan ke database
            $nilai = mysql_query("INSERT INTO nilai (nilai_id, id, pelajaran_id, semester_id, tahun_id, nilai_kkm, 
                                 uh, pas, p5ra, tugas, kehadiran, keaktifan, kekompakan, nilai_akhir) 
                                 VALUES (NULL, '$name[$x]', '$pelajaran[$x]', '$semester[$x]', '$tahun[$x]', 
                                 '$kkm[$x]', '$uh[$x]', '$pas[$x]', '$p5ra[$x]', '$tugas[$x]', 
                                 '$kehadiran[$x]', '$keaktifan[$x]', '$kekompakan[$x]', '$nilai_akhir')");
            
            if ($nilai) {
                echo "<meta http-equiv='refresh' content='0;URL= ?nilai=input '/>";
            } else {
                echo "Gagal Input Nilai";
            }
        }
	}
?>
<?php 
	//Upload Modul
	if(isset($_POST['upload'])){
        $allowed_ext    = array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'rar', 'zip');
        $file_name      = $_FILES['file']['name'];
        $file_ext       = strtolower(end(explode('.', $file_name)));
        $file_size      = $_FILES['file']['size'];
        $file_tmp       = $_FILES['file']['tmp_name'];
        
        $nama           = $_POST['nama'];
        $tgl            = date("Y-m-d");
        
        if(in_array($file_ext, $allowed_ext) === true){
            if($file_size < 20440700){
                $lokasi = 'files/'.$nama.'.'.$file_ext;
                move_uploaded_file($file_tmp, $lokasi);
                $in = mysql_query("INSERT INTO download VALUES(NULL, '$tgl', '$nama', '$file_ext', '$file_size', '$lokasi')");
                if($in){
                    echo "<meta http-equiv='refresh' content='0;URL= ?modul=download '/>";
                }else{
                    echo '<div class="error">ERROR: Gagal upload file!</div>';
                }
            }else{
                echo '<div class="error">ERROR: Besar ukuran file (file size) maksimal 20 Mb!</div>';
            }
        }else{
            echo '<div class="error">ERROR: Ekstensi file tidak di izinkan!</div>';
        }
    }
?>
