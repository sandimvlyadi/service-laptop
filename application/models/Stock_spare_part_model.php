<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_laptop_model extends CI_Model {

	function _get($data = array())
    {
    	$q = "SELECT a.*, SUM(b.`jml_stock`) AS `total` FROM `spare_part` a LEFT JOIN `stock` b ON a.`id` = b.`id_spare_part` ";

        if ($data['search']['value'] && !isset($data['all'])) {
        	$s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE a.`nama_spare_part` LIKE '%". $s ."%' AND a.`deleted_at` IS NULL ";
        } else{
        	$q .= "WHERE a.`deleted_at` IS NULL ";
        }

        $q .= "GROUP BY a.`id` ";

        if (isset($data['order'])) {
        	$dir = $this->db->escape_str($data['order'][0]['dir']);
        	$col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
        	if ($data['order'][0]['column'] != 0) {
                if ($col == 'total') {
                    $q .= "ORDER BY `". $col ."` ". $dir ." ";
                } else{
                    $q .= "ORDER BY a.`". $col ."` ". $dir ." ";
                }
        	} else{
        		$q .= "ORDER BY a.`id` ". $dir ." ";
        	}
        } else{
        	$q .= "ORDER BY a.`id` DESC ";
        }

        return $q;
    }

    function _list($data = array())
    {
        $q = $this->_get($data);
        $q .= "LIMIT ". $this->db->escape_str($data['start']) .", ". $this->db->escape_str($data['length']);
        $r = $this->db->query($q, false)->result_array();

        return $r;
    }

    function _filtered($data = array())
    {
        $q = $this->_get($data);
        $r = $this->db->query($q, false)->result_array();

        return count($r);
    }

    function _all($data = array())
    {
        $data['all'] = true;
        $q = $this->_get($data);
        $r = $this->db->query($q)->result_array();

        return count($r);
    }

	function datatable($data = array())
	{
		$result = array(
            'draw'              => 1,
            'recordsTotal'      => 0,
            'recordsFiltered'   => 0,
            'data'              => array(),
            'result'            => false,
            'msg'               => ''
        );

        $list = $this->_list($data);
        if (count($list) > 0) {
            $result = array(
                'draw'              => $data['draw'],
                'recordsTotal'      => $this->_all($data),
                'recordsFiltered'   => $this->_filtered($data),
                'data'              => $list,
                'result'            => true,
                'msg'               => 'Loaded.',
                'start'             => (int) $data['start'] + 1
            );
        } else{
            $result['msg'] = 'No data left.';
        }

        return $result;
	}

    function edit($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => 'Data stock tidak ditemukan.'
        );

        $q =    "SELECT
                    a.*,
                    b.`nama_kondisi`,
                    c.`status`
                FROM
                    `stock_laptop` a
                LEFT JOIN
                    `kondisi` b
                        ON
                    a.`id_kondisi` = b.`id`
                LEFT JOIN
                    `status` c
                        ON
                    a.`id_status` = c.`id`
                WHERE
                    a.`id` = '". $this->db->escape_str($id) ."'
                ;";
        $r = $this->db->query($q)->result_array();
        if (count($r) > 0) {
            $result['result'] = true;
            $result['data'] = $r[0];
        }

        return $result;
    }

	function save($data = array())
	{
		$result = array(
			'result'    => false,
			'msg'		=> ''
		);

		$u = $data['userData'];
		$d = $data['postData'];
		$id = $d['id'];
		parse_str($d['form'], $f);

		$q = '';
		if ($id == 0) {
			$q =    "INSERT INTO
                        `stock_laptop`
                        (
                            `created_at`,
                            `merk_serie`,
                            `spesifikasi`,
                            `id_kondisi`,
                            `persentase_kondisi`,
                            `harga`,
                            `id_status`,
                            `keterangan`
                        )
                    VALUES
                        (
                            NOW(),
                            '". $this->db->escape_str($f['merk_serie']) ."',
                            '". $this->db->escape_str($f['spesifikasi']) ."',
                            '". $this->db->escape_str($f['id_kondisi']) ."',
                            '". $this->db->escape_str($f['persentase_kondisi']) ."',
                            '". $this->db->escape_str($f['harga']) ."',
                            '". $this->db->escape_str($f['id_status']) ."',
                            '". $this->db->escape_str($f['keterangan']) ."'
                        )
                    ;";
		} else{
            $q =    "UPDATE
                        `stock_laptop`
                    SET
                        `modified_at` = NOW(),
                        `merk_serie` = '". $this->db->escape_str($f['merk_serie']) ."',
                        `spesifikasi` = '". $this->db->escape_str($f['spesifikasi']) ."',
                        `id_kondisi` = '". $this->db->escape_str($f['id_kondisi']) ."',
                        `persentase_kondisi` = '". $this->db->escape_str($f['persentase_kondisi']) ."',
                        `harga` = '". $this->db->escape_str($f['harga']) ."',
                        `id_status` = '". $this->db->escape_str($f['id_status']) ."',
                        `keterangan` = '". $this->db->escape_str($f['keterangan']) ."'
                    WHERE
                        `id` = '". $this->db->escape_str($id) ."'
                    ;";
		}

		if ($this->db->simple_query($q)) {
			$result['result'] = true;
			$result['msg'] = 'Data berhasil disimpan.';
		} else{
			$result['msg'] = 'Terjadi kesalahan saat menyimpan data.';
		}

		return $result;
	}

	function delete($data = array())
	{
		$result = array(
			'result'    => false,
			'msg'		=> ''
		);

		$u = $data['userData'];
		$d = $data['postData'];
		$id = $d['id'];
		$q = "UPDATE `stock_laptop` SET `deleted_at` = NOW() WHERE `id` = '". $this->db->escape_str($id) ."';";
		if ($this->db->simple_query($q)) {
			$result['result'] = true;
			$result['msg'] = 'Data berhasil dihapus.';
		} else{
			$result['msg'] = 'Terjadi kesalahan saat menghapus data.';
		}

		return $result;
	}

    function select($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `stock_laptop` WHERE `deleted_at` IS NULL;";
        } else{
            $q = "SELECT * FROM `stock_laptop` WHERE `id` = '". $this->db->escape_str($id) ."' AND `deleted_at` IS NULL;";
        }
        $r = $this->db->query($q)->result_array();
        if (count($r) > 0) {
            $result['result'] = true;
            $result['data'] = $r;

            if (count($r) == 1 && $id != 0) {
                $result['data'] = $r[0];
            }
        }

        return $result;
    }

}