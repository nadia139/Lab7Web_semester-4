<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ArtikelModel;

class AjaxController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Artikel AJAX'];
        return view('ajax/index', $data);
    }

    public function getData()
    {
        $model = new ArtikelModel();
        $data = $model->findAll();
        return $this->response->setJSON($data);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        $data = ['status' => 'OK'];
        return $this->response->setJSON($data);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $model = new ArtikelModel();
            $model->insert([
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
                'slug'  => url_title($this->request->getPost('judul')),
            ]);
            return $this->response->setJSON(['status' => 'OK']);
        }
        return $this->response->setJSON(['status' => 'ERROR']);
    }

    public function update($id)
    {
        if ($this->request->is('post')) {
            $model = new ArtikelModel();
            $model->update($id, [
                'judul' => $this->request->getPost('judul'),
                'isi'   => $this->request->getPost('isi'),
            ]);
            return $this->response->setJSON(['status' => 'OK']);
        }
        return $this->response->setJSON(['status' => 'ERROR']);
    }

    public function getDetail($id)
    {
        $model = new ArtikelModel();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }
}