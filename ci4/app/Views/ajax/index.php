<?= $this->include('template/header'); ?>

<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-header h1 {
        margin: 0;
        font-size: 24px;
        color: #1a1a1a;
    }
    .btn-tambah {
        background-color: #1A6B3A;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-tambah:hover { background-color: #145c30; }

    .form-card {
        display: none;
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .form-card h3 {
        margin-top: 0;
        margin-bottom: 16px;
        font-size: 18px;
        color: #1a1a1a;
    }
    .form-card label {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    .form-card input[type="text"],
    .form-card textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        margin-bottom: 12px;
        outline: none;
    }
    .form-card input[type="text"]:focus,
    .form-card textarea:focus {
        border-color: #1A6B3A;
    }
    .form-actions {
        display: flex;
        gap: 8px;
    }
    .btn-simpan {
        background-color: #1A6B3A;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-simpan:hover { background-color: #145c30; }
    .btn-batal {
        background-color: #e5e7eb;
        color: #374151;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }
    .btn-batal:hover { background-color: #d1d5db; }

    #artikelTable {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    #artikelTable thead tr {
        background-color: #1A6B3A;
        color: white;
    }
    #artikelTable th,
    #artikelTable td {
        padding: 10px 14px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    #artikelTable tbody tr:hover {
        background-color: #f3f4f6;
    }
    .badge-aktif {
        background-color: #d1fae5;
        color: #065f46;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-nonaktif {
        background-color: #f3f4f6;
        color: #6b7280;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .btn-edit-tbl {
        background-color: #1A6B3A;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        margin-right: 4px;
    }
    .btn-edit-tbl:hover { background-color: #145c30; }
    .btn-delete-tbl {
        background-color: #dc3545;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
    }
    .btn-delete-tbl:hover { background-color: #b91c2c; }
</style>

<div class="page-header">
    <h1>Data Artikel</h1>
    <button class="btn-tambah" onclick="showAddForm()">+ Tambah Artikel</button>
</div>

<!-- Form Tambah -->
<div id="formAdd" class="form-card">
    <h3>Tambah Artikel</h3>
    <label>Judul</label>
    <input type="text" id="addJudul" placeholder="Masukkan judul artikel">
    <label>Isi</label>
    <textarea id="addIsi" rows="5" placeholder="Masukkan isi artikel"></textarea>
    <div class="form-actions">
        <button class="btn-simpan" onclick="submitAdd()">Simpan</button>
        <button class="btn-batal" onclick="hideAddForm()">Batal</button>
    </div>
</div>

<!-- Form Edit -->
<div id="formEdit" class="form-card">
    <h3>Edit Artikel</h3>
    <input type="hidden" id="editId">
    <label>Judul</label>
    <input type="text" id="editJudul" placeholder="Masukkan judul artikel">
    <label>Isi</label>
    <textarea id="editIsi" rows="5" placeholder="Masukkan isi artikel"></textarea>
    <div class="form-actions">
        <button class="btn-simpan" onclick="submitEdit()">Update</button>
        <button class="btn-batal" onclick="hideEditForm()">Batal</button>
    </div>
</div>

<table id="artikelTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
<script>
    function showAddForm() {
        $('#formAdd').show();
        $('#formEdit').hide();
    }

    function hideAddForm() {
        $('#formAdd').hide();
        $('#addJudul').val('');
        $('#addIsi').val('');
    }

    function hideEditForm() {
        $('#formEdit').hide();
    }

    function loadData() {
        $('#artikelTable tbody').html('<tr><td colspan="4" style="text-align:center; color:#6b7280;">Loading data...</td></tr>');
        $.ajax({
            url: "<?= base_url('ajax/getData') ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                var tableBody = "";
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var statusBadge = row.status == 1
                        ? '<span class="badge-aktif">Aktif</span>'
                        : '<span class="badge-nonaktif">Non-aktif</span>';
                    tableBody += '<tr>';
                    tableBody += '<td>' + row.id + '</td>';
                    tableBody += '<td>' + row.judul + '</td>';
                    tableBody += '<td>' + statusBadge + '</td>';
                    tableBody += '<td>';
                    tableBody += '<a href="#" class="btn-edit-tbl btn-edit" data-id="' + row.id + '">Edit</a>';
                    tableBody += '<a href="#" class="btn-delete-tbl btn-delete" data-id="' + row.id + '">Delete</a>';
                    tableBody += '</td>';
                    tableBody += '</tr>';
                }
                $('#artikelTable tbody').html(tableBody);
            }
        });
    }

    function submitAdd() {
        var judul = $('#addJudul').val();
        var isi = $('#addIsi').val();
        if (!judul) { alert('Judul wajib diisi'); return; }
        $.ajax({
            url: "<?= base_url('ajax/add') ?>",
            method: "POST",
            data: { judul: judul, isi: isi },
            dataType: "json",
            success: function(data) {
                if (data.status === 'OK') {
                    hideAddForm();
                    loadData();
                }
            }
        });
    }

    function submitEdit() {
        var id = $('#editId').val();
        var judul = $('#editJudul').val();
        var isi = $('#editIsi').val();
        if (!judul) { alert('Judul wajib diisi'); return; }
        $.ajax({
            url: "<?= base_url('ajax/update/') ?>" + id,
            method: "POST",
            data: { judul: judul, isi: isi },
            dataType: "json",
            success: function(data) {
                if (data.status === 'OK') {
                    hideEditForm();
                    loadData();
                }
            }
        });
    }

    $(document).ready(function() {
        loadData();

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: "<?= base_url('ajax/getDetail/') ?>" + id,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    $('#editId').val(data.id);
                    $('#editJudul').val(data.judul);
                    $('#editIsi').val(data.isi);
                    $('#formEdit').show();
                    $('#formAdd').hide();
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Yakin ingin menghapus artikel ini?')) {
                $.ajax({
                    url: "<?= base_url('ajax/delete/') ?>" + id,
                    method: "DELETE",
                    dataType: "json",
                    success: function(data) {
                        loadData();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
                    }
                });
            }
        });
    });
</script>

<?= $this->include('template/footer'); ?>