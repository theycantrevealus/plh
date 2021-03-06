<?php

namespace PondokCoder;

use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;
use PondokCoder\Authorization as Authorization;
use PondokCoder\Terminologi as Terminologi;

class Wilayah extends Utility
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

  public function __GET__($parameter = array())
  {
    try {
      switch ($parameter[1]) {
        case 'provinsi':
          return self::get_provinsi('master_wilayah_provinsi');
          break;

        case 'kabupaten':
          return self::get_wilayah_child('master_wilayah_kabupaten', 'provinsi', $parameter[2]);
          break;

        case 'kecamatan':
          return self::get_wilayah_child('master_wilayah_kecamatan', 'kabupaten', $parameter[2]);
          break;

        case 'kelurahan':
          return self::get_wilayah_child('master_wilayah_kelurahan', 'kecamatan', $parameter[2]);
          break;

        case 'negara':
          return self::get_wilayah_negara();
          break;

        case 'kabupaten_free':
          return self::get_kabupaten_free();
          break;

        default:
          # code...
          break;
      }
    } catch (QueryException $e) {
      return 'Error => ' . $e;
    }
  }

  public function get_kabupaten_free()
  {
    $data = self::$query->select('master_wilayah_kabupaten', array(
      'id', 'nama'
    ))
      ->where(array(
        'master_wilayah_kabupaten.nama' => 'ILIKE ' . '\'%' . strtoupper($_GET['search']) . '%\''
      ), array())
      ->execute();
    return $data;
  }

  public function get_wilayah_negara()
  {
    $data = self::$query->select('master_wilayah_negara', array(
      'id', 'alpha_3_code', 'en_short_name', 'nationality'
    ))
      ->where(array(
        'master_wilayah_negara.deleted_at' => 'IS NULL',
        'AND',
        'master_wilayah_negara.nationality' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\''
      ), array())
      ->execute();
    return $data;
  }


  public function get_provinsi($table)
  {
    $data = self::$query
      ->select(
        $table,
        array(
          'id',
          'nama'
        )
      )
      ->execute();

    $autonum = 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    return $data;
  }

  public function get_wilayah_child($table, $fkey, $parameter)
  {
    $data = self::$query
      ->select(
        $table,
        array(
          'id',
          $fkey,
          'nama'
        )
      )
      ->where(
        array(
          $table . '.' . $fkey => '= ?'
        ),
        array(
          $parameter
        )
      )
      ->execute();

    $autonum = 1;
    foreach ($data['response_data'] as $key => $value) {
      $data['response_data'][$key]['autonum'] = $autonum;
      $autonum++;
    }

    return $data;
  }
}
