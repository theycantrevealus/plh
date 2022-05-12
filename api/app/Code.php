<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Code extends Utility
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

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'add':
        return self::hapus_add_code($parameter[7]);
        break;
      case 'rate':
        return self::hapus_add_rate($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  public function __POST__($parameter = array())
  {
    switch ($parameter['request']) {
      case 'add_code_list':
        return self::add_code_list($parameter);
        break;
      case 'rate_code_list':
        return self::rate_code_list($parameter);
        break;
      case 'tambah_add_code':
        return self::tambah_add_code($parameter);
        break;
      case 'tambah_rate_code':
        return self::tambah_rate_code($parameter);
        break;
      case 'edit_rate_code':
        return self::edit_rate_code($parameter);
        break;
      case 'edit_add_code':
        return self::edit_add_code($parameter);
        break;
    }
  }

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'rate_detail':
          return self::rate_detail($parameter[2]);
          break;
        default:
          return array();
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function rate_detail($parameter)
  {
    $data = self::$query->select('master_kamar_rate', array(
      'uid',
      'kode',
      'harga',
      'keterangan'
    ))
      ->where(array('master_kamar_rate.uid' => '= ?'), array($parameter))
      ->execute();

    foreach ($data['response_data'] as $key => $value) {
      $detail = self::$query->select('master_kamar_rate_detail', array(
        'rate_code', 'add_code', 'harga'
      ))
        ->join('master_kamar_rate_item', array(
          'kode as kode_add',
          'nama as nama_add'
        ))
        ->on(array(
          array('master_kamar_rate_detail.add_code', '=', 'master_kamar_rate_item.uid')
        ))
        ->where(array(
          'master_kamar_rate_detail.rate_code' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      $data['response_data'][$key]['detail'] = $detail['response_data'];
    }
    return $data;
  }

  private function hapus_add_rate($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('master_kamar_rate')
      ->where(array(
        'master_kamar_rate.uid' => '= ?'
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
            'master_kamar_rate',
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

  private function hapus_add_code($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('master_kamar_rate_item')
      ->where(array(
        'master_kamar_rate_item.uid' => '= ?'
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
            'master_kamar_rate_item',
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

  private function edit_rate_code($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $data = self::$query->update('master_kamar_rate', array(
      'kode' => $parameter['kode'],
      'harga' => $parameter['harga'],
      'keterangan' => $parameter['keterangan'],
      'updated_at' => parent::format_date()
    ))
      ->where(array('master_kamar_rate.uid' => '= ?'), array($parameter['uid']))
      ->execute();
    if ($data['response_result'] > 0) {
      $reset = self::$query->delete('master_kamar_rate_detail')
        ->where(array('master_kamar_rate_detail.rate_code' => '= ?'), array($parameter['uid']))
        ->execute();
      foreach ($parameter['add_code'] as $key => $value) {
        $check = self::$query->select('master_kamar_rate_detail', array('id'))
          ->where(array(
            'master_kamar_rate_detail.rate_code' => '= ?',
            'AND',
            'master_kamar_rate_detail.add_code' => '= ?'
          ), array(
            $parameter['uid'], $key
          ))
          ->execute();
        if (count($check['response_data']) > 0) {
          $set = self::$query->update('master_kamar_rate_detail', array(
            'harga' => floatval($value),
            'updated_at' => parent::format_date(),
            'deleted_at' => null
          ))
            ->where(array('master_kamar_rate_detail.id' => '= ?'),  array($check['response_data'][0]['id']))
            ->execute();
        } else {
          $set = self::$query->insert('master_kamar_rate_detail', array(
            'rate_code' => $parameter['uid'],
            'add_code' => $key,
            'harga' => floatval($value),
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
          ))
            ->execute();
        }
      }
    }

    return $data;
  }

  private function tambah_rate_code($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_kamar_rate', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'harga' => $parameter['harga'],
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
          'master_kamar_rate',
          'I',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));

      //Rate Detail
      foreach ($parameter['add_code'] as $key => $value) {
        $set = self::$query->insert('master_kamar_rate_detail', array(
          'rate_code' => $uid,
          'add_code' => $key,
          'harga' => floatval($value),
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();
      }
    }

    return $data;
  }

  private function tambah_add_code($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_kamar_rate_item', array(
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
          $parameter['uid'],
          $UserData['data']->uid,
          'master_kamar_rate_item',
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

  private function edit_add_code($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_add_code($parameter['uid']);
    $data = self::$query->update('master_kamar_rate_item', array(
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_kamar_rate_item.uid' => '= ?'
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
          'master_kamar_rate_item',
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

  private function detail_add_code($parameter)
  {
    $data = self::$query->select('master_kamar_rate_item')
      ->where(array(
        'master_kamar_rate_item.uid' => '= ?'
      ), array($parameter))
      ->execute();

    return $data;
  }

  private function rate_code_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kamar_rate.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'master_kamar_rate.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_kamar_rate.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kamar_rate', array(
        'uid', 'kode', 'harga'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kamar_rate', array(
        'uid', 'kode', 'harga'
      ))
        ->where($paramData, $paramValue)
        ->offset(intval($parameter['start']))
        ->limit(intval($parameter['length']))
        ->execute();
    }

    $data['response_draw'] = $parameter['draw'];
    $autonum = intval($parameter['start']) + 1;
    foreach ($data['response_data'] as $key => $value) {
      $detail = self::$query->select('master_kamar_rate_detail', array(
        'harga'
      ))
        ->join('master_kamar_rate_item', array('kode as kode_add', 'nama as nama_add'))
        ->on(array(
          array('master_kamar_rate_detail.add_code', '=', 'master_kamar_rate_item.uid')
        ))
        ->where(array(
          'master_kamar_rate_detail.rate_code' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      $data['response_data'][$key]['detail']  = $detail['response_data'];
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    $itemTotal = self::$query->select('master_kamar_rate', array(
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

  private function add_code_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'master_kamar_rate_item.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'master_kamar_rate_item.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_kamar_rate_item.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_kamar_rate_item', array(
        'uid', 'kode', 'nama'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_kamar_rate_item', array(
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

    $itemTotal = self::$query->select('master_kamar_rate_item', array(
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
