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
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
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
      'balance' => floatval($parameter['deposit']) - floatval($parameter['rate_value']),
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
        'remark' => 'Guest Deposit',
        'add_by' => $UserData['data']->uid,
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      // Charge Rule rate   __RULE_TRANS_RATE_CHARGE__
      $rate_code = self::$query->insert('folio_transact', array(
        'folio' => $uid,
        'transcode' => __RULE_TRANS_RATE_CHARGE__,
        'qty' => 1,
        'price' => $parameter['rate_value'],
        'remark' => 'Charge Rate Code',
        'add_by' => $UserData['data']->uid,
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();

      // Ubah Actual Check In
      $updateRes = self::$query->update('reservasi', array(
        'check_in_actual' => parent::format_date()
      ))
        ->where(array(
          'reservasi.uid' => '= ?'
        ), array(
          $parameter['reservasi']
        ))
        ->execute();

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
