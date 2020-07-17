<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\KpiCategory;
use Modules\General\Entities\KpiEmployee;
use Modules\General\Entities\KpiEmployeeDetail;
use Modules\General\Entities\KpiEmployeeResultDetail;
use Modules\General\Transformers\KPI\Employee\KpiEmployeeDetailCalculationResource;
use Modules\PublicWarehouse\Entities\GoodsBorrowItem;
use Modules\PublicWarehouse\Entities\GoodsRepairment;
use Modules\PublicWarehouse\Entities\GoodsRequestItemOut;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Entities\StockTransaction;

// GENERAL
    if (! function_exists('getWorkDay')) {
        function getWorkDay($date = null)
        {
            $date       = (empty($date)) ? date('Y-m-d') : $date;
            $day        = 0;
            $work_day   = 0;
            $month      = substr($date, 5, 2);
            $year       = substr($date, 0, 4);
            $holiday    = ['2020-06-24', '2020-06-16', '2020-06-20']; // Libur Ambil dari Aplikasi HRD
            if ($month == date('m') && $year == date('Y')) {
                $day = (int) date('d');
            } else {
                $day = getTotalDay($month, $year);
            }

            for ($i=1; $i<=$day; $i++) {
                $check_date = $year."-".$month."-".str_pad($i,2,0,STR_PAD_LEFT);
                if (date("D",strtotime($check_date)) != "Sun" && array_search($check_date, $holiday)==null) {
                    $work_day++;
                }
            }
            return $work_day;
        }
    }

    if (! function_exists('getKPIDate')) {
        function getKPIDate($date = null)
        {
            if (empty($date)) {
                $data['date'] = date('Y-m-d');
            } else {
                $month = substr($date,5,2);
                $year  = substr($date,0,4);
                if ($month==date('m') && $year==date('Y')) {
                    $data['date'] = $data['date'] = date('Y-m-d');
                } else {
                    $data['date'] = $year."-".$month."-".getTotalDay($month, $year);
                }
            }
            $data['month']  = (empty($date)) ? date('m') : substr($date,5,2);
            $data['year']   = (empty($date)) ? date('Y') : substr($date,0,4);

            return $data;
        }
    }

    if (! function_exists('getKPICategoryName')) { // OK
        function getKPICategoryName($id)
        {
            $data = KpiCategory::where('id', $id)->first();
            return (!empty($data)) ? $data->name : null;
        }
    }

    if (! function_exists('getWorkEmployee')) {
        function getWorkEmployee($nik, $date = null)
        {
            $nik = $nik;
            return getWorkDay($date);
        }
    }

    if (! function_exists('getKPIGrade')) { // OK
        function getKPIGrade($total_score)
        {
            if ($total_score>85) {
                return "A";
            } elseif ($total_score>75 && $total_score<=85) {
                return "B";
            } elseif ($total_score>65 && $total_score<=75) {
                return "C";
            } elseif ($total_score>49 && $total_score<=65) {
                return "D";
            } else {
                return "E";
            }
        }
    }

    if (! function_exists('getKPIDashboard')) { // OK
        function getKPIDashboard()
        {
            $employee_nik    = Auth::user()->employee_nik;
            $kpiEmployee     = KpiEmployee::where('employee_nik', $employee_nik)->first();
            $kpi_employee_id = (!empty($kpiEmployee)) ? $kpiEmployee->id : 0;

            $last_month = Carbon::now()->subMonth(1);
            $last_month = $last_month->format('Y-m')."-".getTotalDay($last_month->format('m'), $last_month->format('Y'));

            if ($kpi_employee_id>0) {
                $data['current'] = [
                    'score' => getKPIEmployeeScore($kpi_employee_id, 'score', date('Y-m-d')),
                    'value' => getKPIEmployeeScore($kpi_employee_id, 'value', date('Y-m-d')),
                    'month' => getMonthName(date('m'))." ".date('Y'),
                    'kpi_employee_id' => $kpi_employee_id,
                    'date' => date('Y-m-d'),
                ];
                $data['last'] = [
                    'score' => getKPIEmployeeScore($kpi_employee_id, 'score', $last_month),
                    'value' => getKPIEmployeeScore($kpi_employee_id, 'value', $last_month),
                    'month' => getMonthName(substr($last_month,5,2))." ".substr($last_month,0,4),
                    'kpi_employee_id' => $kpi_employee_id,
                    'date' => $last_month,
                ];
            } else {
                $data['current'] = [
                    'score' => '0',
                    'value' => 'E',
                    'month' => getMonthName(date('m'))." ".date('Y'),
                    'kpi_employee_id' => $kpi_employee_id,
                    'date' => date('Y-m-d'),
                ];
                $data['last'] = [
                    'score' => '0',
                    'value' => 'E',
                    'month' => getMonthName(substr($last_month,5,2))." ".substr($last_month,0,4),
                    'kpi_employee_id' => $kpi_employee_id,
                    'date' => $last_month,
                ];
            }

            return $data;
        }
    }

    if (! function_exists('getKPIResult')) { // OK
        function getKPIResult($nik, $formula_code, $month, $year)
        {
            $kpi = KpiEmployeeResultDetail::query()
                ->whereHas('kpiFormula', function ($query) use ($formula_code) {
                    $query->where('formula_code', $formula_code);
                })
                ->whereHas('KpiEmployeeResult', function ($query) use ($nik) {
                    $query->where('employee_nik', $nik);
                })
                ->whereMonth('kpi_date', $month)
                ->whereYear('kpi_date', $year)
                ->first();

            if (!empty($kpi)) {
                $data['a'] = ($kpi->kpi_a>0) ? $kpi->kpi_a : 0;
                $data['b'] = ($kpi->kpi_b>0) ? $kpi->kpi_b : 0;
            } else {
                $data['a'] = 0;
                $data['b'] = 0;
            }
            return $data;
        }
    }

