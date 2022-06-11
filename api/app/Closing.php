<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Closing extends Utility
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
      case 'calculate':
        return self::calculate($parameter);
        break;
      case 'proceed_close':
        return self::proceed_close($parameter);
        break;
      case 'list_closing':
        return self::list_closing($parameter);
        break;
    }
  }

  public function __GET__($parameter = array())
  {
    try {

      switch ($parameter[1]) {
        case 'closing_detail':
          return self::closing_detail($parameter[2]);
          break;
        default:
          return 'Unknown request';
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  private function closing_detail($parameter)
  {
    $data = self::$query->select('closing_set', array(
      'uid', 'meta_data'
    ))
      ->where(array(
        'closing_set.uid' => '= ?'
      ), array(
        $parameter
      ))
      ->execute();
    return $data;
  }

  private function list_closing($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
      $paramData = array(
        'pegawai.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
        'AND',
        'closing_set.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    } else {
      $paramData = array(
        'closing_set.deleted_at' => 'IS NULL'
      );
      $paramValue = array();
    }

    if ($parameter['length'] < 0) {
      $data = self::$query->select('closing_set', array(
        'uid', 'pegawai', 'meta_data', 'created_at'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('closing_set.pegawai', '=', 'pegawai.uid')
        ))
        ->order(array(
          'created_at' => 'DESC'
        ))
        ->where($paramData, $paramValue)
        ->execute();
    } else {
      $data = self::$query->select('closing_set', array(
        'uid', 'pegawai', 'meta_data', 'created_at'
      ))
        ->join('pegawai', array(
          'nama'
        ))
        ->on(array(
          array('closing_set.pegawai', '=', 'pegawai.uid')
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
      $data['response_data'][$key]['created_at'] = date('d F Y, H:i', strtotime($value['created_at']));
      $autonum++;
    }

    $itemTotal = self::$query->select('closing_set', array(
      'uid', 'pegawai', 'meta_data', 'created_at'
    ))
      ->join('pegawai', array(
        'nama'
      ))
      ->on(array(
        array('closing_set.pegawai', '=', 'pegawai.uid')
      ))
      ->order(array(
        'created_at' => 'DESC'
      ))
      ->where($paramData, $paramValue)
      ->execute();

    $data['recordsTotal'] = count($itemTotal['response_data']);
    $data['recordsFiltered'] = count($itemTotal['response_data']);
    $data['length'] = intval($parameter['length']);
    $data['start'] = intval($parameter['start']);
    return $data;
  }

  private function proceed_close($parameter)
  {
    $Authorization = new Authorization();
    $UserData = $Authorization->readBearerToken($parameter['access_token']);
    //Check
    $check = self::$query->select('closing_set', array(
      'uid'
    ))
      ->where(array(
        'closing_set.created_at::date' => '= ?'
      ), array(
        date('Y-m-d')
      ))
      ->execute();
    if (count($check['response_data']) > 0) {
      return array(
        'response_result' => 0,
        'response_message' => 'Sudah ada closing harian untuk hari ini'
      );
    } else {
      $for_save = self::calculate($parameter);
      $uid = parent::gen_uuid();
      $closing = self::$query->insert('closing_set', array(
        'uid' => $uid,
        'pegawai' => $UserData['data']->uid,
        'meta_data' => json_encode($for_save),
        'created_at' => parent::format_date(),
        'updated_at' => parent::format_date()
      ))
        ->execute();
      return $closing;
    }
  }

  private function calculate($parameter)
  {
    $Dashboard = new Dashboard(self::$pdo);
    $Reservasi = new Reservasi(self::$pdo);
    $avail = floatval($Dashboard->count_room_available()['current']);
    $saleable = floatval($Dashboard->count_room_available()['current']);
    $sold = floatval($Dashboard->count_room_sold()['current']);
    $occupied = floatval($Dashboard->count_room_occupied()['current']);
    $oo = floatval($Dashboard->count_room_oo()['current']);
    //===================================================================================================
    $pax = 0;
    $count_pax = self::$query->select('reservasi', array(
      'uid', 'pax'
    ))
      ->where(array(
        'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'reservasi.check_in_actual' => 'IS NOT NULL'
      ), array())
      ->execute();
    foreach ($count_pax['response_data'] as $key => $value) {
      $pax += floatval($value['pax']);
    }


    $compliment = $Reservasi->count_compliment();
    //===================================================================================================
    $segment = self::$query->select('segmentasi', array(
      'uid', 'msscode'
    ))
      ->where(array(
        'segmentasi.deleted_at' => 'IS NULL'
      ), array())
      ->execute();
    foreach ($segment['response_data'] as $key => $value) {
      $count_segment = self::$query->select('reservasi', array(
        'uid'
      ))
        ->where(array(
          'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
          'AND',
          'reservasi.check_in_actual' => 'IS NOT NULL',
          'AND',
          'reservasi.segmentasi' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      $segment['response_data'][$key]['counter'] = count($count_segment['response_data']);
    }
    //===================================================================================================
    $hu = 0;
    $count_hu = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'reservasi.check_in_actual' => 'IS NOT NULL',
        'AND',
        'reservasi.rate_code' => '= ?'
      ), array(
        __RATE_HU__
      ))
      ->execute();
    $hu = count($count_hu['response_data']);
    //===================================================================================================
    $no_show = 0;
    $count_no_show = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'reservasi.status_rev' => '= ?'
      ), array('N'))
      ->execute();
    $no_show = count($count_no_show['response_data']);
    //===================================================================================================
    $cancel = 0;
    $count_cancel = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'reservasi.status_rev' => '= ?'
      ), array('C'))
      ->execute();
    $cancel = count($count_cancel['response_data']);
    //===================================================================================================
    $arr = $Reservasi->count_arr();
    //===================================================================================================
    $rev_sales = 0;
    $tax = 0;
    $service = 0;

    $count_rev_sales = self::$query->select('folio_transact', array(
      'price', 'final_price'
    ))
      ->where(array(
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio_transact.transcode' => '= ?'
      ), array(
        __RULE_TRANS_RATE_CHARGE__
      ))
      ->execute();
    foreach ($count_rev_sales['response_data'] as $key => $value) {
      $rev_sales += floatval($value['price']);
      $tax += floatval($value['price']) * (11 / 100);
      $service += floatval($value['price']) * (10 / 100);
    }
    //===================================================================================================
    $extra_bed = 0;
    $count_extra_bed = self::$query->select('folio_transact', array(
      'price', ' final_price'
    ))
      ->where(array(
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio_transact.transcode' => '= ?'
      ), array(
        __RULE_EXTRA_BED__
      ))
      ->execute();
    foreach ($count_extra_bed['response_data'] as $key => $value) {
      $extra_bed += floatval($value['final_price']);
      $tax += floatval($value['final_price']) - floatval($value['price']);
      $service += floatval($value['final_price']) - floatval($value['price']);
    }
    //===================================================================================================
    $rev_other = 0;
    $count_rev_other = self::$query->select('folio_transact', array(
      'price', 'transcode', 'final_price'
    ))
      ->where(array(
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        '   folio_transact.transcode' => '!= ?',
        'AND',
        '  folio_transact.transcode' => '!= ?',
        'AND',
        ' folio_transact.transcode' => '!= ?',
        'AND',
        'folio_transact.transcode' => '!= ?'
      ), array(
        __RULE_TRANS_DEPO__,
        __RULE_TRANS_RATE_CHARGE__,
        __RULE_EXTRA_BED__,
        __RULE_REBATE__
      ))
      ->execute();
    foreach ($count_rev_other['response_data'] as $key => $value) {
      // Check Outlet
      $OutletRCheck = self::$query->select('outlet', array(
        'kode', 'trans'
      ))
        ->where(array(
          'outlet.trans' => '= ?'
        ), array(
          $value['transcode']
        ))
        ->execute();
      if (count($OutletRCheck['response_data']) <= 0) {
        $rev_other += floatval($value['final_price']);
        $tax += floatval($value['final_price']) - floatval($value['price']);
        $service += floatval($value['final_price']) - floatval($value['price']);
      }
    }
    //===================================================================================================
    $rebate = 0;
    $count_rebate = self::$query->select('folio_transact', array(
      'price', 'transcode'
    ))
      ->where(array(
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\'',
        'AND',
        'folio_transact.transcode' => '= ?'
      ), array(
        __RULE_REBATE__
      ))
      ->execute();
    foreach ($count_rebate['response_data'] as $key => $value) {
      $rebate += floatval($value['price']);
    }
    //===================================================================================================
    $TotalRev = floatval($rev_sales) + floatval($extra_bed) + floatval($rev_other) - abs(floatval($rebate));
    $Nett = $TotalRev / 1.21;

    //===================================================================================================
    $OutletTax = 0;
    $OutletService = 0;
    $OutletNet = 0;
    $OutletRCharge = self::$query->select('outlet', array(
      'uid', 'kode', 'nama', 'trans'
    ))
      ->where(array(
        'outlet.deleted_at' => 'IS NULL'
      ), array())
      ->execute();

    foreach ($OutletRCharge['response_data'] as $key => $value) {
      $CatBuild = array();
      $OutCat = self::$query->select('outlet_category', array(
        'uid', 'nama'
      ))
        ->where(array(
          'outlet_category.outlet' => '= ?',
          'AND',
          'outlet_category.deleted_at' => 'IS NULL'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($OutCat['response_data'] as $OCKey => $OCValue) {
        if (!isset($CatBuild[$OCValue['uid']])) {
          $CatBuild[$OCValue['uid']] = array(
            'nama' => '',
            'total' => 0
          );
        }

        $CatBuild[$OCValue['uid']] = array(
          'nama' => $OCValue['nama'],
          'total' => 0
        );
      }

      $Order = self::$query->select('outlet_order', array(
        'uid'
      ))
        ->where(array(
          'outlet_order.outlet' => '= ?',
          'AND',
          'outlet_order.created_at::date' => '= date \'' . date('Y-m-d') . '\''
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($Order['response_data'] as $OKey => $OValue) {
        $OrdDet = self::$query->select('outlet_order_detail', array(
          'price'
        ))
          ->join('outlet_item', array(
            'kategori'
          ))
          ->on(array(
            array('outlet_order_detail.item', '=', 'outlet_item.uid')
          ))
          ->where(array(
            'outlet_order_detail.order_uid' => '= ?'
          ), array(
            $OValue['uid']
          ))
          ->execute();
        foreach ($OrdDet['response_data'] as $ORDKey => $ORDValue) {
          $TotalOuu = floatval($ORDValue['price']);
          $OutletNet +=  $TotalOuu / 1.21;
          $OutletTax += $OutletNet * (11 / 100);
          $OutletService += $OutletNet * (10 / 100);
          $CatBuild[$ORDValue['kategori']]['total'] += $TotalOuu;
        }
      }
      $OutletRCharge['response_data'][$key]['categories'] = $CatBuild;
    }

    //===================================================================================================
    $deposite = 0;
    $count_deposite = self::$query->select('folio_transact', array(
      'price'
    ))
      ->where(array(
        'folio_transact.transcode' => '= ?',
        'AND',
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\''
      ), array(
        __RULE_TRANS_DEPO__
      ))
      ->execute();
    foreach ($count_deposite['response_data'] as $key => $value) {
      $deposite += floatval($value['price']);
    }
    //===================================================================================================
    $refund = 0;
    $count_refund = self::$query->select('folio_transact', array(
      'price'
    ))
      ->where(array(
        'folio_transact.transcode' => '= ?',
        'AND',
        'folio_transact.created_at::date' => '= date \'' . date('Y-m-d') . '\''
      ), array(
        __RULE_REFUND__
      ))
      ->execute();
    foreach ($count_refund['response_data'] as $key => $value) {
      $refund += floatval($value['price']);
    }
    //===================================================================================================
    $CL = 0;
    $CityLedger = self::$query->select('reservasi', array(
      'uid'
    ))
      ->where(array(
        'reservasi.segmentasi' => '!= ?',
        'AND',
        'reservasi.created_at::date' => '= date \'' . date('Y-m-d') . '\''
      ), array(
        __SEGMEN_INDIVIDUAL__
      ))
      ->execute();
    foreach ($CityLedger['response_data'] as $key => $value) {
      $FolioCL = self::$query->select('folio', array(
        'uid'
      ))
        ->where(array(
          'folio.reservasi' => '= ?'
        ), array(
          $value['uid']
        ))
        ->execute();
      foreach ($FolioCL['response_data'] as $FKey => $FValue) {
        $FDetCL = self::$query->select('folio_transact', array(
          'transcode', 'price', 'final_price'
        ))
          ->join('master_accounting_transact', array(
            'dbcr'
          ))
          ->on(array(
            array('folio_transact.transcode', '=', 'master_accounting_transact.uid')
          ))
          ->where(array(
            'folio_transact.folio' => '= ?'
          ), array(
            $FValue['uid']
          ))
          ->execute();
        foreach ($FDetCL['response_data'] as $FDKey => $FDValue) {
          if (isset($FDValue['final_price'])) {
            $targetPrice = floatval($FDValue['final_price']);
          } else {
            $targetPrice = floatval($FDValue['price']);
          }
          if ($FDValue['dbcr'] === 'D') {
            $CL += $targetPrice;
          } else {
            $CL -= $targetPrice;
          }
        }
      }
    }



    $builder = array(
      'room_available' => $avail,
      'room_saleable' => $saleable,
      'room_sold' => $sold,
      'room_occupied' => $occupied,
      'room_occupied_per' => number_format($occupied / ($avail + $occupied) * 100, 2, ".", ","),
      'room_sold_per' => number_format($sold / ($avail + $sold) * 100, 2, ".", ","),
      'room_oo' => $oo,
      'number_guest' => $pax,
      'compliment' => $compliment,
      'house_use' => $hu,
      'no_show' => $no_show,
      'cancel' => $cancel,
      'arr' => $arr,
      'room_sales' => $rev_sales,
      'extra_bed' => $extra_bed,
      'other_revenue' => $rev_other,
      'rebate' => $rebate,
      'room_revenue_total' => $TotalRev,
      'tax' => $Nett * (11 / 100),
      'service' => $Nett * (10 / 100),
      'room_revenue_nett' => $Nett,
      'outlet' => $OutletRCharge['response_data'],
      'cash_deposite' => $deposite,
      'refund' => $refund,
      'city_ledger' => $CL
      // 'outlet_tax' => $OutletTax,
      // 'outlet_service' => $OutletService,
      // 'outlet_revenue_nett' => $OutletNet,
    );

    foreach ($segment['response_data'] as $key => $value) {
      $builder[$value['msscode']] = $value['counter'];
    }

    return $builder;
  }
}
