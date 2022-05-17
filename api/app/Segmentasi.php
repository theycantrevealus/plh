<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Segmentasi extends Utility
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
      case 'segmentasi_list';
        return self::segmentasi_list($parameter);
        break;
      case 'tambah_segmentasi':
        return self::tambah_segmentasi($parameter);
        break;
      case 'edit_segmentasi':
        return self::edit_segmentasi($parameter);
        break;
      default:
        return array();
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'get_segmentasi':
          return self::get_segmentasi();
          break;
        default:
          return array();
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'master':
        return self::hapus_master_segmentasi($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  private function get_segmentasi()
  {
    $data = self::$query->select('segmentasi', array(
      'uid', 'msscode', 'deskripsi'
    ))
      ->where(array(
        'segmentasi.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    return $data;
  }

  private function tambah_segmentasi($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('segmentasi', array(
      'uid' => $uid,
      'msscode' => $parameter['kode'],
      'deskripsi' => $parameter['nama'],
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
          'segmentasi',
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

  private function detail_segmentasi($parameter)
  {
    $data = self::$query->select('segmentasi', array(
      'uid', 'msscode', 'deskripsi'
    ))
      ->where(array(
        'segmentasi.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    return $data;
  }

  private function edit_segmentasi($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_segmentasi($parameter['uid']);
    $data = self::$query->update('segmentasi', array(
      'msscode' => $parameter['kode'],
      'deskripsi' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'segmentasi.uid' => '= ?'
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
          'segmentasi',
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

  private function hapus_master_segmentasi($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('segmentasi')
      ->where(array(
        'segmentasi.uid' => '= ?'
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
            'segmentasi',
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

  private function segmentasi_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(segmentasi.msscode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'segmentasi.deskripsi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'segmentasi.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'segmentasi.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('segmentasi', array(
        'uid', 'msscode', 'deskripsi'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('segmentasi', array(
        'uid', 'msscode', 'deskripsi'
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

    $itemTotal = self::$query->select('segmentasi', array(
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
