<?php

namespace App\Traits;

use App\Http\Resources\Accounting\JournalResource;
use App\Models\Inventory\ItemDetail;
use App\Models\Inventory\PurchaseUnit;
use App\Models\Inventory\SalesUnit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HelperTrait
{
  use UploadTrait;
  /**
   * calcBalance
   *
   * @param  mixed $pos_report
   * @param  mixed $debet
   * @param  mixed $credit
   * @return int
   */
  function calcBalance($pos_report, $debet, $credit): int
  {
    $balance = 0;
    if (strtolower($pos_report) === 'kredit') {
      $balance = $credit - $debet;
    } else {
      $balance = $debet - $credit;
    }
    return $balance;
  }

  /**
   * getUser
   *
   * @return String
   */
  public function getUser($value = null): String
  {
    $find = DB::table('users')->select(['id']);

    if (is_null($value)) {
      return $find->first()->id;
    } else {
      return $find->where('name', $value)->first()->id;
    }
  }

  /**
   * getSubGroupId
   *
   * @param  mixed $search
   * @return String
   */
  public function getSubGroupId($search): String
  {
    $find = DB::table('sub_groups')->where('code', $search)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getGroupId
   *
   * @param  mixed $search
   * @return String
   */
  public function getGroupId($search): String
  {
    $find = DB::table('groups')
      ->where('code', $search)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * foreignCheck
   *
   * @param  mixed $val
   * @return void
   */
  public static function foreignCheck($val = 0): void
  {
    DB::statement("SET FOREIGN_KEY_CHECKS=$val;");
  }

  /**
   * getFileJson
   *
   * @param  mixed $path
   * @return void
   */
  public function getFileJson($path)
  {
    return json_decode(File::get(base_path("database/json/$path.json")));
  }

  /**
   * getConfigId
   *
   * @param  mixed $table
   * @return Array
   */
  public function getConfigId($table, $branchId = null): array
  {
    $isPrefix = !is_null($branchId) && DB::table($table)->where('branch_id', $branchId)->first();
    $codeSetting = DB::table('code_settings')->where('table', $table)->first();
    $prefixArr = explode(';', $codeSetting->prefix);
    $prefix = $prefixArr[0];
    array_key_exists(2, $prefixArr) && $prefix .= auth()->user()->employee->branch->prefix ?? optional($isPrefix)->id ?? 'HI';
    array_key_exists(1, $prefixArr) && $prefix .= date($prefixArr[1]);
    return [
      'table' => $table,
      'reset_on_prefix_change' => array_key_exists(1, $prefixArr) && true,
      'field' => $codeSetting->field,
      'prefix' => $prefix,
      'length' => $codeSetting->length,
    ];
  }

  /**
   * getBranch
   *
   * @param  mixed $name
   * @return String
   */
  function getBranch($name): String
  {
    $find = DB::table('branches')
      ->where('name', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getActivePeriode
   *
   * @return String
   */
  function getActivePeriode(): String
  {
    $find = DB::table('periodes')
      ->where('is_active', 1)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getPosition
   *
   * @param  mixed $name
   * @return String
   */
  function getPosition($name): String
  {
    $find = DB::table('positions')
      ->where('name', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }
  /**
   * getPurchaseId
   *
   * @param  mixed $name
   * @return String
   */
  private function getPurchaseId($name): String
  {
    $find = PurchaseUnit::where('name', $name)
      ->firstOrCreate(['name' => $name]);
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getSalesId
   *
   * @param  mixed $name
   * @return String
   */
  private function getSalesId($name): String
  {
    $find = SalesUnit::where('name', $name)
      ->firstOrCreate(['name' => $name]);
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getSupplierId
   *
   * @param  mixed $name
   * @param  mixed $by
   * @return String
   */
  public function getSupplierId($name, $by = null): String
  {
    $find = DB::table('suppliers')
      ->where($by ?? 'code', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getWarehouse
   *
   * @param  mixed $name
   * @return String
   */
  function getWarehouse($name): String
  {
    $find = DB::table('warehouses')
      ->where('name', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getBlok
   *
   * @param  mixed $name
   * @return String
   */
  function getBlok($name): String
  {
    $find = DB::table('bloks')
      ->where('name', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getInventoryNotes
   *
   * @param  mixed $itemId
   * @param  mixed $stock
   * @return String
   */
  function getInventoryNotes($itemId, $stock): String
  {
    $itemDetail = ItemDetail::where('item_id', $itemId)->get();
    $result = '';
    $i = 0;
    $count = $itemDetail->count();

    foreach ($itemDetail as $key => $value) {
      $divide = $value->convertion;
      $result .= intval($stock / $divide) . ' ' . $value->salesUnit->name;
      $sisa = $stock % $divide;
      $stock = $sisa;
      $i++;
      $result .= ($key !== $count - 1) ? ' @ ' : '';
    }
    return $result;
  }

  public function getAccountPost($type, $status)
  {
    # code...
  }

  /**
   * getJournal
   *
   * @param  mixed $periode
   * @return object
   */
  public function getJournal($periode = null): object
  {
    $periode = !is_null($periode) ? $periode : $this->getActivePeriode();

    $journalType = DB::table('journal_types')->where('code', 'ju')->first();
    $journal = DB::table('journals')
      ->where('journal_type_id', $journalType->id)
      ->where('periode_id', $periode)
      ->first();
    return new JournalResource($journal);
  }

  /**
   * getFieldType
   *
   * @param  mixed $table
   * @param  mixed $field
   * @return Array
   */
  private function getFieldType($table, $field): array
  {
    $connection = config('database.default');
    $driver = DB::connection($connection)->getDriverName();
    $database = DB::connection($connection)->getDatabaseName();

    if ($driver == 'mysql') {
      $sql = 'SELECT column_name AS "column_name",data_type AS "data_type",column_type AS "column_type" FROM information_schema.columns ';
      $sql .= 'WHERE table_schema=:database AND table_name=:table';
    } else {
      // column_type not available in postgres SQL
      // table_catalog is database in postgres
      $sql = 'SELECT column_name as "column_name",data_type as "data_type" FROM information_schema.columns ';
      $sql .= 'WHERE table_catalog=:database AND table_name=:table';
    }

    $rows = DB::select($sql, ['database' => $database, 'table' => $table]);
    $fieldType = null;
    $fieldLength = null;

    foreach ($rows as $col) {
      if ($field == $col->column_name) {
        $fieldType = $col->data_type;
        if ($driver == 'mysql') {
          //example: column_type int(11) to 11
          preg_match("/(?<=\().+?(?=\))/", $col->column_type, $tblFieldLength);
          $fieldLength = $tblFieldLength[0];
        } else {
          //column_type not available in postgres SQL
          $fieldLength = 32;
        }
        break;
      }
    }

    if ($fieldType == null) throw new Exception("$field not found in $table table");
    return ['type' => $fieldType, 'length' => $fieldLength];
  }
  /**
   * generate
   *
   * @param  mixed $configArr
   * @return String
   */
  public static function generate(array $configArr): String
  {
    if (!array_key_exists('table', $configArr) || $configArr['table'] == '') {
      throw new Exception('Must need a table name');
    }
    if (!array_key_exists('length', $configArr) || $configArr['length'] == '') {
      throw new Exception('Must specify the length of ID');
    }
    if (!array_key_exists('prefix', $configArr) || $configArr['prefix'] == '') {
      throw new Exception('Must specify a prefix of your ID');
    }

    if (array_key_exists('where', $configArr)) {
      if (is_string($configArr['where']))
        throw new Exception('where clause must be an array, you provided string');
      if (!count($configArr['where']))
        throw new Exception('where clause must need at least an array');
    }

    $table = $configArr['table'];
    $field = array_key_exists('field', $configArr) ? $configArr['field'] : 'id';
    $prefix = $configArr['prefix'];
    $resetOnPrefixChange = array_key_exists('reset_on_prefix_change', $configArr) ? $configArr['reset_on_prefix_change'] : false;
    $length = $configArr['length'];

    $fieldInfo = (new self)->getFieldType($table, $field);
    $tableFieldType = $fieldInfo['type'];
    $tableFieldLength = $fieldInfo['length'];

    if (in_array($tableFieldType, ['int', 'integer', 'bigint', 'numeric']) && !is_numeric($prefix)) {
      throw new Exception("$field field type is $tableFieldType but prefix is string");
    }

    if ($length > $tableFieldLength) {
      throw new Exception('Generated ID length is bigger then table field length');
    }

    $prefixLength = strlen($configArr['prefix']);
    $idLength = $length - $prefixLength;
    $whereString = '';

    if (array_key_exists('where', $configArr)) {
      $whereString .= " WHERE ";
      foreach ($configArr['where'] as $row) {
        $whereString .= $row[0] . "=" . $row[1] . " AND ";
      }
    }
    $whereString = rtrim($whereString, 'AND ');


    $totalQuery = sprintf("SELECT count(%s) total FROM %s %s", $field, $configArr['table'], $whereString);
    $total = DB::select($totalQuery);

    if ($total[0]->total) {
      if ($resetOnPrefixChange) {
        $maxQuery = sprintf("SELECT MAX(%s) max_id from %s WHERE %s like %s", $field, $table, $field, "'" . $prefix . "%'");
      } else {
        $maxQuery = sprintf("SELECT MAX(%s) max_id from %s", $field, $table);
      }

      $queryResult = DB::select($maxQuery);
      $maxFullId = $queryResult[0]->max_id;

      $max_id = substr($maxFullId, $prefixLength, $idLength);
      return $prefix . str_pad($max_id + 1, $idLength, '0', STR_PAD_LEFT);
    } else {
      return $prefix . str_pad(1, $idLength, '0', STR_PAD_LEFT);
    }
  }

  /**
   * getCity
   *
   * @param  mixed $name
   * @return String
   */
  public function getCity($name): String
  {
    $find = DB::table('cities')
      ->where('name', 'like', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getDistrict
   *
   * @param  mixed $name
   * @return String
   */
  public function getDistrict($name): String
  {
    $find = DB::table('districts')
      ->where('name', 'like', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getSubDistrict
   *
   * @param  mixed $name
   * @return String
   */
  public function getSubDistrict($name): String
  {
    $find = DB::table('sub_districts')
      ->where('name', 'like', $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getStatusTransaction
   *
   * @param  mixed $name, $operator
   * @return String
   */
  public function getStatusTransaction($name, $operator = '='): String
  {
    $find = DB::table('transaction_statuses')
      ->where('name', $operator, $name)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  public function getStatusTransactions($names): object
  {
    $find = DB::table('transaction_statuses')
      ->whereIn('name', $names)->get();
    return $find;
  }

  /**
   * getCustomerStatus
   *
   * @param  mixed $value
   * @return String
   */
  public function getCustomerStatus($value): String
  {
    $find = DB::table('customer_statuses')->select(['id'])
      ->where('name', $value)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getCustomerCategory
   *
   * @param  mixed $value
   * @return String
   */
  public function getCustomerCategory($value): String
  {
    $find = DB::table('customer_categories')->select(['id'])
      ->where('name', $value)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  /**
   * getEmployee
   *
   * @param  mixed $value
   * @param  mixed $by
   * @return String
   */
  public function getEmployee($value, $by = 'code'): String
  {
    $find = DB::table('employees')
      ->select(['id', 'code'])
      ->where($by, $value)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  public function getCustomer($value, $by = 'id'): object
  {
    $find = DB::table('customers')
      ->select(['id', 'code'])
      ->where($by, $value)->first();
    if ($find) {
      return $find->id;
    }
    return null;
  }

  public function getFromTable(string $table, string $where, $value)
  {
    $find = DB::table($table)
      ->where($table . '.' . $where, 'like', '%' . $value);
    return $find;
  }

  public function sum(array $value): int
  {
    return array_sum($value);
  }

  public function helperUpload(Request $request, string $file, string $name, string $path = null): string
  {
    if ($request->has($file)) {
      $image = $request->file($file);
      $name = Str::slug($name) . '_' . time();
      $filePath = $this->uploadOne($image, $path, $name);
      return $filePath;
    }
    return '';
  }

  public function checkStok(array $config)
  {
    $itemDetail = DB::table('item_details')
      ->select(['item_id', 'barcode', 'sales_units.name', 'sales_value', 'convertion'])
      ->where('item_details.id', $config['itemDetail'])
      ->join('sales_units', 'item_details.sales_unit_id', 'sales_units.id')
      ->first();

    if (is_null($itemDetail)) {
      return false;
    }
    $inventory = DB::table('inventories')->where('item_id', $itemDetail->item_id)
      ->where('warehouse_id', $config['warehouse'])->first();
    if (is_null($inventory)) {
      return false;
    }
    /**
     * check
     */

    $tempQty = $config['qty'] * $itemDetail->convertion;
    if ($inventory->stok <= $tempQty) {
      return false;
    }
    return true;
  }

  /**
   * lockTable
   *
   * @param  mixed $table
   * @param  mixed $type
   * @return void
   */
  public function lockTable(String $table, String $type = 'WRITE')
  {
    $sql = "LOCK TABLE {$table} {$type}";
    DB::unprepared($sql);
  }

  public function unlockTable()
  {
    DB::unprepared("UNLOCK TABLES");
  }
}
