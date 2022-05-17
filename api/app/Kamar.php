<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Kamar extends Utility
{
  static $pdo;
  static $query;

  protected static function getConn()
  {
    return self::$pdo;
  }

  public function __construct($connection)
  {
    self::$pdo = $connection;
    self::$query = new Query(self::$pdo);
  }

  public function __POST__($parameter = array())
  {
    switch ($parameter['request']) {
      case 'tipe_list':
        return self::tipe_list($parameter);
        break;
      case 'kamar_list':
        return self::kamar_list($parameter);
        break;
      case 'kamar_list_status':
        return self::kamar_list_status($parameter);
        break;
      case 'tambah_kamar':
        return self::tambah_kamar($parameter);
        break;
      case 'edit_kamar':
        return self::edit_kamar($parameter);
        break;
      case 'tambah_tipe':
        return self::tambah_tipe($parameter);
        break;
      case 'edit_tipe':
        return self::edit_tipe($parameter);
        break;
    }
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'tipe':
        return self::hapus_tipe($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'tipe':
          return self::load_tipe();
          break;
        case 'detail':
          return self::detail($parameter[2]);
          break;
        default:
          return array();
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function hapus_tipe($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('master_kamar_tipe')
      ->where(array(
        'master_kamar_tipe.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    if ($data['response_result'] > 0) {
      $log = parent::log(
        array(
          'type' => 'activity',
          'column' => array(
            'unique_target',
            'user_uid',
            'table_name',
            'action',
            'logged_at',
            'status',
            'login_id'
          ),
          'value' => array(
            $parameter,
            $UserData['data']->uid,
            'master_kamar_tipe',
            'D',
            parent::format_date(),
            'N',
            $UserData['data']->log_id
          ),
          'class' => __CLASS__
        )
      );
    }
    return $data;
  }

  private function tambah_tipe($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_kamar_tipe', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();
    if ($data['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $uid,
          $UserData['data']->uid,
          'master_kamar_tipe',
          'I',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }

    return $data;
  }

  private function detail_tipe($parameter)
  {
    $data = self::$query->select('master_kamar_tipe')
      ->where(array(
        'master_kamar_tipe.uid' => '= ?'
      ), array($parameter))
      ->execute();

    return $data;
  }

  private function edit_tipe($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_tipe($parameter['uid']);
    $data = self::$query->update('master_kamar_tipe', array(
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kamar_tipe.uid' => '= ?'
      ), array($parameter['uid']))
      ->execute();
    if ($data['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kamar_tipe',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }

    return $data;
  }

  private function edit_kamar($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail($parameter['uid']);
    $data = self::$query->update('master_kamar', array(
      'nomor' => $parameter['nomor'],
      'tipe' => $parameter['tipe'],
      'keterangan' => $parameter['keterangan'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kamar.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();
    if ($data['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'old_value',
          'new_value',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kamar_tipe',
          'U',
          json_encode($old['response_data'][0]),
          json_encode($parameter),
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $data;
  }

  private function tambah_kamar($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_kamar', array(
      'uid' => $uid,
      'nomor' => $parameter['nomor'],
      'tipe' => $parameter['tipe'],
      'keterangan' => $parameter['keterangan'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();

    if ($data['response_result'] > 0) {
      $log = parent::log(array(
        'type' => 'activity',
        'column' => array(
          'unique_target',
          'user_uid',
          'table_name',
          'action',
          'logged_at',
          'status',
          'login_id'
        ),
        'value' => array(
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kamar',
          'I',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));
    }
    return $data;
  }

  private function kamar_list_status($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(master_kamar.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'master_kamar_tipe.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.tipe' => '= ?',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      );
      $paramValue = array($parameter['status'], $parameter['tipe']);
    } else {
      $paramData = array(
        'master_kamar.deleted_at' => 'IS NULL',
        'AND',
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.tipe' => '= ?'
      );
      $paramValue = array($parameter['status'], $parameter['tipe']);
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kamar', array(
        'uid', 'nomor', 'tipe', 'keterangan', 'status'
      ))
        ->join('master_kamar_tipe', array(
          'nama', 'kode'
        ))
        ->on(array(
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kamar', array(
        'uid', 'nomor', 'tipe', 'keterangan', 'status'
      ))
        ->join('master_kamar_tipe', array(
          'nama', 'kode'
        ))
        ->on(array(
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid')
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('master_kamar_tipe', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function kamar_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(master_kamar.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'master_kamar_tipe.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_kamar.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kamar', array(
        'uid', 'nomor', 'tipe', 'keterangan'
      ))
        ->join('master_kamar_tipe', array(
          'nama', 'kode'
        ))
        ->on(array(
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kamar', array(
        'uid', 'nomor', 'tipe', 'keterangan'
      ))
        ->join('master_kamar_tipe', array(
          'nama', 'kode'
        ))
        ->on(array(
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid')
        ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('master_kamar_tipe', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function detail($parameter)
  {
    $data = self::$query->select('master_kamar', array(
      'uid', 'nomor', 'tipe', 'keterangan'
    ))
      ->join('master_kamar_tipe', array(
        'nama', 'kode'
      ))
      ->on(array(
        array('master_kamar.tipe', '=', 'master_kamar_tipe.uid')
      ))
      ->where(array(
        'master_kamar.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    return $data;
  }

  private function load_tipe()
  {
    $data = self::$query->select('master_kamar_tipe', array(
      'uid', 'kode', 'nama'
    ))
      ->where(array(
        'master_kamar_tipe.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    return $data;
  }

  private function tipe_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kamar_tipe.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'master_kamar_tipe.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_kamar_tipe.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kamar_tipe', array(
        'uid', 'kode', 'nama'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kamar_tipe', array(
        'uid', 'kode', 'nama'
      ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('mater_kamar_tipe', array(
      'uid'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }
}
