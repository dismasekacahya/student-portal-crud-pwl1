<?php
$nilaiAkhir = hitungNilaiAkhir($user['nilai_tugas'], $user['nilai_uts'], $user['nilai_uas']);
$grade = tentukanGrade($nilaiAkhir);
?>
<div class="grid"><section class="panel"><h2>Identitas Mahasiswa</h2><dl>
<?php foreach (['nim'=>'NIM','nama'=>'Nama','email'=>'Email','nama_prodi'=>'Program Studi'] as $field=>$label): ?><dt><?= $label ?></dt><dd><?= e($user[$field]) ?></dd><?php endforeach; ?>
</dl><a href="edit.php?id=<?= (int) $user['id'] ?>">Edit data mahasiswa</a></section>
<section class="panel"><h2>Evaluasi Akademik</h2><dl>
<?php foreach (['nilai_tugas'=>'Nilai Tugas (30%)','nilai_uts'=>'Nilai UTS (30%)','nilai_uas'=>'Nilai UAS (40%)','kehadiran'=>'Kehadiran (%)'] as $field=>$label): ?><dt><?= $label ?></dt><dd><?= e($user[$field]) ?></dd><?php endforeach; ?>
<dt>Nilai Akhir / Grade</dt><dd><?= number_format($nilaiAkhir, 2) ?> / <?= $grade ?></dd>
<dt>Status</dt><dd><?= tentukanStatusKelulusan($nilaiAkhir, $user['kehadiran']) ?></dd>
<dt>Predikat</dt><dd><?= tentukanPredikat($nilaiAkhir, $user['kehadiran']) ?></dd>
<dt>Remedial</dt><dd><?= perluRemedial($nilaiAkhir) ? 'YA' : 'TIDAK' ?></dd></dl><p><?= e(buatPesan($grade)) ?></p></section></div>
<a href="index.php">← Daftar mahasiswa</a>
