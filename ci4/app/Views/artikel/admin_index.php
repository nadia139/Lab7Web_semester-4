<?= $this->include('template/admin_header'); ?>

<h2><?= $title; ?></h2>

<style>
    .form-inline {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .form-inline input[type="text"],
    .form-inline select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
    }
    .form-inline input[type="text"]:focus,
    .form-inline select:focus {
        border-color: #1A6B3A;
    }
    #article-container table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    #article-container thead tr {
        background-color: #1A6B3A;
        color: white;
    }
    #article-container th,
    #article-container td {
        padding: 10px 14px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    #article-container tbody tr:hover {
        background-color: #f3f4f6;
    }
    .loading {
        text-align: center;
        padding: 20px;
        color: #6b7280;
        font-size: 14px;
    }
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 16px 0;
        gap: 4px;
    }
    .page-item .page-link {
        display: block;
        padding: 6px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        color: #1A6B3A;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }
    .page-item.active .page-link {
        background-color: #1A6B3A;
        color: white;
        border-color: #1A6B3A;
    }
    .page-item .page-link:hover {
        background-color: #f3f4f6;
    }
    .page-item.active .page-link:hover {
        background-color: #1A6B3A;
    }
    .btn-ubah {
        background-color: #1A6B3A;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        margin-right: 4px;
    }
    .btn-hapus {
        background-color: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
    }
</style>

<div class="row mb-3">
    <form id="search-form" class="form-inline">
        <input type="text" name="q" id="search-box" value="<?= $q; ?>" placeholder="Cari judul artikel">
        <select name="kategori_id" id="category-filter">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori as $k): ?>
            <option value="<?= $k['id_kategori']; ?>" <?= ($kategori_id == $k['id_kategori']) ? 'selected' : ''; ?>>
                <?= $k['nama_kategori']; ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Cari" class="btn btn-primary">
    </form>
</div>

<div id="article-container">
    <div class="loading">Memuat data...</div>
</div>

<div id="pagination-container"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const articleContainer = $('#article-container');
    const paginationContainer = $('#pagination-container');
    const searchForm = $('#search-form');
    const searchBox = $('#search-box');
    const categoryFilter = $('#category-filter');

    const fetchData = (url) => {
        articleContainer.html('<div class="loading">Memuat data...</div>');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(data) {
                renderArticles(data.artikel);
                renderPagination(data.pager, data.q, data.kategori_id);
            },
            error: function() {
                articleContainer.html('<div class="loading">Gagal memuat data.</div>');
            }
        });
    };

    const renderArticles = (articles) => {
        let html = '<table>';
        html += '<thead><tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody>';
        if (articles.length > 0) {
            articles.forEach(article => {
                html += `
                <tr>
                    <td>${article.id}</td>
                    <td>
                        <b>${article.judul}</b>
                        <p><small>${article.isi ? article.isi.substring(0, 50) : ''}</small></p>
                    </td>
                    <td>${article.nama_kategori ?? '-'}</td>
                    <td>${article.status == 1 ? 'Aktif' : 'Non-aktif'}</td>
                    <td>
                        <a class="btn-ubah" href="/admin/artikel/edit/${article.id}">Ubah</a>
                        <a class="btn-hapus" onclick="return confirm('Yakin menghapus data?');" href="/admin/artikel/delete/${article.id}">Hapus</a>
                    </td>
                </tr>`;
            });
        } else {
            html += '<tr><td colspan="5" style="text-align:center; color:#6b7280;">Tidak ada data.</td></tr>';
        }
        html += '</tbody></table>';
        articleContainer.html(html);
    };

    const renderPagination = (pager, q, kategori_id) => {
        if (!pager || !pager.links || pager.links.length <= 1) {
            paginationContainer.html('');
            return;
        }
        let html = '<ul class="pagination">';
        pager.links.forEach(link => {
            let url = link.url ? `${link.url}` : '#';
            html += `<li class="page-item ${link.active ? 'active' : ''}">
                <a class="page-link" href="${url}" onclick="event.preventDefault(); fetchPage('${url}')">${link.title}</a>
            </li>`;
        });
        html += '</ul>';
        paginationContainer.html(html);
    };

    window.fetchPage = (url) => {
        fetchData(url);
    };

    searchForm.on('submit', function(e) {
        e.preventDefault();
        const q = searchBox.val();
        const kategori_id = categoryFilter.val();
        fetchData(`/admin/artikel?q=${q}&kategori_id=${kategori_id}`);
    });

    categoryFilter.on('change', function() {
        searchForm.trigger('submit');
    });

    fetchData('/admin/artikel');
});
</script>

<?= $this->include('template/admin_footer'); ?>