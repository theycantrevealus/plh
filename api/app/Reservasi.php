<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use Spipu\Html2Pdf\Tag\Html\Em;

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
      case 'change_status':
        return self::change_status($parameter);
        break;
      case 'dashboard_grafik_kamar':
        return self::dashboard_grafik_kamar($parameter);
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
        case 'search_reserv':
          return self::search_reserv($parameter);
          break;
        case 'count_reservasi':
          return self::count_reservasi();
          break;
        case 'count_checkout':
          return self::count_checkout();
          break;
        case 'count_occupied':
          return self::count_occupied();
          break;
        case 'count_occ':
          return self::count_occ();
          break;
        case 'count_available':
          return self::count_available();
          break;
        case 'count_vacant':
          return self::count_vacant();
          break;
        case 'count_vacant_dirty':
          return self::count_vacant_dirty();
          break;
        case 'count_compliment':
          return self::count_compliment();
          break;
        case 'count_oo':
          return self::count_oo();
          break;
        case 'count_arr':
          return self::count_arr();
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function dashboard_grafik_kamar($parameter)
  {
    $begin = new \DateTime($parameter['from']);
    $end = new \DateTime($parameter['to']);

    $interval = \DateInterval::createFromDateString('1 day');
    $period = new \DatePeriod($begin, $interval, $end);

    $dataSet = array(
      array(
        'backgroundColor' => array('rgba(63, 198, 0, 1)'),
        'borderColor' => array('rgba(120, 255, 58, 1)'),
        'label' => 'Rate Revenue',
        'fill' => false,
        'cubicInterpolationMode' => 'monotone',
        'tension' => 0.4,
        'data' => array()
      ),
      array(
        'backgroundColor' => array('rgba(239, 243, 0, 1)'),
        'borderColor' => array('rgba(255, 206, 86, 1)'),
        'label' => 'Penggunaan Kamar',
        'fill' => false,
        'cubicInterpolationMode' => 'monotone',
        'tension' => 0.4,
        'data' => array()
      )
    );

    $labels = array();
    foreach ($period as $dt) {
      array_push($labels, $dt->format('d-m-Y'));

      $count_kamar = self::$query->select('reservasi', array(
        'uid'
      ))
        ->where(array(
          'reservasi.kamar' => 'IS NOT NULL',
          'AND',
          'reservasi.deleted_at' => 'IS NULL',
          'AND',
          'reservasi.created_at::date' => '= date \'' . $dt->format('Y-m-d') . '\''
        ), array())
        ->execute();
      array_push($dataSet[1]['data'], count($count_kamar['response_data']));


      $data = self::$query->select('folio_transact', array(
        'price'
      ))
        ->where(array(
          'folio_transact.transcode' => '= ?',
          'AND',
          'folio_transact.deleted_at' => 'IS NULL',
          'AND',
          'folio_transact.created_at::date' => '= date \'' . $dt->format('Y-m-d') . '\''
        ), array(
          __RULE_TRANS_RATE_CHARGE__
        ))
        ->execute();
      $total = 0;
      foreach ($data['response_data'] as $key => $value) {
        $total += floatval($value['price']);
      }

      array_push($dataSet[0]['data'], $total);
    }

    return array(
      'labels' => $labels,
      'datasets' => $dataSet
    );
  }

  private function search_reserv($parameter)
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
      ->join('master_kamar', array(
        'nomor'
      ))
      ->join('master_kamar_tipe', array(
        'kode as kode_tipe',
        'nama as nama_tipe'
      ))
      ->join('segmentasi', array(
        'msscode', 'deskripsi as nama_segmentasi'
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
        array('reservasi.kamar', '=', 'master_kamar.uid'),
        array('reservasi.tipe_kamar', '=', 'master_kamar_tipe.uid'),
        array('reservasi.segmentasi', '=', 'segmentasi.uid'),
        array('reservasi.customer', '=', 'customer.uid'),
        array('customer.panggilan', '=', 'terminologi_item.id'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
        array('reservasi.nationality', '=', 'master_wilayah_negara.id'),
        array('reservasi.state', '=', 'master_wilayah_kabupaten.id')
      ))
      ->where(array(
        'master_kamar.nomor' => 'ILIKE ' . '\'%' . strtoupper($_GET['search']) . '%\'',
        'AND',
        'reservasi.check_out' => '>= ?',
        'AND',
        'reservasi.checked_out_by' => 'IS NULL'
      ), array(
        date('Y-m-d')
      ))
      ->execute();

    return $data;
  }

  private function count_reservasi()
  {
    $data = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.check_out_actual' => 'IS NULL',
        'AND',
        'reservasi.kamar' => 'IS NULL',
        'AND',
        'reservasi.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    return $data;
  }

  private function count_occ()
  {
    // RO / RA * 100

    // Avail : non OO dan non HU
    // Occ : non HU

    $ro = self::count_occupied();

    $roHU = self::$query->select('folio', array(
      'uid', 'reservasi'
    ))
      ->join('reservasi', array(
        'rate_code'
      ))
      ->join('master_kamar_rate', array(
        'harga'
      ))
      ->on(array(
        array('folio.reservasi', '=', 'reservasi.uid'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid')
      ))
      ->where(array(
        'reservasi.check_out::date' => '>= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.rate_code' => ' != ?'
      ), array(
        __RATE_HU__
      ))
      ->execute();

    $roFinal = count($ro['response_data']) - count($roHU['response_data']);

    $ra = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        'master_kamar.status' => '!= ?'
      ), array(
        'OO'
      ))
      ->execute();
    $raFinal = count($ra['response_data']) - count($roHU['response_data']);
    return number_format((float) ($roFinal / $raFinal * 100), 2, ".", "");
  }

  private function count_occupied()
  {
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
    return $data;
  }

  private function count_available()
  {
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
    return $data;
  }

  private function count_vacant()
  {
    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('VC'))
      ->execute();
    return $data;
  }

  private function count_arr()
  {

    $data = self::$query->select('folio', array(
      'uid', 'reservasi'
    ))
      // ->join('folio_transact', array(
      //   'price', 'folio'
      // ))
      ->join('reservasi', array(
        'rate_code'
      ))
      ->join('master_kamar_rate', array(
        'harga'
      ))
      ->on(array(
        // array('folio_transact.folio', '=', 'folio.uid'),
        array('folio.reservasi', '=', 'reservasi.uid'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid')
      ))
      ->where(array(
        'reservasi.check_out::date' => '>= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.rate_code' => ' != ?'
        // 'AND',
        // 'folio_transact.transcode' => '= ?'
      ), array(
        //__RULE_TRANS_RATE_CHARGE__
        __RATE_HU__
      ))
      ->execute();
    $total = 0;
    foreach ($data['response_data'] as $key => $value) {
      $cPost = self::$query->select('room_posting', array(
        'rate_value'
      ))
        ->where(array(
          'room_posting.folio' => '= ?',
          'AND',
          'room_posting.rate_code' => 'IS NOT NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      if (count($cPost['response_data']) > 0) {
        $transDet = self::$query->select('folio', array(
          'uid', 'reservasi'
        ))
          ->join('folio_transact', array(
            'price', 'folio'
          ))
          ->join('reservasi', array(
            'rate_code'
          ))
          ->join('master_kamar_rate', array(
            'harga'
          ))
          ->on(array(
            array('folio_transact.folio', '=', 'folio.uid'),
            array('folio.reservasi', '=', 'reservasi.uid'),
            array('reservasi.rate_code', '=', 'master_kamar_rate.uid')
          ))
          ->where(array(
            'folio.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
            'AND',
            'folio.deleted_at' => 'IS NULL',
            'AND',
            'folio_transact.transcode' => '= ?',
            'AND',
            'folio.uid' => '= ?'
          ), array(
            __RULE_TRANS_RATE_CHARGE__,
            $value['uid']
          ))
          ->execute();
        $total += floatval($transDet['response_data'][0]['harga']) / 1.21;
        //$total += floatval($value['harga']) / 1.21;
      } else {
        $total += floatval($value['harga']) / 1.21;
      }
    }

    $final = $total / count($data['response_data']);

    return ($total === 0) ? 0 : $final;
  }

  private function count_oo()
  {
    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('OO'))
      ->execute();
    return $data;
  }

  private function count_compliment()
  {
    $data = self::$query->select('folio', array(
      'uid', 'reservasi'
    ))
      // ->join('folio_transact', array(
      //   'price', 'folio'
      // ))
      ->join('reservasi', array(
        'rate_code'
      ))
      ->join('master_kamar_rate', array(
        'harga'
      ))
      ->on(array(
        // array('folio_transact.folio', '=', 'folio.uid'),
        array('folio.reservasi', '=', 'reservasi.uid'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid')
      ))
      ->where(array(
        'reservasi.check_out::date' => '>= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.rate_code' => '!= ?'
        // 'AND',
        // 'folio_transact.transcode' => '= ?'
      ), array(
        //__RULE_TRANS_RATE_CHARGE__
        __RATE_HU__
      ))
      ->execute();
    $total = 0;
    $setDat = 0;
    foreach ($data['response_data'] as $key => $value) {
      $cPost = self::$query->select('room_posting', array(
        'rate_value'
      ))
        ->where(array(
          'room_posting.folio' => '= ?',
          'AND',
          'room_posting.rate_code' => 'IS NOT NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      if (count($cPost['response_data']) > 0) {
        $transDet = self::$query->select('folio', array(
          'uid', 'reservasi'
        ))
          ->join('folio_transact', array(
            'price', 'folio'
          ))
          ->join('reservasi', array(
            'rate_code'
          ))
          ->join('master_kamar_rate', array(
            'harga'
          ))
          ->on(array(
            array('folio_transact.folio', '=', 'folio.uid'),
            array('folio.reservasi', '=', 'reservasi.uid'),
            array('reservasi.rate_code', '=', 'master_kamar_rate.uid')
          ))
          ->where(array(
            'folio.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
            'AND',
            'folio.deleted_at' => 'IS NULL',
            'AND',
            'folio_transact.transcode' => '= ?',
            'AND',
            'folio.uid' => '= ?'
          ), array(
            __RULE_TRANS_RATE_CHARGE__,
            $value['uid']
          ))
          ->execute();
        $setDat = floatval($transDet['response_data'][0]['harga']);
      } else {
        $setDat = floatval($value['harga']);
      }

      if ($setDat <= 0) {
        $total += 1;
      }
    }

    return $total;
  }

  private function count_vacant_dirty()
  {
    $data = self::$query->select('master_kamar', array(
      'uid'
    ))
      ->where(array(
        'master_kamar.status' => '= ?',
        'AND',
        'master_kamar.deleted_at' => 'IS NULL'
      ), array('VD'))
      ->execute();
    return $data;
  }

  private function count_checkout()
  {
    $data = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.check_out_actual' => 'IS NOT NULL',
        'AND',
        'reservasi.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    return $data;
  }

  private function change_status($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    $old = self::reservasi_detail($parameter['uid']);
    $data = self::$query->update('reservasi', array(
      'status_rev' => $parameter['status']
    ))
      ->where(array(
        'reservasi.uid' => '= ?'
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
      'kamar',
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
      'check_in_actual',
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
      // ->join('company', array(
      //   'nama as nama_company', 'kode as kode_company'
      // ))
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
      ->join('master_accounting_payment', array(
        'kode as kode_payment', 'keterangan as ket_payment'
      ))
      ->on(array(
        array('reservasi.tipe_kamar', '=', 'master_kamar_tipe.uid'),
        array('reservasi.segmentasi', '=', 'segmentasi.uid'),
        // array('reservasi.company', '=', 'company.uid'),
        array('reservasi.customer', '=', 'customer.uid'),
        array('customer.panggilan', '=', 'terminologi_item.id'),
        array('reservasi.rate_code', '=', 'master_kamar_rate.uid'),
        array('reservasi.nationality', '=', 'master_wilayah_negara.id'),
        array('reservasi.state', '=', 'master_wilayah_kabupaten.id'),
        array('reservasi.metode_payment', '=', 'master_accounting_payment.uid')
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

      $data['response_data'][$key]['check_in_date'] = date('d F Y', strtotime($value['check_in']));
      $data['response_data'][$key]['check_out_date'] = date('d F Y', strtotime($value['check_out']));
      $data['response_data'][$key]['check_in_actual'] = date('d F Y, H:i', strtotime($value['check_in_actual']));
      $kamar = self::$query->select('master_kamar', array(
        'uid', 'nomor'
      ))
        ->where(array(
          'master_kamar.uid' => '= ?'
        ), array(
          $value['kamar']
        ))
        ->execute();
      $data['response_data'][$key]['kamar'] = $kamar['response_data'][0];
    }
    return $data;
  }

  private function reservasi_list($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (!isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      if (isset($parameter['paramSet'])) {
        if ($parameter['paramSet'] === "today") {
          $paramData = array(
            '(reservasi.no_reservasi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
            'OR',
            'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
            'AND',
            'reservasi.deleted_at' => 'IS NULL',
            'AND',
            'reservasi.kamar' => 'IS NULL',
            'AND',
            '(reservasi.created_at' => '>= current_date::timestamp',
            'AND',
            'reservasi.created_at' => '< current_date::timestamp + interval \'1 day\')'
          );
          $paramValue = array();
        }
      } else {
        $paramData = array(
          '(reservasi.no_reservasi' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
          'OR',
          'customer.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
          'AND',
          'reservasi.deleted_at' => 'IS NULL',
          'AND',
          'reservasi.status_rev' => 'IS NULL',
          'AND',
          'reservasi.kamar' => 'IS NULL'
        );
        $paramValue = array();
      }
    } else {
      if (isset($parameter['paramSet'])) {
        if ($parameter['paramSet'] === "today") {
          $paramData = array(
            'reservasi.deleted_at' => 'IS NULL',
            'AND',
            'reservasi.kamar' => 'IS NULL',
            'AND',
            '(reservasi.created_at' => '>= current_date::timestamp',
            'AND',
            'reservasi.created_at' => '< current_date::timestamp + interval \'1 day\')'
          );
          $paramValue = array();
        }
      } else {
        $paramData = array(
          'reservasi.deleted_at' => 'IS NULL',
          'AND',
          'reservasi.status_rev' => 'IS NULL',
          'AND',
          'reservasi.kamar' => 'IS NULL'
        );
        $paramValue = array();
      }
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('reservasi', array(
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company', 'status_rev'
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
        'uid', 'no_reservasi', 'check_in', 'check_out', 'vip', 'company', 'status_rev'
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
        'reservasi.created_at' => 'BETWEEN ? AND ?',
        'AND',
        '(reservasi.kamar' => 'IS NOT NULL',
        'OR',
        'reservasi.status_rev' => 'IS NOT NULL)'
      );
      $paramValue = array($parameter['from'], $parameter['to']);
    } else {
      $paramData = array(
        'reservasi.deleted_at' => 'IS NULL',
        'AND',
        'reservasi.created_at' => 'BETWEEN ? AND ?',
        'AND',
        '(reservasi.kamar' => 'IS NOT NULL',
        'OR',
        'reservasi.status_rev' => 'IS NOT NULL)'
      );
      $paramValue = array($parameter['from'], $parameter['to']);
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
        ->order(array(
          'reservasi.no_reservasi' => 'ASC'
        ))
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
        ->order(array(
          'reservasi.no_reservasi' => 'ASC'
        ))
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
