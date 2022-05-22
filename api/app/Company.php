<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Company extends Utility
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
      case 'list_company':
        return self::list_company($parameter);
        break;
      case 'tambah_company':
        return self::tambah_company($parameter);
        break;
      case 'edit_company':
        return self::edit_company($parameter);
        break;
    }
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'company':
        return self::hapus_company($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'list_company':
          return self::company_list();
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function detail_company($parameter)
  {
    $data = self::$query->select('company')
      ->where(array(
        'company.uid' => '= ?'
      ), array($parameter))
      ->execute();

    return $data;
  }

  private function hapus_company($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('company')
      ->where(array(
        'company.uid' => '= ?'
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
            'company',
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

  private function edit_company($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_company($parameter['uid']);
    $data = self::$query->update('company', array(
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'email' => $parameter['email'],
      'phone' => $parameter['phone'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'company.uid' => '= ?'
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
          'company',
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

  private function tambah_company($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('company', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'email' => $parameter['email'],
      'phone' => $parameter['phone'],
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
          'company',
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

  private function list_company($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(company.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'company.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'company.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'company.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('company', array(
        'uid', 'kode', 'nama', 'email', 'phone'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('company', array(
        'uid', 'kode', 'nama', 'email', 'phone'
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

    $itemTotal = self::$query->select('company', array(
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

  private function company_list()
  {
    $data = self::$query->select('company', array(
      'uid', 'kode', 'nama', 'email', 'phone'
    ))
      ->where(array(
        'company.deleted_at' => 'IS NULL',
        'AND',
        '(company.nama' => 'ILIKE ' . '\'%' . strtoupper($_GET['search']) . '%\'',
        'OR',
        'company.kode' => 'ILIKE ' . '\'%' . strtoupper($_GET['search']) . '%\')'
      ), array())
      ->execute();
    return $data;
  }
}
