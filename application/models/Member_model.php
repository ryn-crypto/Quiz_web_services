<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Member_model extends CI_Model
{
    // untuk mengambil data member
    public function getMember($id = null)
    {
      $this->load->database();

      if ($id === null) {
          return $this->db->get('pelanggan')->result_array();
      } else {
          return $this->db->get_where('pelanggan', ['id_pelanggan' => $id])->result_array();
      }

    }

    // untuk menambahkan member
    public function createMember($dataMember)
    {
      // load database
      $this->load->database();

      // insert data
      $this->db->insert('pelanggan', $dataMember);
      return $this->db->affected_rows();
    }

    // untuk menghapus user
    public function deleteMember($id)
    {
      // load database
      $this->load->database();

      $this->db->delete('pelanggan', ['id_pelanggan' => $id]);
      return $this->db->affected_rows();
    }
} 