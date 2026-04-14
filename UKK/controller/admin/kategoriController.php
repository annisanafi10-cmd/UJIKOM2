<?php
require_once __DIR__ . '/../BaseController.php';

class KategoriController extends BaseController {
    private $model;

    public function __construct($pdo) {
        parent::__construct($pdo);
        require_once __DIR__ . '/../../model/admin/kategoriModel.php';
        $this->model = new KategoriModel($this->db);
    }

    // Fungsi Utama (Nampilin Data)
    public function index() {
        // Cek kalau ada POST masuk ke sini (biar gak blank)
        if (isset($_POST['submit'])) return $this->tambah();
        if (isset($_POST['update'])) return $this->edit();
        if (isset($_GET['hapus'])) return $this->hapus();

        $dataKategori = $this->model->getKategori();
        $this->render('admin/kategori.php', ['dataKategori' => $dataKategori]);
    }

    // Fungsi Tambah (Harus ada biar gak error)
    public function tambah() {
        if (isset($_POST['nama_kategori'])) {
            $nama = trim($_POST['nama_kategori']);
            $this->model->tambahKategori($nama);
            $this->setFlash('flash_success', "Kategori '$nama' berhasil ditambahkan.");
        }
        $this->redirect('index.php?page=kategori');
    }

    // Fungsi Edit (Ini yang tadi bikin error karena kosong/gaada)
    public function edit() {
        if (isset($_POST['id_kategori'])) {
            $id = $_POST['id_kategori'];
            $nama = trim($_POST['nama_kategori']);
            $this->model->updateKategori($id, $nama);
            $this->setFlash('flash_success', "Kategori berhasil diupdate.");
        }
        $this->redirect('index.php?page=kategori');
    }

    public function hapus() {
    $id = $_GET['id'] ?? null;

    if ($id) {
        try {
            // Ganti kategorimodel jadi model
            $this->model->hapusKategori($id); 
            $this->setFlash('flash_success', 'Kategori berhasil dihapus!');
            
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $this->setFlash('flash_error', 'Gagal! Kategori ini masih dipakai oleh data alat.');
            } else {
                $this->setFlash('flash_error', 'Terjadi kesalahan sistem.');
            }
        }
    }
    header("Location: index.php?page=kategori");
    exit;
}
}