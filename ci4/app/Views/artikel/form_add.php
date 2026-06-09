<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<?php if (isset($validation)): ?>
<div style="color:red; margin-bottom:10px;">
    <?= $validation->listErrors(); ?>
</div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <p>
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" value="<?= old('judul'); ?>" required>
    </p>
    <p>
        <label for="isi">Isi</label>
        <textarea name="isi" id="isi" cols="50" rows="10"><?= old('isi'); ?></textarea>
    </p>
    <p>
        <label for="id_kategori">Kategori</label>
        <select name="id_kategori" id="id_kategori" required>
            <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="gambar">Gambar</label>
        <input type="file" name="gambar" id="gambar">
    </p>
    <p><input type="submit" value="Kirim" class="btn"></p>
</form>

<?= $this->include('template/admin_footer'); ?>