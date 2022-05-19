<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class RPost extends Utility
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
      case 'rpost_list':
        return self::rpost_list($parameter);
        break;
      case 'add_posting':
        return self::add_posting($parameter);
        break;
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'ujiclass':
          return array();
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function add_posting($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);

    $data = self::$query->update('room_posting', array(
      'rate_code' => $parameter['rate_code'],
      'rate_value' => $parameter['rate_value'],
      'pegawai' => $UserData['data']->uid
    ))
      ->where(array(
        'room_posting.uid' => '= ?'
      ), array(
        $parameter['uid']
      ))
      ->execute();

    if ($data['response_result'] > 0) {
      // Charge Rule rate   __RULE_TRANS_RATE_CHARGE__
      $rate_code = self::$query->insert('folio_transact', array(
        'folio' => $parameter['folio'],
        'transcode' => __RULE_TRANS_RATE_CHARGE__,
        'qty' => 1,
        'deskripsi' => 'Charge Rate Code',
        'remark' => 'Charge Rate Code',
        'price' => $parameter['rate_value'],
        'add_by' => $UserData['data']->uid,
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();

      if ($rate_code['response_result'] > 0) {
        $old = self::$query->select('folio', array(
          'balance'
        ))
          ->where(array(
            'uid' => '= ?'
          ), array(
            $parameter['folio']
          ))
          ->execute();

        $upFol = self::$query->update('folio', array(
          'balance' => floatval($old['response_data'][0]['balance']) - floatval($parameter['rate_value'])
        ))
          ->where(array(
            'uid' => '= ?'
          ), array(
            $parameter['folio']
          ))
          ->execute();
      }
    }

    return $data;
  }

  private function rpost_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'room_posting.deleted_at' => 'IS NULL',
        'AND',
        'room_posting.tanggal' => '= ?'
      );
      $paramValue = array(date('Y-m-d'));
    } else {
      $paramData = array(
        'room_posting.deleted_at' => 'IS NULL',
        'AND',
        'room_posting.tanggal' => '= ?'
      );
      $paramValue = array(date('Y-m-d'));
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('room_posting', array(
        'uid', 'tanggal', 'pegawai', 'rate_code as actual_rate_code', 'rate_value as actual_rate_value', 'folio'
      ))
        ->join('folio', array(
          'reservasi', 'no_folio', 'kamar', 'balance'
        ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out', 'rate_code', 'rate_value', 'metode_payment', 'status', 'card_number', 'card_valid_until', 'pax', 'company'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
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
        ->on(array(
          array('room_posting.folio', '=', 'folio.uid'),
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid'),
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid'),
          array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
          array('reservasi.metode_payment', '=', 'master_accounting_payment.uid')
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('room_posting', array(
        'uid', 'tanggal', 'pegawai', 'rate_code as actual_rate_code', 'rate_value as actual_rate_value', 'folio'
      ))
        ->join('folio', array(
          'reservasi', 'no_folio', 'kamar', 'balance'
        ))
        ->join('reservasi', array(
          'no_reservasi', 'customer', 'check_in', 'check_out', 'rate_code', 'rate_value', 'metode_payment', 'status', 'card_number', 'card_valid_until', 'pax', 'company'
        ))
        ->join('customer', array(
          'nama_depan', 'nama_belakang'
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
        ->on(array(
          array('room_posting.folio', '=', 'folio.uid'),
          array('folio.reservasi', '=', 'reservasi.uid'),
          array('reservasi.customer', '=', 'customer.uid'),
          array('folio.kamar', '=', 'master_kamar.uid'),
          array('master_kamar.tipe', '=', 'master_kamar_tipe.uid'),
          array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
          array('reservasi.metode_payment', '=', 'master_accounting_payment.uid')
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

    $itemTotal = self::$query->select('room_posting', array(
      'uid'
    ))
      ->join('folio', array(
        'uid', 'reservasi', 'no_folio', 'kamar', 'balance'
      ))
      ->join('reservasi', array(
        'no_reservasi', 'customer', 'check_in', 'check_out', 'rate_code', 'rate_value', 'metode_payment', 'status', 'card_number', 'card_valid_until', 'pax', 'company'
      ))
      ->join('customer', array(
        'nama_depan', 'nama_belakang'
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
      ->on(array(
        array('room_posting.folio', '=', 'folio.uid'),
        array('folio.reservasi', '=', 'reservasi.uid'),
        array('reservasi.customer', '=', 'customer.uid'),
        array('folio.kamar', '=', 'master_kamar.uid'),
        array('master_kamar.tipe', '=', 'master_kamar_tipe.uid'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
        array('reservasi.metode_payment', '=', 'master_accounting_payment.uid')
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
