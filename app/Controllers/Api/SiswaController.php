<?php

namespace App\Controllers\Api;

use App\Models\SiswaModel;
use CodeIgniter\RESTful\ResourceController;

class SiswaController extends ResourceController
{
	protected $modelName = 'App\Models\SiswaModel';
	protected $format    = 'json';

	// Ambil semua siswa
	public function index()
	{
		return $this->respond($this->model->findAll());
	}

	// Tambah siswa
	public function create()
	{
		$model = new SiswaModel();
        $data = $this->request->getJSON();

        $siswaData = [
            'nama' => $data->nama,
            'alamat' => $data->alamat,
            'gender' => $data->gender
        ];

        $model->insert($siswaData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $siswaData
        ]);
	}

	// Ambil satu siswa
	public function show($id = null)
	{
		$data = $this->model->find($id);
		return $data ? $this->respond($data) : $this->failNotFound('Siswa tidak ditemukan');
	}

	// Update siswa
	public function update($id = null)
	{
		$model = new SiswaModel();
        $data = $this->request->getJSON();

        $siswaData = [
            'nama' => $data->nama,
            'alamat' => $data->alamat,
            'gender' => $data->gender
        ];
        
		$this->model->update($id, $data);
		return $this->respond(['message' => 'Siswa berhasil diperbarui']);
	}

	// Hapus siswa
	public function delete($id = null)
	{
		$this->model->delete($id);
		return $this->respondDeleted(['message' => 'Siswa berhasil dihapus']);
	}
}
