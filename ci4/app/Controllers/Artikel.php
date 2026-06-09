<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->getArtikelDenganKategori();
        return view('artikel/index', compact('artikel', 'title'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->db->table('artikel')
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->where('artikel.slug', $slug)
            ->get()
            ->getRowArray();

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';
        $model = new ArtikelModel();
        $q = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';
        $page = $this->request->getVar('page') ?? 1;

        $builder = $model->db->table('artikel')
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }

        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        $perPage    = 10;
        $totalRows  = $builder->countAllResults(false);
        $artikel    = $builder->limit($perPage, ($page - 1) * $perPage)->get()->getResultArray();
        $totalPages = ceil($totalRows / $perPage);

        $pagerLinks = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $pagerLinks[] = [
                'title'  => $i,
                'url'    => "/admin/artikel?page=$i&q=$q&kategori_id=$kategori_id",
                'active' => ($i == $page),
            ];
        }

        $data = [
            'title'       => $title,
            'q'           => $q,
            'kategori_id' => $kategori_id,
            'artikel'     => $artikel,
            'pager'       => ['links' => $pagerLinks],
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($data);
        }

        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->findAll();
        return view('artikel/admin_index', $data);
    }

    public function add()
    {
        $kategoriModel = new KategoriModel();

        if ($this->request->is('post')) {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul'       => 'required',
                'id_kategori' => 'required|integer',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return view('artikel/form_add', [
                    'title'      => 'Tambah Artikel',
                    'kategori'   => $kategoriModel->findAll(),
                    'validation' => $validation,
                ]);
            }

            $file = $this->request->getFile('gambar');
            $gambarName = '';

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $file->move(ROOTPATH . 'public/gambar');
                $gambarName = $file->getName();
            }

            $model = new ArtikelModel();
            $model->insert([
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'slug'        => url_title($this->request->getPost('judul')),
                'id_kategori' => $this->request->getPost('id_kategori'),
                'gambar'      => $gambarName,
            ]);
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_add', [
            'title'    => 'Tambah Artikel',
            'kategori' => $kategoriModel->findAll(),
        ]);
    }

    public function edit($id)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        if ($this->request->is('post')) {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'judul'       => 'required',
                'id_kategori' => 'required|integer',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return view('artikel/form_edit', [
                    'title'      => 'Edit Artikel',
                    'artikel'    => $model->find($id),
                    'kategori'   => $kategoriModel->findAll(),
                    'validation' => $validation,
                ]);
            }

            $model->update($id, [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'id_kategori' => $this->request->getPost('id_kategori'),
            ]);
            return redirect()->to('/admin/artikel');
        }

        return view('artikel/form_edit', [
            'title'    => 'Edit Artikel',
            'artikel'  => $model->find($id),
            'kategori' => $kategoriModel->findAll(),
        ]);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        return redirect()->to('/admin/artikel');
    }
}