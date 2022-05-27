<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Folio extends Utility
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
      case 'tambah_folio':
        return self::tambah_folio($parameter);
        break;
      case 'add_trans':
        return self::add_trans($parameter);
        break;
      case 'list_folio':
        return self::list_folio($parameter);
        break;
      case 'list_folio_history':
        return self::list_folio_history($parameter);
        break;
      case 'check_out':
        return self::check_out($parameter);
        break;
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'folio_detail':
          return self::folio_detail($parameter[2]);
          break;
        case 'folio_trans':
          return self::folio_trans($parameter[2]);
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function folio_trans($parameter)
  {
    $data = self::$query->select('folio_transact', array(
      'transcode', 'price', 'remark', 'add_by', 'created_at', 'deskripsi', 'final_price'
    ))
      ->where(array(
        'folio_transact.folio' => '= ?',
        'AND',
        'folio_transact.deleted_at' => 'IS NULL'
      ), array(
        $parameter
      ))
      ->execute();
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['created_at'] = date('d F Y', strtotime($value['created_at']));
      $data['response_data'][$key]['transcode'] = self::$query->select('master_accounting_transact', array(
        'kode', 'dbcr', 'account_code', 'apply_tax', 'apply_service', 'keterangan'
      ))
        ->where(array(
          'master_accounting_transact.uid' => '= ?'
        ), array(
          $value['transcode']
        ))
        ->execute()['response_data'][0];
      $data['response_data'][$key]['add_by'] = self::$query->select('pegawai', array('nama'))
        ->where(array(
          'pegawai.uid' => '= ?'
        ), array(
          $value['add_by']
        ))
        ->execute()['response_data'][0];
    }
    return $data;
  }

  public function folio_detail($parameter)
  {
    $data = self::$query->select('folio', array(
      'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
    ))
      ->join('reservasi', array(
        'no_reservasi', 'customer', 'check_in', 'check_out', 'rate_code', 'rate_value', 'metode_payment', 'status', 'card_number', 'card_valid_until', 'pax', 'company'
      ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang', 'alamat'
      ))
      ->join('master_kamar', array(
        'nomor as nomor_kamar'
      ))
      ->join('master_kamar_tipe', array(
        'kode as kode_tipe', 'nama as nama_tipe'
      ))
      ->join('master_kamar_rate', array(
        'kode as kode_rate'
      ))
      ->join('master_accounting_payment', array(
        'kode as kode_payment', 'keterangan as nama_payment'
      ))
      // ->join('company', array(
      //   'kode as kode_company', 'nama as nama_company'
      // ))
      ->on(array(
        array('folio.reservasi', '=', 'reservasi.uid'),
        array('reservasi.customer', '=', 'customer.uid'),
        array('folio.kamar', '=', 'master_kamar.uid'),
        array('master_kamar.tipe', '=', 'master_kamar_tipe.uid'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
        array('reservasi.metode_payment', '=', 'master_accounting_payment.uid'),
        // array('reservasi.company', '=', 'company.uid')
      ))
      ->where(array(
        'folio.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    foreach ($data['response_data'] as $key => $value) {
      if (isset($value['company']) && !empty($value['company'])) {
        $Company = self::$query->select('company', array(
          'nama', 'kode'
        ))
          ->where(array(
            'company.uid' => '= ?'
          ), array(
            $value['company']
          ))
          ->execute();
        $data['response_data'][$key]['kode_company'] = $Company['response_data'][0]['kode'];
        $data['response_data'][$key]['nama_company'] = $Company['response_data'][0]['nama'];
      }
      $data['response_data'][$key]['check_in'] = date('d F Y', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out'] = date('d F Y', strtotime($value['check_out']));
    }
    return $data;
  }

  private function check_out($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $Fol = self::folio_detail($parameter['folio']);
    $Reserv = self::$query->update('reservasi', array(
      'check_out_actual' => parent::format_date(),
      'checked_out_by' => $UserData['data']->uid,
      'status_rev' => NULL
    ))
      ->where(array(
        'reservasi.uid' => '= ?'
      ), array(
        $Fol['response_data'][0]['reservasi']
      ))
      ->execute();

    if ($Reserv['response_result'] > 0) {
      $Kamar = self::$query->update('master_kamar', array(
        'status' => 'VD'
      ))
        ->where(array(
          'master_kamar.uid' => '= ?'
        ), array(
          $Fol['response_data'][0]['kamar']
        ))
        ->execute();
    }
    return $Reserv;
  }

  private function list_folio_history($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(folio.no_folio' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.check_out_actual' => 'IS NOT NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.check_out_actual' => 'IS NOT NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('folio', array(
        'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
      ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->join('master_kamar', array(
          'nomor as nomor_kamar'
        ))
        ->on(array(
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('folio', array(
        'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
      ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->join('master_kamar', array(
          'nomor as nomor_kamar'
        ))
        ->on(array(
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid')
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
      $data['response_data'][$key]['check_in'] = date('d F Y H:i', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out'] = date('d F Y H:i', strtotime($value['check_out']));
      $autonum++;
    }

    $itemTotal = self::$query->select('folio', array(
      'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
    ))
      ->join('reservasi', array(
        'customer'
      ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang'
      ))
      ->on(array(
        array('folio.reservasi', '=', 'reservasi.uid'),
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

  private function list_folio($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        '(folio.no_folio' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'OR',
        'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
        'AND',
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.check_out_actual' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.check_out_actual' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('folio', array(
        'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
      ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->join('master_kamar', array(
          'nomor as nomor_kamar'
        ))
        ->on(array(
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('folio', array(
        'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
      ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
        ))
        ->join('master_kamar', array(
          'nomor as nomor_kamar'
        ))
        ->on(array(
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid')
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
      $data['response_data'][$key]['check_in'] = date('d F Y H:i', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out'] = date('d F Y H:i', strtotime($value['check_out']));
      $autonum++;
    }

    $itemTotal = self::$query->select('folio', array(
      'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
    ))
      ->join('reservasi', array(
        'customer'
      ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang'
      ))
      ->on(array(
        array('folio.reservasi', '=', 'reservasi.uid'),
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

  private function add_trans($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $data = self::$query->insert('folio_transact', array(
      'folio' => $parameter['folio'],
      'transcode' => $parameter['transcode'],
      'qty' => 1,
      'price' => $parameter['transval'],
      'tax_value' => __TAX_VAL__,
      'service_value' => __SER_VAL__,
      'final_price' => $parameter['transval_final'],
      'deskripsi' => $parameter['deskripsi'],
      'remark' => $parameter['remark'],
      'add_by' => $UserData['data']->uid,
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();

    if ($data['response_result'] > 0) {
      $checkTrans = self::$query->select('master_accounting_transact', array(
        'kode', 'dbcr', 'account_code', 'apply_tax', 'apply_service', 'keterangan'
      ))
        ->where(array(
          'master_accounting_transact.uid' => '= ?'
        ), array(
          $parameter['transcode']
        ))
        ->execute()['response_data'][0];

      $oldFol = self::folio_detail($parameter['folio']);

      $fixBalance = ($checkTrans['dbcr'] === 'D') ? (floatval($oldFol['response_data'][0]['balance']) + floatval($parameter['transval'])) : (floatval($oldFol['response_data'][0]['balance']) - (floatval($parameter['transval'])));
      $FolioUp = self::$query->update('folio', array(
        'balance' => $fixBalance
      ))
        ->where(array(
          'folio.uid' => '= ?'
        ), array(
          $parameter['folio']
        ))
        ->execute();
    }

    return $data;
  }

  private function tambah_folio($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $nomor = self::$query->select('folio', array('uid'))
      ->where(array(
        '(created_at' => '>= current_date::timestamp',
        'AND',
        'created_at' => '< current_date::timestamp + interval \'1 day\')'
      ), array())
      ->execute();

    $uid = parent::gen_uuid();
    $data = self::$query->insert('folio', array(
      'uid' => $uid,
      'reservasi' => $parameter['reservasi'],
      'no_folio' => str_pad((count($nomor['response_data']) + 1), 5, '0', STR_PAD_LEFT),
      'kamar' => $parameter['kamar'],
      // 'balance' => floatval($parameter['deposit']) - floatval($parameter['rate_value']),
      'balance' => floatval($parameter['deposit']) * -1,
      'created_at' => parent::format_date(),
      'updated_at' => parent::format_date()
    ))
      ->execute();

    if ($data['response_result'] > 0) {
      // Charge Rule depo   __RULE_TRANS_DEPO__
      $depo = self::$query->insert('folio_transact', array(
        'folio' => $uid,
        'transcode' => __RULE_TRANS_DEPO__,
        'qty' => 1,
        'price' => $parameter['deposit'],
        'deskripsi' => 'Guest Deposit',
        'remark' => 'Guest Deposit',
        'add_by' => $UserData['data']->uid,
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      // Charge Rule rate   __RULE_TRANS_RATE_CHARGE__
      // $rate_code = self::$query->insert('folio_transact', array(
      //   'folio' => $uid,
      //   'transcode' => __RULE_TRANS_RATE_CHARGE__,
      //   'qty' => 1,
      //   'deskripsi' => 'Charge Rate Code',
      //   'remark' => 'Charge Rate Code',
      //   'price' => $parameter['rate_value'],
      //   'add_by' => $UserData['data']->uid,
      //   'created_at' => parent::format_date(),
      //   'updated_at' => parent::format_date()
      // ))
      //   ->execute();

      // Ubah Actual Check In
      $updateRes = self::$query->update('reservasi', array(
        'check_in_actual' => parent::format_date(),
        'status_rev' => 'I',
        'kamar' => $parameter['kamar']
      ))
        ->where(array(
          'reservasi.uid' => '= ?'
        ), array(
          $parameter['reservasi']
        ))
        ->execute();

      // Iterate Room Post Job
      $from = date('Y-m-d', strtotime($parameter['arrival']));
      for ($i = 1; $i <= intval($parameter['night']); $i++) {
        $RPost = self::$query->insert('room_posting', array(
          'uid' => parent::gen_uuid(),
          'tanggal' => $from,
          'folio' => $uid,
          'created_at' => parent::format_date(),
          'updated_at' => parent::format_date()
        ))
          ->execute();
        $repeat = strtotime("+1 day", strtotime($from));
        $from = date('Y-m-d', $repeat);
      }

      // Ubah Kamar jadi OC
      $updateKamar = self::$query->update('master_kamar', array(
        'status' => 'OC'
      ))
        ->where(array(
          'master_kamar.uid' => '= ?'
        ), array(
          $parameter['kamar']
        ))
        ->execute();
    }

    return $data;
  }
}
