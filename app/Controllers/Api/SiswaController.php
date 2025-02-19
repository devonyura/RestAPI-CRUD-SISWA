<?php

namespace App\Controllers\Api;

use App\Models\SiswaModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;


class SiswaController extends ResourceController
{
	protected $modelName = 'App\Models\SiswaModel';
	protected $format    = 'json';
	
	// Ambil semua siswa
	public function index()
	{
		try {
			$data = $this->model->findAll();

			if (empty($data)) {
				return $this->failNotFound('Tidak ada data siswa.');
			}

			return $this->respond([
				'status' => 'success',
				'data' => $data
			]);
		} catch (\Exception $e) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Terjadi kesalahan pada server.',
					'error' => $e->getMessage()
				])
				->setStatusCode(500);
		}
	}

	// Tambah siswa
	public function create()
	{
		$rules = [
			'nama' => 'required|min_length[3]|is_unique[siswa.nama]',
			'alamat' => 'required|min_length[6]',
			'gender' => 'required'
		];

		if (!$this->validate($rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

		$model = new SiswaModel();
		$data = $this->request->getJSON();

		$siswaData = [
			'nama' => $data->nama,
			'alamat' => $data->alamat,
			'gender' => $data->gender
		];

		try {
			if (!$model->insert($siswaData)) {
				return Services::response()
					->setJSON([
						'status' => 'error',
						'message' => 'Gagal menambahkan siswa.',
						'errors' => $model->errors() // Mengambil error dari model jika ada
					])
					->setStatusCode(500);
			}

			return Services::response()
				->setJSON([
					'status' => 'success',
					'message' => 'Data siswa berhasil ditambahkan',
					'data' => $siswaData
				])
				->setStatusCode(201);

		} catch (\Exception $e) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Terjadi kesalahan pada server.',
					'error' => $e->getMessage()
				])
				->setStatusCode(500);
		}
	}

	// Ambil satu siswa
	public function show($id = null)
	{
		try {
			$model = new SiswaModel();
			$data = $model->find($id);
	
			if (!$data) {
				return $this->failNotFound('Siswa tidak ditemukan.');
			}
	
			return Services::response()
				->setJSON([
					'status' => 'success',
					'data' => $data
				])
				->setStatusCode(200);
	
		} catch (\Exception $e) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Terjadi kesalahan pada server.',
					'error' => $e->getMessage()
				])
				->setStatusCode(500);
		}
	}

	// Update siswa
	public function update($id = null)
	{
		$rules = [
			'nama' => 'required|min_length[3]',
			'alamat' => 'required|min_length[6]',
			'gender' => 'required'
		];

		$model = new SiswaModel();
    $data = $this->request->getJSON();

		if (!$model->find($id)) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Siswa tidak ditemukan'
				])
				->setStatusCode(404);
		}
	
		if (!$this->validate($rules)) {
			return $this->failValidationErrors($this->validator->getErrors());
		}

		$siswaData = [
				'nama' => $data->nama,
				'alamat' => $data->alamat,
				'gender' => $data->gender
		];

		try {

			$model->update($id, $siswaData);
		
			return Services::response()
				->setJSON([
					'status' => 'success',
					'message' => 'Siswa berhasil diperbarui',
					'data' => $siswaData
				])
				->setStatusCode(200);

		} catch (Exeption $e) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Gagal memperbarui data siswa',
					'error' => $e->getMessage()
				])
				->setStatusCode(500);
		}
		
	}

	// Hapus siswa
	public function delete($id = null)
	{
		try {
			$model = new SiswaModel();

			if (!$model->find($id)) {
				return $this->failNotFound('Siswa tidak ditemukan.');
			}

			if (!$model->delete($id)) {
				return Services::response()
					->setJSON([
						'status' => 'error',
						'message' => 'Gagal menghapus siswa.'
					])
					->setStatusCode(500);
			}

			return Services::response()
				->setJSON([
					'status' => 'success',
					'message' => 'Siswa berhasil dihapus.'
				])
				->setStatusCode(200);

		} catch (\Exception $e) {
			return Services::response()
				->setJSON([
					'status' => 'error',
					'message' => 'Terjadi kesalahan pada server.',
					'error' => $e->getMessage()
				])
				->setStatusCode(500);
		}
	}
}
