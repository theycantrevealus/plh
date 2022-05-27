<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Outlet extends Utility
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
      case 'outlet_order_current':
        return self::outlet_order_current($parameter);
        break;
      case 'outlet_order_history':
        return self::outlet_order_history($parameter);
        break;
      case 'outlet_list':
        return self::outlet_list($parameter);
        break;
      case 'outlet_item_list':
        return self::outlet_item_list($parameter);
        break;
      case 'outlet_table_list':
        return self::outlet_table_list($parameter);
        break;
      case 'outlet_employee_list':
        return self::outlet_employee_list($parameter);
        break;
      case 'tambah_outlet':
        return self::tambah_outlet($parameter);
        break;
      case 'edit_outlet':
        return self::edit_outlet($parameter);
        break;
      case 'tambah_item':
        return self::tambah_item($parameter);
        break;
      case 'edit_item':
        return self::edit_item($parameter);
        break;
      case 'tambah_pegawai':
        return self::tambah_pegawai($parameter);
        break;
      case 'load_item_per_outlet':
        return self::load_item_per_outlet($parameter);
        break;
      case 'tambah_meja':
        return self::tambah_meja($parameter);
        break;
      case 'load_table_outlet':
        return self::load_table_outlet($parameter);
        break;
      case 'add_order':
        return self::add_order($parameter);
        break;
      case 'check_out_payment':
        return self::check_out_payment($parameter);
        break;
      default:
        return $parameter;
    }
  }

  public function __DELETE__($parameter = array())
  {
    switch ($parameter[6]) {
      case 'outlet':
        return self::hapus_outlet($parameter[7]);
        break;
      case 'item':
        return self::hapus_item($parameter[7]);
        break;
      case 'pegawai':
        return self::hapus_pegawai($parameter[7]);
        break;
      case 'meja':
        return self::hapus_meja($parameter[7]);
        break;
      default:
        return $parameter;
    }
  }

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'outlet':
          return self::load_outlet();
          break;
        case 'outlet_detail':
          return self::outlet_detail($parameter[2]);
          break;
        case 'order_detail':
          return self::order_detail($parameter[2]);
          break;
        case 'load_my_outlet':
          return self::load_my_outlet($parameter);
          break;
        case 'detail':
          return self::detail_outlet($parameter[2]);
          break;
        default:
          return $parameter;
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function hapus_meja($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('outlet_table')
      ->where(array(
        'outlet_table.uid' => '= ?'
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
            'outlet_table',
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

  private function hapus_pegawai($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('outlet_pegawai')
      ->where(array(
        'outlet_pegawai.id' => '= ?'
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
            'outlet_pegawai',
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

  private function hapus_item($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('outlet_item')
      ->where(array(
        'outlet_item.uid' => '= ?'
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
            'outlet_item',
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

  private function hapus_outlet($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->delete('outlet')
      ->where(array(
        'outlet.uid' => '= ?'
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
            'outlet',
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

  private function check_out_payment($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $orderDetail = self::order_detail($parameter['order']);
    $chargeMethod = array();
    $saveMethod = array(
      'remark' => $parameter['remark'],
      'updated_at' => parent::format_date()
    );

    if (floatval($parameter['cash_pay']) > 0) {
      array_push($chargeMethod, 'cash');
      $saveMethod['cash_pay'] = floatval($parameter['cash_pay']);
    }
    if (floatval($parameter['cc_pay']) > 0) {
      array_push($chargeMethod, 'cc');
      $saveMethod['cc_pay'] = floatval($parameter['cc_pay']);
      $saveMethod['credit_card_num'] = $parameter['cc_num'];
      $saveMethod['credit_card_valid'] = $parameter['cc_valid'];
      $saveMethod['cc_com'] = $parameter['cc_bank'];
    }
    if (floatval($parameter['gl_pay']) > 0) {
      array_push($chargeMethod, 'gl');
      $saveMethod['gl_pay'] = floatval($parameter['gl_pay']);
      $saveMethod['reservasi'] = $parameter['gl_room'];
    }
    if (floatval($parameter['cl_pay']) > 0) {
      array_push($chargeMethod, 'cc');
      $saveMethod['cl_pay'] = floatval($parameter['cl_pay']);
      $saveMethod['company'] = $parameter['cl_com'];
    }

    $saveMethod['charge_method'] = implode(',', $chargeMethod);

    $set = self::$query->update('outlet_order', $saveMethod)
      ->where(array(
        'outlet_order.uid' => '= ?'
      ), array(
        $parameter['order']
      ))
      ->execute();

    if ($set['response_result'] > 0) {
      if (floatval($parameter['gl_pay']) > 0) {
        $outInf = self::outlet_detail($parameter['outlet']);

        $Fol = self::$query->select('folio', array(
          'uid', 'balance'
        ))
          ->where(array(
            'folio.reservasi' => '= ?'
          ), array(
            $parameter['gl_room']
          ))
          ->execute();

        $FolDetail = self::$query->insert('folio_transact', array(
          'folio' => $Fol['response_data'][0]['uid'],
          'transcode' => $outInf['response_data'][0]['trans'],
          'qty' => 1,
          'price' => floatval($parameter['gl_pay']),
          'remark' => $orderDetail['response_data'][0]['nomor'],
          'deskripsi' => 'Biaya dari outlet [' . $outInf['response_data'][0]['kode'] . '-' . $outInf['response_data'][0]['nama'] . ']',
          'add_by' => $UserData['data']->uid,
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date(),
          'tax_value' => __TAX_VAL__,
          'service_value' => __SER_VAL__,
          'final_price' => floatval($parameter['gl_pay'])
        ))
          ->execute();

        if ($FolDetail['response_result'] > 0) {
          $checkTrans = self::$query->select('master_accounting_transact', array(
            'kode', 'dbcr', 'account_code', 'apply_tax', 'apply_service', 'keterangan'
          ))
            ->where(array(
              'master_accounting_transact.uid' => '= ?'
            ), array(
              $outInf['response_data'][0]['trans']
            ))
            ->execute()['response_data'][0];
          $fixBalance = ($checkTrans['dbcr'] === 'D') ? (floatval($Fol['response_data'][0]['balance']) + floatval($parameter['gl_pay'])) : (floatval($Fol['response_data'][0]['balance']) - floatval($parameter['gl_pay']));
          $FolioUp = self::$query->update('folio', array(
            'balance' => $fixBalance
          ))
            ->where(array(
              'folio.uid' => '= ?'
            ), array(
              $Fol['response_data'][0]['uid']
            ))
            ->execute();
        }
      }
    }

    return $set;
  }

  private function add_order($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $lastNumber = self::$query->select('outlet_order', array(
      'uid'
    ))
      ->where(array(
        'EXTRACT(month FROM created_at)' => '= ?'
      ), array(
        intval(date('m'))
      ))
      ->execute();

    $uid = parent::gen_uuid();
    $outDet = self::detail_outlet($parameter['outlet']);
    if (!empty($parameter['table'])) {
      $checkLatestOrder = self::$query->select('outlet_order', array(
        'uid'
      ))
        ->where(array(
          'outlet_order.meja' => '= ?',
          'AND',
          'outlet_order.charge_method' => 'IS NULL'
        ), array(
          $parameter['table']
        ))
        ->execute();
      if (count($checkLatestOrder['response_data']) > 0) {
        $uid = $checkLatestOrder['response_data'][0]['uid'];
        $data = self::$query->update('outlet_order', array(
          'updated_at' => parent::format_date()
        ))
          ->where(array(
            'outlet_order.uid' => '= ?'
          ), array(
            $uid
          ))
          ->execute();
      } else {
        $data = self::$query->insert('outlet_order', array(
          'uid' => $uid,
          'nomor' => $outDet['response_data'][0]['kode'] . '/' . date('Y/m') . '/' . str_pad(strval(count($lastNumber['response_data']) + 1), 4, '0', STR_PAD_LEFT),
          'outlet' => $parameter['outlet'],
          'meja' => $parameter['table'],
          'pegawai' => $UserData['data']->uid,
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();
      }
    } else {
      $data = self::$query->insert('outlet_order', array(
        'uid' => $uid,
        'nomor' => '',
        'outlet' => $parameter['outlet'],
        'pegawai' => $UserData['data']->uid,
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
    }

    if ($data['response_result'] > 0) {
      foreach ($parameter['item'] as $key => $value) {
        $child = self::$query->insert('outlet_order_detail', array(
          'order_uid' => $uid,
          'item' => $key,
          'qty' => floatval($value['qty']),
          'price' => floatval($value['price']),
          'subtotal' => floatval($value['qty']) * floatval($value['price']),
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();
      }
    }
    return $data;
  }

  private function load_table_outlet($parameter)
  {
    $data = self::$query->select('outlet_table', array(
      'uid', 'kode'
    ))
      ->where(array(
        'outlet_table.outlet' => '= ?',
        'AND',
        'outlet_table.deleted_at' => 'IS NULL'
      ), array(
        $parameter['outlet']
      ))
      ->execute();

    return $data;
  }

  private function load_item_per_outlet($parameter)
  {
    $data = self::$query->select('outlet_item', array(
      'uid', 'nama', 'price'
    ))
      ->where(array(
        'outlet_item.nama' => 'ILIKE ' . '\'%' . $parameter['search'] . '%\'',
        'AND',
        'outlet_item.outlet' => '= ?',
        'AND',
        'outlet_item.deleted_at' => 'IS NULL'
      ), array(
        $parameter['outlet']
      ))
      ->execute();

    return $data;
  }

  private function tambah_meja($parameter)
  {
    $data = self::$query->insert('outlet_table', array(
      'uid' => parent::gen_uuid(),
      'outlet' => $parameter['outlet'],
      'kode' => $parameter['tableCode'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();
    return $data;
  }

  private function tambah_pegawai($parameter)
  {
    $check = self::$query->select('outlet_pegawai', array(
      'id'
    ))
      ->where(array(
        'outlet_pegawai.pegawai' => '= ?',
        'AND',
        'outlet_pegawai.outlet' => '= ?'
      ), array(
        $parameter['pegawai'], $parameter['outlet']
      ))
      ->execute();
    if (count($check['response_data']) > 0) {
      $set = self::$query->update('outlet_pegawai', array(
        'deleted_at' => NULL
      ))
        ->where(array(
          'outlet_pegawai.pegawai' => '= ?',
          'AND',
          'outlet_pegawai.outlet' => '= ?'
        ), array(
          $parameter['pegawai'], $parameter['outlet']
        ))
        ->execute();
    } else {
      $set = self::$query->insert('outlet_pegawai', array(
        'outlet' => $parameter['outlet'],
        'pegawai' => $parameter['pegawai'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
    }

    return $set;
  }

  private function edit_item($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $data = self::$query->update('outlet_item', array(
      'nama' => $parameter['nama'],
      'price' => $parameter['harga'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'outlet_item.uid' => '= ?',
        'AND',
        'outlet_item.outlet' => '= ?'
      ), array(
        $parameter['uid'], $parameter['outlet']
      ))
      ->execute();
    return $data;
  }

  private function tambah_item($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $data = self::$query->insert('outlet_item', array(
      'uid' => $uid,
      'outlet' => $parameter['outlet'],
      'nama' => $parameter['nama'],
      'price' => $parameter['harga'],
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();
    return $data;
  }

  private function tambah_outlet($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();
    $TUID = parent::gen_uuid();
    $data = self::$query->insert('outlet', array(
      'uid' => $uid,
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'trans' => $TUID,
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();
    if ($data['response_result'] > 0) {
      $TrC = self::$query->insert('master_accounting_transact', array(
        'uid' => $TUID,
        'kode' => $parameter['kode'],
        'keterangan' => $parameter['nama'],
        'dbcr' => 'D',
        'account_code' => '-',
        'apply_tax' => 'Y',
        'apply_service' => 'Y',
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
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
          'outlet',
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

  private function order_detail($parameter)
  {
    $data = self::$query->select('outlet_order', array(
      'uid', 'nomor', 'outlet', 'meja', 'pegawai'
    ))
      ->join('pegawai', array(
        'nama'
      ))
      ->on(array(
        array('outlet_order.pegawai', '=', 'pegawai.uid')
      ))
      ->where(array(
        'outlet_order.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    foreach ($data['response_data'] as $key => $value) {
      $meja = self::$query->select('outlet_table', array(
        'kode'
      ))
        ->where(array(
          'outlet_table.uid' => '= ?'
        ), array(
          $value['meja']
        ))
        ->execute();
      $data['response_data'][$key]['meja'] = $meja['response_data'][0];

      $detail = self::$query->select('outlet_order_detail', array(
        'item', 'qty', 'price', 'subtotal'
      ))
        ->join('outlet_item', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order_detail.item', '=', 'outlet_item.uid')
        ))
        ->where(array(
          'outlet_order_detail.order_uid' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      $data['response_data'][$key]['detail'] = $detail['response_data'];
    }
    return $data;
  }

  private function detail_outlet($parameter)
  {
    $data = self::$query->select('outlet', array(
      'uid', 'kode', 'nama'
    ))
      ->where(array(
        'outlet.uid' => '= ?'
      ), array($parameter))
      ->execute();

    return $data;
  }

  private function edit_outlet($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::detail_outlet($parameter['uid']);
    $data = self::$query->update('outlet', array(
      'kode' => $parameter['kode'],
      'nama' => $parameter['nama'],
      'updated_at' => parent::format_date()
    ))
      ->where(array(
        'outlet.uid' => '= ?'
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
          'outlet',
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

  private function load_my_outlet($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $data = self::$query->select('outlet_pegawai', array(
      'id', 'pegawai', 'outlet'
    ))
      ->join('outlet', array(
        'kode', 'nama'
      ))
      ->on(array(
        array('outlet_pegawai.outlet', '=', 'outlet.uid')
      ))
      ->where(array(
        'outlet_pegawai.deleted_at' => 'IS NULL',
        'AND',
        'outlet_pegawai.pegawai' => '= ?'
      ), array(
        $UserData['data']->uid
      ))
      ->execute();
    return $data;
  }

  private function outlet_detail($parameter)
  {
    $data = self::$query->select('outlet', array(
      'uid', 'kode', 'nama', 'trans'
    ))
      ->where(array(
        'outlet.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    return $data;
  }

  private function load_outlet()
  {
    $data = self::$query->select('outlet', array(
      'uid', 'kode', 'nama'
    ))
      ->where(array(
        'outlet.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    return $data;
  }

  private function outlet_table_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'outlet_table.kode' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet_table.deleted_at' => 'IS NULL',
        'AND',
        'outlet_table.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    } else {
      $paramData = array(
        'outlet_table.deleted_at' => 'IS NULL',
        'AND',
        'outlet_table.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet_table', array(
        'uid', 'kode'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet_table', array(
        'uid', 'kode'
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

    $itemTotal = self::$query->select('outlet_table', array(
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

  private function outlet_employee_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'pegawai.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet_pegawai.deleted_at' => 'IS NULL',
        'AND',
        'outlet_pegawai.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    } else {
      $paramData = array(
        'outlet_pegawai.deleted_at' => 'IS NULL',
        'AND',
        'outlet_pegawai.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet_pegawai', array(
        'id', 'pegawai'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_pegawai.pegawai', '=', 'pegawai.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet_pegawai', array(
        'id', 'pegawai'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_pegawai.pegawai', '=', 'pegawai.uid')
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

    $itemTotal = self::$query->select('outlet_pegawai', array(
      'id'
    ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function outlet_item_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'outlet_item.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet_item.deleted_at' => 'IS NULL',
        'AND',
        'outlet_item.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    } else {
      $paramData = array(
        'outlet_item.deleted_at' => 'IS NULL',
        'AND',
        'outlet_item.outlet' => '= ?'
      );
      $paramValue = array($parameter['outlet']);
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet_item', array(
        'uid', 'price', 'nama'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet_item', array(
        'uid', 'price', 'nama'
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

    $itemTotal = self::$query->select('outlet_item', array(
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

  private function outlet_order_history($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'outlet_order.nomor' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet_order.charge_method' => 'IS NOT NULL',
        'AND',
        'outlet_order.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'outlet_order.deleted_at' => 'IS NULL',
        'AND',
        'outlet_order.charge_method' => 'IS NOT NULL',
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet_order', array(
        'uid', 'nomor', 'outlet', 'meja', 'pegawai', 'charge_method', 'created_at', 'cash_pay', 'cc_pay', 'gl_pay', 'cl_pay'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order.pegawai', '=', 'pegawai.uid')
        ))
        ->order(array(
          'created_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet_order', array(
        'uid', 'nomor', 'outlet', 'meja', 'pegawai', 'charge_method', 'created_at', 'cash_pay', 'cc_pay', 'gl_pay', 'cl_pay'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order.pegawai', '=', 'pegawai.uid')
        ))
        ->order(array(
          'created_at' => 'DESC'
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
      $meja = self::$query->select('outlet_table', array(
        'kode'
      ))
        ->where(array(
          'outlet_table.uid' => '= ?'
        ), array(
          $value['meja']
        ))
        ->execute();
      $data['response_data'][$key]['meja'] = $meja['response_data'][0];
      $data['response_data'][$key]['created_at'] = date('d F Y', strtotime($value['created_at']));

      $detail = self::$query->select('outlet_order_detail', array(
        'item', 'qty', 'price', 'subtotal'
      ))
        ->join('outlet_item', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order_detail.item', '=', 'outlet_item.uid')
        ))
        ->where(array(
          'outlet_order_detail.order_uid' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      $totalSet = 0;
      foreach ($detail['response_data'] as $ODK => $ODV) {
        $totalSet += floatval($ODV['subtotal']);
      }

      $data['response_data'][$key]['detail'] = $detail['response_data'];
      $data['response_data'][$key]['total'] = $totalSet * 1.21;
      $autonum++;
    }

    $itemTotal = self::$query->select('outlet_order', array(
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

  private function outlet_order_current($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'outlet_order.nomor' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet_order.charge_method' => 'IS NULL',
        'AND',
        'outlet_order.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'outlet_order.deleted_at' => 'IS NULL',
        'AND',
        'outlet_order.charge_method' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet_order', array(
        'uid', 'nomor', 'outlet', 'meja', 'pegawai'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order.pegawai', '=', 'pegawai.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet_order', array(
        'uid', 'nomor', 'outlet', 'meja', 'pegawai'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('outlet_order.pegawai', '=', 'pegawai.uid')
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
      $meja = self::$query->select('outlet_table', array(
        'kode'
      ))
        ->where(array(
          'outlet_table.uid' => '= ?'
        ), array(
          $value['meja']
        ))
        ->execute();
      $data['response_data'][$key]['meja'] = $meja['response_data'][0];
      $autonum++;
    }

    $itemTotal = self::$query->select('outlet_order', array(
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

  private function outlet_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'outlet.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'outlet.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'outlet.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('outlet', array(
        'uid', 'kode', 'nama'
      ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('outlet', array(
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

    $itemTotal = self::$query->select('outlet', array(
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
