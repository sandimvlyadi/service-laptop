<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

  function auth($data = array())
  {
    $result = array(
			'result'   => false,
			'msg'      => ''
		);

    $s = $data['session'];
		$q =  "SELECT
            *
          FROM
            `session_log`
          WHERE
            `id_pengguna` = '". $s['id'] ."'
              AND
            `token` = '". $s['token'] ."'
              AND
            `deleted_at` IS NULL
          ;";
		$r = $this->db->query($q, false)->result_array();
		if(count($r) > 0){
			$result['result'] = true;
		}

    return $result;
  }

  function post($data = array())
  {
    $result = array(
			'result' => false,
			'msg'    => 'Invalid username or password.',
      'csrf'   => array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			)
		);

		$u = $data['userData'];
		$d = $data['postData'];
    $d['password'] = md5($d['password']);

    $q =  "SELECT
            a.*,
            b.`nama_level`
          FROM
            `pengguna` a
          LEFT JOIN
            `level_pengguna` b
              ON
            a.`id_level_pengguna` = b.`id`
          WHERE
            a.`username` = '". $this->db->escape_str($d['username']) ."'
              AND
            a.`password` = '". $this->db->escape_str($d['password']) ."'
              AND
            a.`deleted_at` IS NULL
          ;";
    $r = $this->db->query($q)->result_array();
    if (count($r) > 0) {
      $result['result'] = true;
      $result['target'] = base_url('dashboard/');

      $token = sha1(time());
      $q =  "INSERT INTO
              `session_log`
              (
                `created_at`,
                `id_pengguna`,
                `token`
              )
            VALUES
              (
                NOW(),
                '". $r[0]['id'] ."',
                '". $token ."'
              );";
      if ($this->db->simple_query($q)) {
        $this->session->set_userdata(
          'userSession', array(
            'id'          => $r[0]['id'],
            'username'    => $r[0]['username'],
            'displayName' => $r[0]['display_name'],
            'email'       => $r[0]['email'],
            'kontak'      => $r[0]['kontak'],
            'alamat'      => $r[0]['alamat'],
            'level'       => $r[0]['nama_level'],
            'token'       => $token
          )
        );
      }
    }

    return $result;
  }

}
