<?php 
if (isset($_POST['_form']) AND $_POST['_form'] == 'true') {
    $sql = "UPDATE barang SET 
            id_merk='$_POST[id_merk]',
            nama_barang='$_POST[nama_barang]',
            harga='$_POST[harga]',
            warna='$_POST[warna]',
            jenis_lengan='$_POST[jenis_lengan]',
            stok='$_POST[stok]'";
    if ($_FILES['gambar']['name'] !== '') {
        $file = explode('.', $_FILES['gambar']['name']);
        $file_ext = strtolower(end($file));
        $gambar = date('dmYHis').'.'.$file_ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../barang/'.$gambar);
        unlink('../barang/'.$_POST['_gambar']);
        $sql .= ", gambar='$gambar'";
    }
    $sql .= "WHERE id_barang=$_POST[_id]";
    if ($koneksi->query($sql)) {
        echo "
            <script>
                alert('Berhasil diinput!');
                window.location='?halaman=barang';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Gagal diinput!');
                window.location='?halaman=barang';
            </script>
        ";
    }
}

if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
    $koneksi->query("DELETE FROM `barang` WHERE id_barang=$_GET[id]");
    echo "
        <script>
            alert('Berhasil dihapus!');
            window.location='?halaman=barang';
        </script>
    ";
}
?>
<div class="col-md-4 hidden-print">
    <div class="panel panel-warning">
        <div class="panel-heading"><h3 class="text-center">TAMBAH BARANG BARU</h3></div>
        <div class="panel-body">
            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST" enctype="multipart/form-data">
                <?php if ($query = $koneksi->query("SELECT * FROM `barang` WHERE id_barang=$_GET[id]")): ?>
                    <?php while ($barang = $query->fetch_array()): ?>
                        <div class="form-group">
                            <label for="id_merk">Merk</label>
                            <select name="id_merk" class="form-control">
                                <?php if ($query = $koneksi->query("SELECT * FROM `merk`")): ?>
                                    <?php while ($merk = $query->fetch_array()): ?>
                                        <option value="<?=$merk['id_merk']?>"<?php if ($barang['id_merk'] == $merk['id_merk']): ?> selected="on" <?php endif ?>><?=$merk['nama_merk']?></option>
                                    <?php endwhile ?>
                                <?php endif ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="<?=$barang['nama_barang']?>">
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="text" name="harga" class="form-control" value="<?=$barang['harga']?>">
                        </div>
                        <div class="form-group">
                            <label for="warna">Warna</label>
                            <input type="text" name="warna" class="form-control" value="<?=$barang['warna']?>">
                        </div>
                        <div class="form-group">
                            <label for="jenis_lengan">Jenis Lengan</label>
                            <select name="jenis_lengan" class="form-control">
                                <option value="panjang"<?php if ($barang['jenis_lengan'] == 'panjang'): ?> selected="on" <?php endif ?>>Panjang</option>
                                <option value="pendek"<?php if ($barang['jenis_lengan'] == 'pendek'): ?> selected="on" <?php endif ?>>Pendek</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="text" name="stok" class="form-control" value="<?=$barang['stok']?>">
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <input type="file" name="gambar" class="form-control">
                            <span class="help-block" style="color: #D9534F;">*) Kosongkan jika tidak diganti</span>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block">Update</button>
                        <input type="hidden" name="_id" value="<?=$barang['id_barang']?>">
                        <input type="hidden" name="_gambar" value="<?=$barang['gambar']?>">
                    <?php endwhile ?>
                <?php endif ?>
                <input type="hidden" name="_form" value="true">
            </form>
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="panel panel-info">
        <div class="panel-heading"><h3 class="text-center">DAFTAR BARANG</h3></div>
        <div class="panel-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Merk</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Warna</th>
                        <th>Jenis Lengan</th>
                        <th>Stok</th>
                        <th class="hidden-print"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($query = $koneksi->query("SELECT * FROM merk m JOIN barang b ON m.id_merk=b.id_merk")): ?>
                        <?php $no = 1; ?>
                        <?php while($barang = $query->fetch_array()): ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$barang['nama_merk']?></td>
                            <td><?=$barang['nama_barang']?></td>
                            <td>Rp. <?=$barang['harga']?>,-</td>
                            <td><?=$barang['warna']?></td>
                            <td><?=ucwords($barang['jenis_lengan'])?></td>
                            <td><?=$barang['stok']?></td>
                            <td class="hidden-print">
                                <div class="btn-group">
                                    <a href="?halaman=barang&action=update&id=<?=$barang['id_barang']?>" class="btn btn-warning btn-xs">Edit</a>
                                    <a href="?halaman=barang&action=delete&id=<?=$barang['id_barang']?>" class="btn btn-danger btn-xs">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        <div class="panel-footer hidden-print">
            <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
        </div>
    </div>
</div>