<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;

class Dashboard extends Utility
{
  static $pdo;
  static $query;
  static $first_day_month;
  static $today;
  static $last_year;

  protected static function getConn()
  {
    return self::$pdo;
  }

  public function __construct($connection)
  {
    self::$pdo = $connection;
    self::$query = new Query(self::$pdo);
    $day = new \DateTime('last day of this month');
    $endOfYear = new \DateTime('last day of December this year');
    self::$last_year = date('Y-m-d', strtotime('+1 day', strtotime($endOfYear->format('Y-m-d'))));
    self::$first_day_month = date('Y-m-d', strtotime('-1 day', strtotime($day->format('Y-m-1'))));
    // self::$first_day_month = date('Y-m-d', (strtotime('-1 day', strtotime(date('Y-m-1')))));
    self::$today = date('Y-m-d', (strtotime('+1 day', strtotime(date('Y-m-d')))));
  }

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'get_jumlah_antrian_resepsionis':
          return self::get_jumlah_antrian_resepsionis();
          break;

        case 'get_jumlah_pasien_sedang_berobat':
          return self::get_jumlah_pasien_sedang_berobat();
          break;

        case 'get_jumlah_pasien_selesai_berobat':
          return self::get_jumlah_pasien_selesai_berobat();
          break;

        case 'count_room_available':
          return self::count_room_available();
          break;

        case 'count_room_saleable':
          return self::count_room_saleable();
          break;

        case 'count_room_sold':
          return self::count_room_sold();
          break;

        case 'count_folio_segmentasi':
          return self::count_folio_segmentasi();
          break;

        default:
          # code...
          break;
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function count_folio_segmentasi()
  {
    $data = self::$query->select('segmentasi', array(
      'uid', 'msscode', 'deskripsi'
    ))
      ->where(array(
        'segmentasi.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['current'] = 0;
      $currentData = self::$query->select('reservasi', array(
        'uid'
      ))
        ->where(array(
          'reservasi.check_in_actual' => 'IS NOT NULL',
          'AND',
          'reservasi.segmentasi' => '= ?',
          'AND',
          'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\''
        ), array(
          $value['uid']
        ))
        ->execute();
      $data['response_data'][$key]['current'] += count($currentData['response_data']);

      $data['response_data'][$key]['month'] = 0;
      $monthData = self::$query->select('reservasi', array(
        'uid'
      ))
        ->where(array(
          'reservasi.check_in_actual' => 'IS NOT NULL',
          'AND',
          'reservasi.segmentasi' => '= ?',
          'AND',
          'reservasi.created_at' => 'BETWEEN ? AND ?'
        ), array(
          $value['uid'],
          self::$first_day_month,
          self::$today,
        ))
        ->execute();

      $data['response_data'][$key]['month'] += count($monthData['response_data']);

      $data['response_data'][$key]['year'] = 0;
      $yearData = self::$query->select('reservasi', array(
        'uid'
      ))
        ->where(array(
          'reservasi.check_in_actual' => 'IS NOT NULL',
          'AND',
          'reservasi.segmentasi' => '= ?',
          'AND',
          'reservasi.created_at' => 'BETWEEN ? AND ?'
        ), array(
          $value['uid'],
          date('Y-m-d', strtotime('-1 day', strtotime(date('Y-1-1')))),
          self::$last_year
        ))
        ->execute();

      $data['response_data'][$key]['year'] += count($yearData['response_data']);
    }
    return $data;
  }

  public function count_room_occupied()
  {
    $current = 0;
    $month = 0;
    $year = 0;

    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        '(master_kamar.status' => '= ?',
        'OR',
        'master_kamar.status' => '= ?)',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('OC', 'OD'))
      ->execute();
    $current = count($data['response_data']);

    return array(
      'current' => $current,
      'month' => $month,
      'year' => $year
    );
  }

  public function count_room_oo()
  {
    $current = 0;
    $month = 0;
    $year = 0;

    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('OO'))
      ->execute();
    $current = count($data['response_data']);

    return array(
      'current' => $current,
      'month' => $month,
      'year' => $year
    );
  }

  public function count_room_sold()
  {
    $current = 0;
    $month = 0;
    $year = 0;

    $dataCurrent = self::$query->select('folio', array(
      'uid'
    ))
      ->where(array(
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'folio.created_at::date' => '= date \'' . date('Y-m-d') . '\''
      ), array())
      ->execute();

    $current = count($dataCurrent['response_data']);

    $dataMonth = self::$query->select('folio', array(
      'uid'
    ))
      ->where(array(
        'folio.created_at' => 'BETWEEN ? AND ?'
      ), array(
        self::$first_day_month,
        self::$today,
      ))
      ->execute();
    $month = count($dataMonth['response_data']);

    $dataYear = self::$query->select('folio', array(
      'uid'
    ))
      ->where(array(
        'folio.created_at' => 'BETWEEN ? AND ?'
      ), array(
        date('Y-m-d', strtotime('-1 day', strtotime(date('Y-1-1')))),
        self::$last_year
      ))
      ->execute();
    $year = count($dataYear['response_data']);

    return array(
      'current' => $current,
      'month' => $month,
      'year' => $year
    );
  }

  private function count_room_saleable()
  {
    $current = 0;
    $month = 0;
    $year = 0;

    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        '(master_kamar.status' => '= ?',
        'OR',
        'master_kamar.status' => '= ?)',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('VC', 'VD'))
      ->execute();
    $current = count($data['response_data']);

    return array(
      'current' => $current,
      'month' => $month,
      'year' => $year
    );
  }

  public function count_room_available()
  {
    $current = 0;
    $month = 0;
    $year = 0;

    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        '(master_kamar.status' => '= ?',
        'OR',
        'master_kamar.status' => '= ?)',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('VC', 'VD'))
      ->execute();
    $current = count($data['response_data']);

    return array(
      'current' => $current,
      'month' => $month,
      'year' => $year
    );
  }

  private static function get_jumlah_antrian_resepsionis()
  {
    $tgl = self::get_date_now();

    $data = self::$query
      ->select('antrian_nomor', array(
        'nomor_urut'
      ))
      ->where(
        array(
          'antrian_nomor.status'              => '= ?',
          'AND',
          'DATE(antrian_nomor.created_at)'    => '= ?'
        ),
        array(
          'N',
          $tgl
        )
      )
      ->execute();

    return $data;
  }

  private static function get_jumlah_pasien_sedang_berobat()
  {
    $tgl = self::get_date_now();

    $data = self::$query
      ->select('antrian', array(
        'uid'
      ))
      ->where(
        array(
          'DATE(antrian.waktu_masuk)' => '= ?'
        ),
        array(
          $tgl
        )
      )
      ->execute();

    return $data;
  }

  private static function get_jumlah_pasien_selesai_berobat()
  {
    $tgl = self::get_date_now();

    $data = self::$query
      ->select('antrian', array(
        'uid'
      ))
      ->where(
        array(
          'antrian.waktu_keluar'          =>  'IS NOT NULL',
          'AND',
          'DATE(antrian.waktu_keluar)'    => '= ?'
        ),
        array(
          $tgl
        )
      )
      ->execute();

    return $data;
  }

  static function get_date_now()
  {
    $date_now = parent::format_date();
    $arr_tgl = explode(" ", $date_now);
    $tgl = $arr_tgl[0];

    return $tgl;
  }
}
