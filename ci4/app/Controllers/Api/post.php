<?php

namespace App\Controllers\Api;

use App\Models\ArtikelModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Post extends ResourceController
{
    protected $modelName = 'App\Models\ArtikelModel';
    protected $format    = 'json';

    // GET /post
    public function index()
    {
        $artikel = $this->model->findAll();
        return $this->respond([
            'status'  => 200,
            'artikel' => $artikel
        ]);
    }

    // GET /post/{id}
    public function show($id = null)
    {
        $artikel = $this->model->find($id);
        if (!$artikel) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        return $this->respond([
            'status'  => 200,
            'artikel' => $artikel
        ]);
    }

    // POST /post
    public function create()
    {
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            $data = $this->request->getPost();
        }

        if (!$this->model->save($data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Data berhasil ditambahkan'
        ]);
    }

    // PUT /post/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        if (empty($data)) {
            $data = $this->request->getRawInput();
        }

        unset($data['id']);

        if (empty($data)) {
            return $this->fail('Tidak ada data untuk diupdate', 400);
        }

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }
        return $this->respond([
            'status'  => 200,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    // DELETE /post/{id}
    public function delete($id = null)
    {
        $artikel = $this->model->find($id);
        if (!$artikel) {
            return $this->failNotFound('Data tidak ditemukan');
        }
        $this->model->delete($id);
        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}