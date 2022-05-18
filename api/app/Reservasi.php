<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Reservasi extends Utility
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
      case 'tambah_reservasi':
        return self::tambah_reservasi($parameter);
        break;
      case 'reservasi_list':
        return self::reservasi_list($parameter);
        break;
      case 'reservasi_list_history':
        return self::reservasi_list_history($parameter);
        break;
      case 'edit_reservasi':
        return self::edit_reservasi($parameter);
        break;
    }
  }


  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'detail':
          return self::reservasi_detail($parameter[2]);
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function reservasi_detail($parameter)
  {
    $data = self::$query->select('reservasi', array(
      'uid',
      'no_reservasi',
      'id_number',
      'check_in',
      'check_out',
      'status',
      'tipe_kamar',
      'block',
      'kamar',
      'deposit',
      'customer',
      'pax',
      'vip',
      'company',
      'rate_code',
      'rate_value',
      'metode_payment',
      'card_number',
      'card_valid_until',
      'segmentasi',
      'nationality',
      'state',
      'reservation_contact',
      'reserved_by',
      'checked_in_by',
      'checked_out_by',
      'cashier_remark',
      'check_in_remark',
      'created_at',
      'updated_at'
    ))
      ->join('master_kamar_tipe', array(
        'kode as kode_tipe',
        'nama as nama_tipe'
      ))
      ->join('segmentasi', array(
        'msscode', 'deskripsi as nama_segmentasi'
      ))
      ->join('company', array(
        'nama as nama_company', 'kode as kode_company'
      ))
      ->join('customer', array(
        'id_number', 'nama_depan', 'nama_belakang', 'panggilan', 'tanggal_lahir', 'alamat', 'phone', 'email'
      ))
      ->join('terminologi_item', array(
        'nama as nama_panggilan'
      ))
      ->join('master_kamar_rate', array(
        'kode as kode_rate'
      ))
      ->join('master_wilayah_negara', array(
        'alpha_3_code', 'nationality as nama_nationality'
      ))
      ->join('master_wilayah_kabupaten', array(
        'nama as nama_kabupaten'
      ))
      ->on(array(
        array('reservasi.tipe_kamar', '=', 'master_kamar_tipe.uid'),
        array('reservasi.segmentasi', '=', 'segmentasi.uid'),
        array('reservasi.company', '=', 'company.uid'),
        array('reservasi.customer', '=', 'customer.uid'),
        array('customer.panggilan', '=', 'terminologi_item.id'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
        array('reservasi.nationality', '=', 'master_wilayah_negara.id'),
        array('reservasi.state', '=', 'master_wilayah_kabupaten.id')
      ))
      ->where(array(
        'reservasi.uid' => '= ?'
      ), array($parameter))
      ->execute();


    // Get Change Detail
    $record = self::$query->select('log_activity', array(
      'user_uid', 'old_value', 'logged_at'
    ))
      ->where(array(
        'log_activity.table_name' => '= ?',
        'AND',
        'log_activity.unique_target' => '= ?'
      ), array(
        'reservasi', $parameter
      ))
      ->execute();
    foreach ($record['response_data'] as $RKey => $RValue) {
      $User = self::$query->select('pegawai', array('nama'))
        ->where(array('pegawai.uid' => '= ?'), array($RValue['user_uid']))
        ->execute();
      $record['response_data'][$RKey]['user']  = $User['response_data'][0];
      $record['response_data'][$RKey]['logged_at'] = date('d F Y | H:i', strtotime($RValue['logged_at']));
    }
    $data['log_change'] = $record['response_data'];
    return $data;
  }

  private function reservasi_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(reservasi.no_reservasi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'reservasi.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.kamar' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'reservasi.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.kamar' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('reservasi', array(
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company'
      ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->on(array(
          array('reservasi.customer', '=', 'customer.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('reservasi', array(
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company'
      ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->on(array(
          array('reservasi.customer', '=', 'customer.uid')
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
      $data['response_data'][$key]['company'] = self::$query->select('company', array('uid', 'kode', 'nama'))->where(array('company.uid' => '= ?'), array($value['company']))->execute()['response_data'][0];
      $data['response_data'][$key]['check_in'] = date('d F Y H:i', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out'] = date('d F Y H:i', strtotime($value['check_out']));
      $autonum++;
    }

    $itemTotal = self::$query->select('reservasi', array(
      'uid'
    ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang'
      ))
      ->on(array(
        array('reservasi.customer', '=', 'customer.uid')
      ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function reservasi_list_history($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(reservasi.no_reservasi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'reservasi.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.kamar' => 'IS NOT NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'reservasi.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.kamar' => 'IS NOT NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('reservasi', array(
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company'
      ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->on(array(
          array('reservasi.customer', '=', 'customer.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('reservasi', array(
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company'
      ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->on(array(
          array('reservasi.customer', '=', 'customer.uid')
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
      $data['response_data'][$key]['company'] = self::$query->select('company', array('uid', 'kode', 'nama'))->where(array('company.uid' => '= ?'), array($value['company']))->execute()['response_data'][0];
      $data['response_data'][$key]['check_in'] = date('d F Y H:i', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out'] = date('d F Y H:i', strtotime($value['check_out']));
      $autonum++;
    }

    $itemTotal = self::$query->select('reservasi', array(
      'uid'
    ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang'
      ))
      ->on(array(
        array('reservasi.customer', '=', 'customer.uid')
      ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function edit_reservasi($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::reservasi_detail($parameter['uid']);
    $cust = self::$query->select('customer', array('uid'))
      ->where(array(
        'customer.id_number' => '= ?'
      ), array(
        $parameter['id_number']
      ))
      ->execute();
    if (count($cust['response_data']) > 0) {
      $selCust = $cust['response_data'][0]['uid'];
    } else {
      $selCust = parent::gen_uuid();
    }
    if (isset($parameter['company']) && !empty($parameter['company'])) {
      $data = self::$query->update('reservasi', array(
        'customer' => $selCust,
        'check_in' => date('Y-m-d H:i:s', strtotime($parameter['arrival'] . ' ' . $parameter['check_in'] . ':00')),
        'check_out' => date('Y-m-d H:i:s', strtotime($parameter['departure'] . ' ' . $parameter['check_out'] . ':00')),
        'status' => $parameter['status'],
        'tipe_kamar' => $parameter['tipe_kamar'],
        'block' => $parameter['block'],
        'deposit' => $parameter['deposit'],
        'pax' => $parameter['pax'],
        'vip' => $parameter['vip'],
        'company' => $parameter['company'],
        'rate_code' => $parameter['rate_code'],
        'rate_value' => $parameter['rate_value'],
        'metode_payment' => $parameter['payment_method'],
        'card_number' => $parameter['card_number'],
        'card_valid_until' => $parameter['card_valid'],
        'segmentasi' => $parameter['segmentasi'],
        'nationality' => $parameter['nationality'],
        'state' => $parameter['state'],
        'reservation_contact' => $parameter['contact'],
        'reserved_by' => $UserData['data']->uid,
        'check_in_remark' => $parameter['check_in_remark'],
        'updated_at' => parent::format_date()
      ))
        ->where(array(
          'reservasi.uid' => '= ?'
        ), array(
          $parameter['uid']
        ))
        ->execute();
    } else {
      $data = self::$query->update('reservasi', array(
        'customer' => $selCust,
        'check_in' => date('Y-m-d H:i:s', strtotime($parameter['arrival'] . ' ' . $parameter['check_in'] . ':00')),
        'check_out' => date('Y-m-d H:i:s', strtotime($parameter['departure'] . ' ' . $parameter['check_out'] . ':00')),
        'status' => $parameter['status'],
        'tipe_kamar' => $parameter['tipe_kamar'],
        'block' => $parameter['block'],
        'deposit' => $parameter['deposit'],
        'pax' => $parameter['pax'],
        'vip' => $parameter['vip'],
        'rate_code' => $parameter['rate_code'],
        'rate_value' => $parameter['rate_value'],
        'metode_payment' => $parameter['payment_method'],
        'card_number' => $parameter['card_number'],
        'card_valid_until' => $parameter['card_valid'],
        'segmentasi' => $parameter['segmentasi'],
        'nationality' => $parameter['nationality'],
        'state' => $parameter['state'],
        'reservation_contact' => $parameter['contact'],
        'reserved_by' => $UserData['data']->uid,
        'check_in_remark' => $parameter['check_in_remark'],
        'updated_at' => parent::format_date()
      ))
        ->where(array(
          'reservasi.uid' => '= ?'
        ), array(
          $parameter['uid']
        ))
        ->execute();
    }
    if ($data['response_result'] > 0) {
      $old['response_data'][0]['alasan_edit'] = $parameter['alasan_ubah'];
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
          'reservasi',
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

  private function tambah_reservasi($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $uid = parent::gen_uuid();

    $cust = self::$query->select('customer', array('uid'))
      ->where(array(
        'customer.id_number' => '= ?'
      ), array(
        $parameter['id_number']
      ))
      ->execute();
    if (count($cust['response_data']) > 0) {
      $selCust = $cust['response_data'][0]['uid'];
    } else {
      $selCust = parent::gen_uuid();
    }

    $nomor = self::$query->select('reservasi', array('uid'))
      ->where(array(
        '(created_at' => '>= current_date::timestamp',
        'AND',
        'created_at' => '< current_date::timestamp + interval \'1 day\')'
      ), array())
      ->execute();

    if (isset($parameter['company']) && !empty($parameter['company'])) {
      $data = self::$query->insert('reservasi', array(
        'uid' => $uid,
        'no_reservasi' => date('Ymd') . str_pad((count($nomor['response_data']) + 1), 5, '0', STR_PAD_LEFT),
        'id_number' => $parameter['id_number'],
        'customer' => $selCust,
        'check_in' => date('Y-m-d H:i:s', strtotime($parameter['arrival'] . ' ' . $parameter['check_in'] . ':00')),
        'check_out' => date('Y-m-d H:i:s', strtotime($parameter['departure'] . ' ' . $parameter['check_out'] . ':00')),
        'status' => $parameter['status'],
        'tipe_kamar' => $parameter['tipe_kamar'],
        'block' => $parameter['block'],
        'deposit' => $parameter['deposit'],
        'pax' => $parameter['pax'],
        'vip' => $parameter['vip'],
        'company' => $parameter['company'],
        'rate_code' => $parameter['rate_code'],
        'rate_value' => $parameter['rate_value'],
        'metode_payment' => $parameter['payment_method'],
        'card_number' => $parameter['card_number'],
        'card_valid_until' => $parameter['card_valid'],
        'segmentasi' => $parameter['segmentasi'],
        'nationality' => $parameter['nationality'],
        'state' => $parameter['state'],
        'reservation_contact' => $parameter['contact'],
        'reserved_by' => $UserData['data']->uid,
        'check_in_remark' => $parameter['check_in_remark'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
    } else {
      $data = self::$query->insert('reservasi', array(
        'uid' => $uid,
        'no_reservasi' => date('Ymd') . str_pad((count($nomor['response_data']) + 1), 5, '0', STR_PAD_LEFT),
        'id_number' => $parameter['id_number'],
        'customer' => $selCust,
        'check_in' => date('Y-m-d H:i:s', strtotime($parameter['arrival'] . ' ' . $parameter['check_in'] . ':00')),
        'check_out' => date('Y-m-d H:i:s', strtotime($parameter['departure'] . ' ' . $parameter['check_out'] . ':00')),
        'status' => $parameter['status'],
        'tipe_kamar' => $parameter['tipe_kamar'],
        'block' => $parameter['block'],
        'deposit' => $parameter['deposit'],
        'pax' => $parameter['pax'],
        'vip' => $parameter['vip'],
        'rate_code' => $parameter['rate_code'],
        'rate_value' => $parameter['rate_value'],
        'metode_payment' => $parameter['payment_method'],
        'card_number' => $parameter['card_number'],
        'card_valid_until' => $parameter['card_valid'],
        'segmentasi' => $parameter['segmentasi'],
        'nationality' => $parameter['nationality'],
        'state' => $parameter['state'],
        'reservation_contact' => $parameter['contact'],
        'reserved_by' => $UserData['data']->uid,
        'check_in_remark' => $parameter['check_in_remark'],
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
    }
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
          'reservasi',
          'I',
          parent::format_date(),
          'N',
          $UserData['data']->log_id
        ),
        'class' => __CLASS__
      ));

      if (count($cust['response_data']) == 0) {
        $newCustomer = self::$query->insert('customer', array(
          'uid' => $selCust,
          'id_number' => $parameter['id_number'],
          'email' => $parameter['email'],
          'nama_depan' => $parameter['first_name'],
          'nama_belakang' => $parameter['last_name'],
          'panggilan' => $parameter['title'],
          'tanggal_lahir' => $parameter['tanggal_lahir'],
          'alamat' => $parameter['address'],
          'phone' => $parameter['contact'],
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();
        $data['customer'] = $newCustomer;
      }
    }
    return $data;
  }
}
