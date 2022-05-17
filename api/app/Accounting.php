<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Accounting extends Utility
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
      case 'account_list':
        return self::account_list($parameter);
        break;
      case 'payment_method_list':
        return self::payment_method_list($parameter);
        break;
      case 'tambah_transact':
        return self::tambah_transact($parameter);
        break;
      case 'edit_transact':
        return self::edit_transact($parameter);
        break;
      case 'tambah_payment_method':
        return self::tambah_payment_method($parameter);
        break;
      case 'edit_payment_method':
        return self::edit_payment_method($parameter);
        break;
    }
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'account':
        return self::hapus_transact($parameter[7]);
        break;
      case 'payment_method':
        return self::payment_method($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'get_payment_method':
          return self::get_payment_method();
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function get_payment_method()
  {
    $data = self::$query->select('master_accounting_payment', array(
      'uid', 'kode', 'keterangan'
    ))
      ->where(array(
        'master_accounting_payment.deleted_at' => 'IS NULL'
      ), array())
      ->execute();

    return $data;
  }

  private function payment_method_detail($parameter)
  {
    $data = self::$query->select('master_accounting_payment', array(
      'kode', 'keterangan'
    ))
      ->where(array(
        'master_accounting_payment.uid' => '= ?'
      ), array($parameter))
      ->execute();

    return $data;
  }

  private function tambah_payment_method($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_accounting_payment', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'keterangan' => $parameter['nama'],
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
          'master_accounting_payment',
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

  private function tambah_transact($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('master_accounting_transact', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'dbcr' => $parameter['dbcr'],
      'keterangan' => $parameter['keterangan'],
      'apply_tax' => $parameter['tax'],
      'apply_service' => $parameter['service'],
      'account_code' => $parameter['akun'],
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
          'master_accounting_transact',
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

  private function detail_transact($parameter)
  {
    $data = self::$query->select('master_accounting_transact', array(
      'uid', 'kode', 'dbcr', 'account_code', 'keterangan', 'apply_tax', 'apply_service'
    ))
      ->where(array(
        'master_accounting_transact.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    return $data;
  }

  private function payment_method($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('master_accounting_payment')
      ->where(array(
        'master_accounting_payment.uid' => '= ?'
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
            'master_accounting_payment',
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

  private function hapus_transact($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('master_accounting_transact')
      ->where(array(
        'master_accounting_transact.uid' => '= ?'
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
            'master_accounting_transact',
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

  private function edit_payment_method($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::payment_method_detail($parameter['uid']);
    $data = self::$query->update('master_accounting_payment', array(
      'kode' => $parameter['kode'],
      'keterangan' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_accounting_payment.uid' => '= ?'
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
          'master_accounting_payment',
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

  private function edit_transact($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_transact($parameter['uid']);
    $data = self::$query->update('master_accounting_transact', array(
      'kode' => $parameter['kode'],
      'dbcr' => $parameter['dbcr'],
      'keterangan' => $parameter['keterangan'],
      'apply_tax' => $parameter['tax'],
      'apply_service' => $parameter['service'],
      'account_code' => $parameter['akun'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'master_accounting_transact.uid' => '= ?'
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
          'master_accounting_transact',
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

  private function payment_method_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(master_accounting_payment.keterangan' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'master_accounting_payment.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'master_accounting_payment.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_accounting_payment.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_accounting_payment', array(
        'uid', 'kode', 'keterangan'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_accounting_payment', array(
        'uid', 'kode', 'keterangan'
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

    $itemTotal = self::$query->select('master_accounting_payment', array(
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

  private function account_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(master_accounting_transact.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'master_accounting_transact.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'master_accounting_transact.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'master_accounting_transact.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('master_accounting_transact', array(
        'uid', 'kode', 'dbcr', 'account_code', 'keterangan', 'apply_tax', 'apply_service'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('master_accounting_transact', array(
        'uid', 'kode', 'dbcr', 'account_code', 'keterangan', 'apply_tax', 'apply_service'
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

    $itemTotal = self::$query->select('master_accounting_transact', array(
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