// KPI CALCULATION

    if (! function_exists('getKPIEmployeeDetail')) { // OK
        function getKPIEmployeeDetail($id, $date)
        {
            $KpiEmployeeDetail = KpiEmployeeDetail::query()
                ->select('*')
                ->addSelect(DB::raw("'$date' as filterDate"))
                ->where('kpi_employee_id', $id)
                ->where('is_active', 1)
                ->orderBy('kpi_formula_id', 'asc')
                ->get();
            return KpiEmployeeDetailCalculationResource::collection($KpiEmployeeDetail);
        }
    }

    if (! function_exists('getFinalScore')) { // OK
        function getFinalScore($score, $percen)
        {
            return round($score * ($percen/100), 2);
        }
    }

    if (! function_exists('getKPIEmployeeScore')) { // OK
        function getKPIEmployeeScore($id, $type, $date)
        {
            $KpiEmployeeDetail = KpiEmployeeDetail::query()
                ->select('*')
                ->addSelect(DB::raw("'$date' as filterDate"))
                ->where('kpi_employee_id', $id)
                ->where('is_active', 1)
                ->get();

            $total_score = 0;
            foreach ($KpiEmployeeDetail as $formula) {
                $nik          = $formula['KpiEmployee']['employee_nik'];
                $formula_code = $formula['kpiFormula']['formula_code'];
                $target_score = $formula['target_score'];
                $data         = getKPIScore($nik, $formula_code, $target_score, $date);
                $total_score  = $total_score + getFinalScore($data['score'], $formula['percen']);
            }
            return ($type=="score") ? round($total_score, 2) : getKPIGrade($total_score);
        }
    }

    if (! function_exists('getKPIScore')) {
        function getKPIScore($nik = null, $formula_code = null, $target = null, $date = null)
        {
            $score = null;
            switch ($formula_code) {
                case "KPIWH01":
                    $score = getKPIWarehouseGoodsRequest($nik, $target, $date);
                    break;
                case "KPIWH02":
                    $score = getKPIWarehouseMOQ($nik, $target, $date);
                    break;
                case "KPIWH03":
                    $score = getKPIWarehouseGoodsBorrow($nik, $target, $date);
                    break;
                case "KPIWH04":
                    $score = getKPIWarehouseStockOpnameDaily($nik, $target, $date);
                    break;
                case "KPIWH05":
                    $score = getKPIWarehouseStockOpnameDailyResult($nik, $target, $date);
                    break;
                case "KPIWH06":
                    $score = getKPIWarehouseStockOpnamePeriod($nik, $target, $date);
                    break;
                case "KPIWH07":
                    $score = getKPIWarehouseStockOpnamePeriodResult($nik, $target, $date);
                    break;
                case "KPIWH08":
                    $score = getKPIWarehouseBufferStock($nik, $target, $date);
                    break;
                case "KPIWH09":
                    $score = getKPIWarehouseQuarantine($nik, $target, $date);
                    break;
                case "KPIWH10":
                    $score = getKPIWarehouseDeadStock($nik, $target, $date);
                    break;
                case "KPIWH11":
                    $score = getKPIWarehouseGoodsRepairment($nik, $target, $date);
                    break;
                case "KPIWH12":
                    $score = getKPIWarehouseEmployeeActivity($nik, $target, $date);
                    break;
                case "KPIWH13":
                    $score = getKPIWarehouseEmployeeAttendance($nik, $target, $date);
                    break;
                case "KPIWH14":
                    $score = getKPIWarehouseTeamAverage($nik, $target, $date);
                    break;
                case "KPIWH15":
                    $score = getKPIWarehousePurchaseRequest($nik, $target, $date);
                    break;


                case "KPIPC01":
                    $score = getKPIPurchasingGoodsQuality($nik, $target, $date);
                    break;
                case "KPIPC02":
                    $score = getKPIPurchasingEvaluation($nik, $target, $date);
                    break;
                case "KPIPC03":
                    $score = getKPIPurchasingDoOnTime($nik, $target, $date);
                    break;
                case "KPIPC04":
                    $score = getKPIPurchasingEvaluationSupplier($nik, $target, $date);
                    break;
                case "KPIPC05":
                    $score = getKPIPurchasingPaymentOnTime($nik, $target, $date);
                    break;
                case "KPIPC06":
                    $score = getKPIPurchasingTeamAverage($nik, $target, $date);
                    break;
                default:
                    $score = 0;
                    break;
            }

            return $score;
        }
    }


