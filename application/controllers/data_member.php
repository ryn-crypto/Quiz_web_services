<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Data_member extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Member_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
		// persiapan data untuk melakukan query
		$id = $this->get('id_pelanggan');
		
		// melakukan query
		if ($id === null) {
			$User = $this->Member_model->getMember();
		} else {
			$User = $this->Member_model->getMember($id);
		}

		// melakukan return
		if ($User) {
			$this->response([
				'status' 	=> true,
				'data'		=> $User
			]);
            
		} else {
			$this->response([
				'status' 	=> false,
				'message'		=> 'Id tidak ditemukan !!'
			]);
		}
	}

    public function index_post()
    {
        // persiapan data yang dikirim dari client
		$dataMember = [
			'nm_pelanggan'	=> $this->post('nama_pelanggan'),
			'jk'		    => $this->post('jenis_kelamin'),
			'alamat'		=> $this->post('alamat'),
			'no_hp'		    => $this->post('no_hp')
		]; 

		// melakukan penambahan ke database
		if ($this->Member_model->createMember($dataMember)> 0) {
			// jika berhasil menambah user
			$this->response([
				'status' 	=> true,
				'message'	=> 'User baru sudah ditambahkan'
			]);
		} else {
			// jika gagal menambah user
			$this->response([
				'status' 	=> false,
				'message'	=> 'User baru gagal ditambahkan'
			]);
		}
    }

    // untuk menghapus data member
	public function index_delete() 
	{
		// persiapan data untuk melakukan query
		$id = $this->delete('id_pelanggan');

		// jika nik tidak ada dalam value
		if ($id === null) {
			$this->response([
				'status' 	=> false,
				'message'	=> 'Silahkan masukan ID pelanggan !!'
			]);
		} else {
			// jika nik ada dalam value
			// lakukan pengecekan
			if ($this->Member_model->deleteMember($id) > 0) {
				// oke data terhapus
				$this->response([
					'status' 	    => true,
					'Id_pelanggan'	=> $id,
					'message'	    => 'data sudah terhapus'
				]);
			} else {
				// nik tidak tersedia
				$this->response([
					'status' 	=> false,
					'message'	=> 'ID Pelanggan tidak ditemukan !!'
				]);
			}
		}
	}
}
