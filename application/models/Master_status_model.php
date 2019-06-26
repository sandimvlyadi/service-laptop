<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_status_model extends CI_Model {

    function _get($data = array())
    {
        $q = "SELECT a.*, b.`nama_kategori` FROM `status` a LEFT JOIN `status_kategori` b ON a.`id_kategori` = b.`id` ";

        if ($data['search']['value'] && !isset($data['all'])) {
            $s = $this->db->escape_str($data['search']['value']);
            $q .= "WHERE a.`status` LIKE '%". $s ."%' OR a.`keterangan` LIKE '%". $s ."%' OR b.`nama_kategori` LIKE '%". $s ."%' ";
        } else{
            
        }

        if (isset($data['order'])) {
            $dir = $this->db->escape_str($data['order'][0]['dir']);
            $col = $this->db->escape_str($data['columns'][$data['order'][0]['column']]['data']);
            if ($data['order'][0]['column'] != 0) {
                if ($col == 'nama_kategori') {
                    $q .= "ORDER BY b.`". $col ."` ". $dir ." ";
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
            'msg'       => 'Data status tidak ditemukan.'
        );

        $q =    "SELECT
                    a.*,
                    b.`nama_kategori`
                FROM
                    `status` a
                LEFT JOIN
                    `status_kategori` b
                        ON
                    a.`id_kategori` = b.`id`
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
            'msg'       => ''
        );

        $u = $data['userData'];
        $d = $data['postData'];
        $id = $d['id'];
        parse_str($d['form'], $f);

        $q = '';
        if ($id == 0) {
            $q =    "INSERT INTO
                        `status`
                        (
                            `status`,
                            `keterangan`,
                            `id_kategori`
                        )
                    VALUES
                        (
                            '". $this->db->escape_str($f['status']) ."',
                            '". $this->db->escape_str($f['keterangan']) ."',
                            '". $this->db->escape_str($f['id_kategori']) ."'
                        )
                    ;";
        } else{
            $q =    "UPDATE
                        `status`
                    SET
                        `status` = '". $this->db->escape_str($f['status']) ."',
                        `keterangan` = '". $this->db->escape_str($f['keterangan']) ."',
                        `id_kategori` = '". $this->db->escape_str($f['id_kategori']) ."'
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
            'msg'       => ''
        );

        $u = $data['userData'];
        $d = $data['postData'];
        $id = $d['id'];
        $q = "DELETE FROM `status` WHERE `id` = '". $this->db->escape_str($id) ."';";
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
            $q = "SELECT * FROM `status`;";
        } else{
            $q = "SELECT * FROM `status` WHERE `id` = '". $this->db->escape_str($id) ."';";
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

    function client($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 2;";
        } else{
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 2 AND `id` = '". $this->db->escape_str($id) ."';";
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

    function service($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 1;";
        } else{
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 1 AND `id` = '". $this->db->escape_str($id) ."';";
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

    function stock_laptop($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 3;";
        } else{
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 3 AND `id` = '". $this->db->escape_str($id) ."';";
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

    function stock_spare_part($id = 0)
    {
        $result = array(
            'result'    => false,
            'msg'       => ''
        );

        $q = "";
        if ($id == 0) {
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 4;";
        } else{
            $q = "SELECT * FROM `status` WHERE `id_kategori` = 4 AND `id` = '". $this->db->escape_str($id) ."';";
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