// KPI FORMULA WAREHOUSE

    if (! function_exists('getKPIWarehouseGoodsRequest')) { //1:KPIWH01
        function getKPIWarehouseGoodsRequest($nik, $target = 0, $args_date = null)
        {
            // No               : 1
            // kode formula     : KPIWH01
            // Point Penilaian  : Permintaan Barang
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : Max 7 hari
            //                    Jumlah item barang terpenuhi tepat waktu
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Keseluruhan jumlah permintaan barang

            $kpiDate  = getKPIDate($args_date);
            $month    = $kpiDate['month'];
            $year     = $kpiDate['year'];
            $date     = $kpiDate['date'];

            // Total permintaan barang
            $request = GoodsRequestItems::with('goodsRequest')
                        ->select(DB::raw('sum(quantity) as total'))
                        ->whereHas('goodsRequest', function ($query) use ($month, $year) {
                            $query->where(function ($query) use ($month, $year) {
                                $query->whereMonth('transaction_date', $month)
                                    ->whereYear('transaction_date', $year)
                                    ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 7 DAY)')
                                    ->whereIn('status', [3])
                                    ->where('is_active', 1);
                            })
                            ->orWhere(function ($query) use ($month, $year) {
                                $query->whereMonth('transaction_date', $month)
                                    ->whereYear('transaction_date', $year)
                                    ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 7 DAY)')
                                    ->whereIn('status', [1, 2])
                                    ->where('is_active', 1);
                            });
                        })
                        ->where('is_active', 1)
                        ->whereIn('status', [1, 2, 3, 4 ])
                        ->first();
            $request_total = (!empty($request->total)) ? $request->total : 0;

            // Total terpenuhi tepat waktu
            $outs = GoodsRequestItemOut::with('goodsRequestItem')
                        ->select(DB::raw('sum(quantity) as total'))
                        ->whereHas('goodsRequestItem', function ($query) use ($month, $year, $date) {
                            $query->whereHas('goodsRequest', function ($query) use ($month, $year, $date) {
                                $query->select('transaction_date')
                                    ->whereRaw('ic_goods_request_item_outs.out_date <= DATE_ADD(transaction_date, INTERVAL 7 DAY)')
                                    ->whereMonth('transaction_date', $month)
                                    ->whereYear('transaction_date', $year)
                                    ->whereIn('status', [1, 2, 3 ])
                                    ->where('is_active', 1);
                            })
                            ->where('is_active', 1)
                            ->whereIn('status', [1, 2, 3, 4 ]);
                        })
                        ->where('is_active', 1)
                        ->whereIn('status', [1, 2, 3, 4 ])
                        ->first();
            $outs_total = (!empty($outs->total)) ? $outs->total : 0;

            if ($request_total<=0) {
                $data['a']      = $request_total;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $request_total;
                $data['b']      = $outs_total;
                $data['score']  = round((($outs_total/$request_total)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseMOQ')) { //2:KPIWH02
        function getKPIWarehouseMOQ($nik, $target = 0, $args_date = null)
        {
            // No               : 2
            // kode formula     : KPIWH02
            // Point Penilaian  : Ketersediaan Barang
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Tidak boleh ada barang yang kosong
            //                    Jumlah barang tidak tersedia
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah Keseluruhan Barang

            // Untuk KPI ini (KPIWH02) ketika closing stok harus di simpan data akhirnya ke KPI result

            $kpiDate  = getKPIDate($args_date);
            $month    = $kpiDate['month'];
            $year     = $kpiDate['year'];
            $date     = $kpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $moq = Item::query()
                        ->where('min_stock', '>', 0)
                        ->where('current_stock', 0)
                        ->where('is_active', 1)
                        ->get();

                $item = Item::query()
                        ->where('is_active', 1)
                        ->get();

                $total_moq  = ($moq->count()>0) ? $moq->count() : 0;
                $total_item = ($item->count()>0) ? $item->count() : 0;
            } else {
                $kpiData    = getKPIResult($nik, 'KPIWH02', $month, $year);
                $total_moq  = $kpiData['b'];
                $total_item = $kpiData['a'];
            }

            if ($total_moq<=0) {
                $data['a']      = $total_item;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_item;
                $data['b']      = $total_moq;
                //$data['score'] = round(((($total_item-$total_moq)/$total_item)*100), 2);
                $data['score']  = ($total_item==$total_moq) ? 100 : 0;
            }
            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseGoodsBorrow')) { //3:KPIWH03
        function getKPIWarehouseGoodsBorrow($nik, $target = 0, $args_date = null)
        {
            // No               : 3
            // kode formula     : KPIWH03
            // Point Penilaian  : Peminjaman Barang
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Tanggal jatuh tempo
            //                    Jumlah pengembalian barang yang sesuai tanggal jatuh tempo
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Total barang yang dipinjam

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            $return = GoodsBorrowItem::with('goodsBorrow')
                            ->select(DB::raw('sum(quantity) as total'))
                            ->whereHas('goodsBorrow', function ($query) use ($month, $year, $date) {
                                $query->select('target_return_date')
                                    ->whereColumn('ic_goods_borrow_items.return_date','<=','target_return_date')
                                    ->whereMonth('target_return_date', $month)
                                    ->whereYear('target_return_date', $year)
                                    ->whereDate('target_return_date', '<=', $date);
                            })
                            ->where('return_status', 1)
                            ->first();
            $return_total = (!empty($return->total)) ? $return->total : 0;

            $borrow = GoodsBorrowItem::with('goodsBorrow')
                            ->select(DB::raw('sum(quantity) as total'))
                            ->whereHas('goodsBorrow', function ($query) use ($month, $year, $date) {
                                $query->select('target_return_date')
                                    ->whereMonth('target_return_date', $month)
                                    ->whereYear('target_return_date', $year)
                                    ->whereDate('target_return_date', '<=', $date);
                            })
                            ->first();
            $borrow_total = (!empty($borrow->total)) ? $borrow->total : 0;



            if ($borrow_total>0) {
                $data['a'] = $borrow_total;
                $data['b'] = $return_total;
                //$data['score'] = round((($return_total/$borrow_total)*100), 2);
                $data['score'] = ($return_total==$borrow_total) ? 100 : 0;
            } else {
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 100;
            }

            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseStockOpnameDaily')) { //4:KPIWH04
        function getKPIWarehouseStockOpnameDaily($nik, $target = 0, $args_date = null)
        {
            // No               : 4
            // kode formula     : KPIWH04
            // Point Penilaian  : Pelaksanaan Stock Opname Harian
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Jumlah hari kerja (100%)
            //
            // Rumus KPI        : (Jumlah stock opname terlaksana == Jumlah hari kerja dalam 1 bulan) ?  100 : 0

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            $stockOpname = StockOpnameGroup::query()
                        ->whereMonth('issue_date', $month)
                        ->whereYear('issue_date', $year)
                        ->where('type', 'daily')
                        ->get();
            $total_stockopname = (!empty($stockOpname->count())) ? $stockOpname->count() : 0;

            $workDay     = getWorkDay($date);

            if ($stockOpname->count()>0) {
                $data['a'] = $workDay;
                $data['b'] = $total_stockopname;
                $data['score'] = ($workDay==$total_stockopname) ? 100 : 0;
            } else {
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 0;
            }
            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseStockOpnameDailyResult')) { //5:KPIWH05
        function getKPIWarehouseStockOpnameDailyResult($nik, $target = 0, $args_date = null)
        {
            // No               : 5
            // kode formula     : KPIWH05
            // Point Penilaian  : Kesesuaian data stock opname harian
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Jumlah item sesuai antara data fisik dan data keluar-masuk barang (100%)
            //
            // Rumus KPI        : (Jumlah barang sesuai antara fisik dan keluar -masuk barang == Jumlah seluruh barang pada data keluar masuk barang) ?  100 : 0

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            $dataBalance = StockOpname::with('stockOpnameGroup')
                        ->whereHas('stockOpnameGroup', function ($query) use ($month, $year) {
                            $query->whereMonth('issue_date', $month)
                            ->whereYear('issue_date', $year)
                            ->where('type', 'daily');
                        })
                        ->whereColumn('quantity_prev', 'quantity_new')
                        ->get();
            $total_balance = (!empty($dataBalance->count())) ? $dataBalance->count() : 0;

            $dataStockOpname = StockOpname::with('stockOpnameGroup')
                        ->whereHas('stockOpnameGroup', function ($query) use ($month, $year) {
                            $query->whereMonth('issue_date', $month)
                            ->whereYear('issue_date', $year)
                            ->where('type', 'daily');
                        })
                        ->get();
            $total_stockopname = (!empty($dataStockOpname->count())) ? $dataStockOpname->count() : 0;

            if ($total_stockopname>0) {
                $data['a'] = $total_stockopname;
                $data['b'] = $total_balance;
                $data['score'] = ($total_balance==$total_stockopname) ? 100 : 0;
            } else {
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 0;
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseStockOpnamePeriod')) { //6:KPIWH06
        function getKPIWarehouseStockOpnamePeriod($nik, $target = 0, $args_date = null)
        {
            // No               : 6
            // kode formula     : KPIWH06
            // Point Penilaian  : Pelaksanaan stock opname periode
            // Satuan           : Tanggal
            // Bobot            : 10%
            // Target           : Tanggal pelaksanaan stok opname (100%)
            //
            // Rumus KPI        : (Realisasi stok opname sesuai jadwal) ?  100 : 0

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];
            $realisasi = 1;

            if ($realisasi) {
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 100;
            } else {
                // Jika tidak melaksanakan stokopname periode
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 100;
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseStockOpnamePeriodResult')) { //7:KPIWH07
        function getKPIWarehouseStockOpnamePeriodResult($nik, $target = 0, $args_date = null)
        {
            // No               : 7
            // kode formula     : KPIWH07
            // Point Penilaian  : Kesesuaian data stock opname periode
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Jumlah item sesuai antara data fisik dan data keluar-masuk barang (100%)
            //
            // Rumus KPI        : (Jumlah barang sesuai antara fisik dan keluar-masuk barang == Jumlah seluruh barang ada daa keluar masuk barang) ?  100 : 0

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            $dataBalance = StockOpname::with('stockOpnameGroup')
                        ->whereHas('stockOpnameGroup', function ($query) use ($month, $year) {
                            $query->whereMonth('issue_date', $month)
                            ->whereYear('issue_date', $year)
                            ->where('type', 'period');
                        })
                        ->whereColumn('quantity_prev', 'quantity_new')
                        ->get();
            $total_balance = (!empty($dataBalance->count())) ? $dataBalance->count() : 0;

            $dataStockOpname = StockOpname::with('stockOpnameGroup')
                        ->whereHas('stockOpnameGroup', function ($query) use ($month, $year) {
                            $query->whereMonth('issue_date', $month)
                            ->whereYear('issue_date', $year)
                            ->where('type', 'period');
                        })
                        ->get();
            $total_stockopname = (!empty($dataStockOpname->count())) ? $dataStockOpname->count() : 0;

            if ($total_stockopname>0) {
                $data['a'] = $total_stockopname;
                $data['b'] = $total_balance;
                $data['score'] = ($total_balance==$total_stockopname) ? 100 : 0;
            } else {
                $data['a'] = 0;
                $data['b'] = 0;
                $data['score'] = 100;
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseBufferStock')) { //8:KPIWH08
        function getKPIWarehouseBufferStock($nik, $target = 0, $args_date = null)
        {
            // No               : 8
            // kode formula     : KPIWH08
            // Point Penilaian  : Kesesuaian barang sesuai dengan kategori buffer stock
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Seluruh item >= buffer stock (100%)
            //                    Jumlah barang keseluruhan - Jumlah barang sesuai dengan buffer stock
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah barang keseluruhan

            // Untuk KPI ini (KPIWH08) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $bufferStock = Item::query()
                        ->where('min_stock', '>', 0)
                        ->whereColumn('min_stock','>=','current_stock')
                        ->where('is_active', 1)
                        ->get();

                $item = Item::query()
                        ->where('is_active', 1)
                        ->get();

                $total_buffer  = ($bufferStock->count()>0) ? $bufferStock->count() : 0;
                $total_item = ($item->count()>0) ? $item->count() : 0;
            } else {
                $kpiData       = getKPIResult($nik, 'KPIWH08', $month, $year);
                $total_buffer  = $kpiData['b'];
                $total_item    = $kpiData['a'];
            }

            if ($total_buffer<=0) {
                $data['a'] = $total_item;
                $data['b'] = 0;
                $data['score'] = 100;
            } else {
                $data['a'] = $total_item;
                $data['b'] = $total_buffer;
                //$data['score'] = round(((($total_item-$total_buffer)/$total_item)*100), 2);
                $data['score'] = ($total_item==$total_buffer) ? 100 : 0;
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseQuarantine')) { //9:KPIWH09
        function getKPIWarehouseQuarantine($nik, $target = 0, $args_date = null)
        {
            // No               : 9
            // kode formula     : KPIWH09
            // Point Penilaian  : Barang tidak bergerak
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Maksimal penyimpanan 6 bulan
            //                    Jumlah barang keseluruhan - Jumlah barang tidak bergerak
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah barang keseluruhan

            // Untuk KPI ini (KPIWH09) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $filter_date = Carbon::now()->subMonth(6);
                $filter_date = $filter_date->format('Y-m-d')." 00:00:00";

                $checkQuarantine = StockTransaction::query()
                        ->select(DB::raw('sum(stock_current) as total_stock'))
                        ->whereIn('transaction_symbol', ['DO', 'OA'])
                        ->where(function ($query) use ($filter_date) {
                            $query->where('transaction_date', '<=', $filter_date)
                                ->where('stock_quarantine_id', null)
                                ->where('stock_category', StockTransaction::SC_ACTIVE)
                                ->orWhere('stock_quarantine_date', '<=', $filter_date)
                                ->where('stock_quarantine_id', '>', 0)
                                ->where('stock_category', StockTransaction::SC_QUARANTINE);
                        })
                        ->where('stock_current', '>', 0)
                        ->first();

                $total_check_quarantine  = ($checkQuarantine->total_stock>0) ? (int) $checkQuarantine->total_stock : 0;

                $quarantine = StockQuarantine::query()
                        ->select(DB::raw('sum(quantity) as total_stock'))
                        ->whereMonth('action_date', $month)
                        ->whereYear('action_date', $year)
                        ->where('action', 1)
                        ->first();
                $total_status_quarantine  = ($quarantine->total_stock>0) ? (int) $quarantine->total_stock : 0;
                $total_quarantine         = $total_check_quarantine + $total_status_quarantine;

                $item = Item::query()
                        ->select(DB::raw('sum(current_stock) as total_stock'))
                        ->where('current_stock', '>', 0)
                        ->where('is_active', 1)
                        ->first();

                $total_item = ($item->total_stock>0) ? (int) $item->total_stock : 0;
            } else {
                $kpiData           = getKPIResult($nik, 'KPIWH09', $month, $year);
                $total_quarantine  = $kpiData['b'];
                $total_item        = $kpiData['a'];
            }

            if ($total_quarantine<=0) {
                $data['a'] = $total_item;
                $data['b'] = 0;
                $data['score'] = 100;
            } else {
                $data['a'] = $total_item;
                $data['b'] = $total_quarantine;
                $data['score'] = round(((($total_item-$total_quarantine)/$total_item)*100), 2);
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseDeadStock')) { //10:KPIWH10
        function getKPIWarehouseDeadStock($nik, $target = 0, $args_date = null)
        {
            // No               : 10
            // kode formula     : KPIWH10
            // Point Penilaian  : Dead stock barang
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Tidak boleh ada barang yang keluar
            //                    Jumlah barang dead stock - Jumlah barang keseluruhan
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah barang keseluruhan

            // Untuk KPI ini (KPIWH10) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $deadstock = StockQuarantine::query()
                        ->select(DB::raw('sum(quantity) as total_stock'))
                        ->whereMonth('action_date', $month)
                        ->whereYear('action_date', $year)
                        ->where('action', 2)
                        ->first();
                $total_deadstock = ($deadstock->total_stock>0) ? (int) $deadstock->total_stock : 0;

                $item = Item::query()
                        ->select(DB::raw('sum(current_stock) as total_stock'))
                        ->where('current_stock', '>', 0)
                        ->where('is_active', 1)
                        ->first();

                $total_item = ($item->total_stock>0) ? (int) $item->total_stock : 0;
            } else {
                $kpiData           = getKPIResult($nik, 'KPIWH10', $month, $year);
                $total_deadstock   = $kpiData['b'];
                $total_item        = $kpiData['a'];
            }

            if ($total_deadstock<=0) {
                $data['a'] = $total_item;
                $data['b'] = 0;
                $data['score'] = 100;
            } else {
                $data['a'] = $total_item;
                $data['b'] = $total_deadstock;
                $data['score'] = round(((($total_item-$total_deadstock)/$total_item)*100), 2);
            }
            return $data;

        }
    }

    if (! function_exists('getKPIWarehouseGoodsRepairment')) { //10:KPIWH11
        function getKPIWarehouseGoodsRepairment($nik, $target = 0, $args_date = null)
        {
            // No               : 11
            // kode formula     : KPIWH11
            // Point Penilaian  : Perbaikan barang
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : Keputusan maksimal 3 hari (Diperbaiki/tidak)
            //                    Jumlah keputusan tepat waktu
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah keseluruhan perbaikan barang dalam satu bulan

            // Untuk KPI ini (KPIWH11) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $done = GoodsRepairment::query()
                        ->whereMonth('transaction_date', $month)
                        ->whereYear('transaction_date', $year)
                        ->where('analysis_status', '>', 1)
                        ->whereRaw('analysis_date <= DATE_ADD(transaction_date, INTERVAL 3 DAY)')
                        ->get();
                $total_done = ($done->count()>0) ? (int) $done->count() : 0;

                $request = GoodsRepairment::query()
                        ->whereMonth('transaction_date', $month)
                        ->whereYear('transaction_date', $year)
                        ->where(function ($query) {
                            $query->where(function ($query) {
                                $query->where('analysis_status', 1)
                                    ->whereRaw('transaction_date > DATE_ADD(transaction_date, INTERVAL 3 DAY)');
                            })
                            ->orWhere(function ($query) {
                                $query->where('analysis_status', '>', 1);
                            });
                        })

                        ->get();
                $total_request = ($request->count()>0) ? (int) $request->count() : 0;
            } else {
                $kpiData        = getKPIResult($nik, 'KPIWH11', $month, $year);
                $total_done     = $kpiData['b'];
                $total_request  = $kpiData['a'];
            }

            if ($total_request>0) {
                $data['a']      = $total_request;
                $data['b']      = $total_done;
                $data['score']  = round(((($total_done)/$total_request)*100), 2);
            } else {
                $data['a']      = $total_request;
                $data['b']      = $total_done;
                $data['score']  = 100;
            }

            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseEmployeeActivity')) { //10:KPIWH12
        function getKPIWarehouseEmployeeActivity($nik, $target = 0, $args_date = null)
        {
            // No               : 12
            // kode formula     : KPIWH12
            // Point Penilaian  : Keaktifan karyawan
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : -
            //                    Jumlah transaksi
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah transaksi keseluruhan dalam 1 bulan

            // Untuk KPI ini (KPIWH12) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                //$done    = ;
                //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
                $total_done = 1;

                //$request = ;
                //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
                $total_request = 1;
            } else {
                $kpiData        = getKPIResult($nik, 'KPIWH12', $month, $year);
                $total_done     = $kpiData['b'];
                $total_request  = $kpiData['a'];
            }

            if ($total_request>0) {
                $data['a']      = $total_request;
                $data['b']      = $total_done;
                $data['score']  = round(((($total_done)/$total_request)*100), 2);
            } else {
                $data['a']      = $total_request;
                $data['b']      = $total_done;
                $data['score']  = 100;
            }

            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseEmployeeAttendance')) { //10:KPIWH13
        function getKPIWarehouseEmployeeAttendance($nik, $target = 0, $args_date = null)
        {
            // No               : 13
            // kode formula     : KPIWH13
            // Point Penilaian  : Absensi karyawan
            // Satuan           : Jumlah
            // Bobot            : 10%
            // Target           : -
            //                    Jumlah hari kerja
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Total hari kerja dalam 1 bulan

            // Untuk KPI ini (KPIWH13) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                $kpi_done  = getWorkEmployee($nik, $date);
                $kpi_total = getWorkDay($date);
            } else {
                $kpiData    = getKPIResult($nik, 'KPIWH13', $month, $year);
                $kpi_done   = $kpiData['b'];
                $kpi_total  = $kpiData['a'];
            }

            if ($kpi_total>0) {
                $data['a']      = $kpi_total;
                $data['b']      = $kpi_done;
                $data['score']  = round(((($kpi_done)/$kpi_total)*100), 2);
            } else {
                $data['a']      = $kpi_total;
                $data['b']      = $kpi_done;
                $data['score']  = 100;
            }

            return $data;
        }
    }

    if (! function_exists('getKPIWarehouseTeamAverage')) { //10:KPIWH14
        function getKPIWarehouseTeamAverage($nik, $target = 0, $args_date = null)
        {
            // No               : 14
            // kode formula     : KPIWH14
            // Point Penilaian  : Rata-rata KPI tim gudang umum
            // Satuan           : Nilai
            // Bobot            : 10%
            // Target           : -
            //                    Rata-rata KPI tim gudang umum
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Target minimal KPI tim

            // Untuk KPI ini (KPIWH14) ketika closing stok harus di simpan data akhirnya ke KPI result

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            if ($month==date('m') && $year==date('Y')) {
                //$done    = ;
                //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
                $total_done = 0;

                //$request = ;
                //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
                $total_request = 0;
            } else {
                $kpiData    = getKPIResult($nik, 'KPIWH14', $month, $year);
                $total_done   = $kpiData['b'];
                $total_request  = $kpiData['a'];
            }

            if ($total_request>0) {
                $data['a']      = $total_request;
                $data['b']      = $total_done;
                $data['score']  = round(((($total_done)/$total_request)*100), 2);
            } else {
                $data['a']      = 0;
                $data['b']      = 0;
                $data['score']  = 100;
            }

            return $data;
        }
    }

    if (! function_exists('getKPIWarehousePurchaseRequest')) { //10:KPIWH15
        function getKPIWarehousePurchaseRequest($nik, $target = 0, $args_date = null)
        {
            // No               : 15
            // kode formula     : KPIWH015
            // Point Penilaian  : Kecepatan pengajuan barang kepada pembelian
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : 1 hari dari pengajuan permintaan user
            //                    Jumlah ppengajuan tepat waktu
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah pengajuan permintaan user dalam 1 bulan

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            // $request = GoodsRequestItems::with('goodsRequest')
            //             ->select(DB::raw('sum(quantity) as total'))
            //             ->whereHas('goodsRequest', function ($query) use ($month, $year) {
            //                 $query->where(function ($query) use ($month, $year) {
            //                     $query->whereMonth('transaction_date', $month)
            //                         ->whereYear('transaction_date', $year)
            //                         ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 1 DAY)')
            //                         ->whereIn('status', [1])
            //                         ->where('is_active', 1);
            //                 })
            //                 ->orWhere(function ($query) use ($month, $year) {
            //                     $query->whereMonth('transaction_date', $month)
            //                         ->whereYear('transaction_date', $year)
            //                         ->whereRaw('transaction_date < DATE_ADD(transaction_date, INTERVAL 7 DAY)')
            //                         ->whereIn('status', [1, 2])
            //                         ->where('is_active', 1);
            //                 });
            //             })
            //             ->where('is_active', 1)
            //             ->whereIn('status', [1, 2, 3, 4 ])
            //             ->first();
            // $request_total = (!empty($request->total)) ? $request->total : 0;

            // Total terpenuhi tepat waktu
            // $outs = GoodsRequestItemOut::with('goodsRequestItem')
            //             ->select(DB::raw('sum(quantity) as total'))
            //             ->whereHas('goodsRequestItem', function ($query) use ($month, $year, $date) {
            //                 $query->whereHas('goodsRequest', function ($query) use ($month, $year, $date) {
            //                     $query->select('transaction_date')
            //                         ->whereRaw('ic_goods_request_item_outs.out_date <= DATE_ADD(transaction_date, INTERVAL 7 DAY)')
            //                         ->whereMonth('transaction_date', $month)
            //                         ->whereYear('transaction_date', $year)
            //                         ->whereIn('status', [1, 2, 3 ])
            //                         ->where('is_active', 1);
            //                 })
            //                 ->where('is_active', 1)
            //                 ->whereIn('status', [1, 2, 3, 4 ]);
            //             })
            //             ->where('is_active', 1)
            //             ->whereIn('status', [1, 2, 3, 4 ])
            //             ->first();
            // $outs_total = (!empty($outs->total)) ? $outs->total : 0;


            // Total permintaan barang
            //$done    = Goods;

            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 0;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 0;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

// KPI FORMULA PURCHASING

    if (! function_exists('getKPIPurchasingGoodsQuality')) { //1:KPIPC01
        function getKPIPurchasingGoodsQuality($nik, $target = 0, $args_date = null)
        {
            // No               : 1
            // kode formula     : KPIPC01
            // Point Penilaian  : Kualitas Barang
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : Jumlah barang
            //                    Jumlah barang keseluruhan-jumlah barang cacat
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah barang keseluruhan

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIPurchasingEvaluation')) { //2:KPIPC02
        function getKPIPurchasingEvaluation($nik, $target = 0, $args_date = null)
        {
            // No               : 2
            // kode formula     : KPIPC02
            // Point Penilaian  : Evaluasi pembelian barang
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : Evaluasi barang sering mengalami pembelian
            //
            // Rumus KPI        : Jumlah barang yang sering di beli
            //

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIPurchasingDoOnTime')) { //3:KPIPC03
        function getKPIPurchasingDoOnTime($nik, $target = 0, $args_date = null)
        {
            // No               : 3
            // kode formula     : KPIPC03
            // Point Penilaian  : Ketepatan waktu kedatangan barang
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : -
            //                    Total PO yang di ajukan
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Tanggal kedatangan barang

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIPurchasingEvaluationSupplier')) { //4:KPIPC04
        function getKPIPurchasingEvaluationSupplier($nik, $target = 0, $args_date = null)
        {
            // No               : 4
            // kode formula     : KPIPC04
            // Point Penilaian  : Evaluasi Supplier
            // Unit Pengukur    : Nilai
            // Bobot            : xx%
            // Target           : -
            //                    Rata-rata penilaian supplier
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Target penilaian supplier

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIPurchasingPaymentOnTime')) { //5:KPIPC05
        function getKPIPurchasingPaymentOnTime($nik, $target = 0, $args_date = null)
        {
            // No               : 5
            // kode formula     : KPIPC05
            // Point Penilaian  : Presentase jumlah supplier yang pembayarannya dilakukan secara tepat waktu
            // Unit Pengukur    : Nilai
            // Bobot            : xx%
            // Target           : -
            //                    Jumlah supplier yang pembayarannya dilakukan secara tepat waktu
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Jumlah supplier keseluruhan

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }

    if (! function_exists('getKPIPurchasingTeamAverage')) { //6:KPIPC06
        function getKPIPurchasingTeamAverage($nik, $target = 0, $args_date = null)
        {
            // No               : 6
            // kode formula     : KPIPC06
            // Point Penilaian  : Rata-rata KPI Staff pembelian
            // Unit Pengukur    : Jumlah
            // Bobot            : xx%
            // Target           : -
            //                    Rata-rata KPI tim pembelian
            // Rumus KPI        : ------------------------------------------------------------ x 100%
            //                    Target minimal KPI tim pembelian

            $KpiDate   = getKPIDate($args_date);
            $month     = $KpiDate['month'];
            $year      = $KpiDate['year'];
            $date      = $KpiDate['date'];

            // Total permintaan barang
            //$done    = ;
            //$total_done = ($done->count()>0) ? (int) $done->count() : 0;
            $total_done = 1;

            //$request = ;
            //$total_request = ($request->count()>0) ? (int) $request->count() : 0;
            $total_request = 1;

            if ($total_done<=0) {
                $data['a']      = $total_done;
                $data['b']      = 0;
                $data['score']  = 100;
            } else {
                $data['a']      = $total_done;
                $data['b']      = $total_request;
                $data['score']  = round((($total_done/$total_request)*100), 2);
            }
            return $data;
        }
    }
