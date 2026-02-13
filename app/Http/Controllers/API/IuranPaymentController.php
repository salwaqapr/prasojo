<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\IuranMember;
use App\Models\IuranPayment;
use App\Services\IuranKasSyncService;
use Illuminate\Http\Request;

class IuranPaymentController extends Controller
{
  public function listByMember($memberId)
  {
    $member = IuranMember::findOrFail($memberId);

    $payments = IuranPayment::where('member_id', $member->id)
      ->orderBy('tahun', 'desc')
      ->orderBy('bulan', 'desc')
      ->get();

    return response()->json($payments);
  }

  public function store(Request $request, $memberId)
  {
    $member = IuranMember::findOrFail($memberId);

    $data = $request->validate([
      'tanggal' => 'required|date',
      'bulan' => 'required|integer|min:1|max:12',
      'tahun' => 'required|integer|min:2000|max:2100',
      'nominal' => 'required|integer|min:0',
    ]);

    $payment = IuranPayment::updateOrCreate(
      [
        'member_id' => $member->id,
        'bulan' => $data['bulan'],
        'tahun' => $data['tahun']
      ],
      [
        'tanggal' => $data['tanggal'],
        'nominal' => $data['nominal']
      ]
    );

    IuranKasSyncService::syncMonthlyKas((int)$data['bulan'], (int)$data['tahun']);

    return response()->json($payment, 201);
  }

  public function update(Request $request, $paymentId)
  {
    $payment = IuranPayment::findOrFail($paymentId);

    $data = $request->validate([
      'tanggal' => 'required|date',
      'nominal' => 'required|integer|min:0',
    ]);

    $payment->update($data);

    IuranKasSyncService::syncMonthlyKas((int)$payment->bulan, (int)$payment->tahun);

    return response()->json($payment);
  }

  public function destroy($paymentId)
  {
    $payment = IuranPayment::findOrFail($paymentId);
    $bulan = (int)$payment->bulan;
    $tahun = (int)$payment->tahun;

    $payment->delete();

    IuranKasSyncService::syncMonthlyKas($bulan, $tahun);

    return response()->json(['message' => 'deleted']);
  }

  public function summaryByMember($memberId)
  {
    $member = IuranMember::findOrFail($memberId);

    $rows = IuranPayment::where('member_id', $member->id)
      ->selectRaw('tahun, bulan, SUM(nominal) as total')
      ->groupBy('tahun', 'bulan')
      ->orderBy('tahun', 'desc')
      ->orderBy('bulan', 'desc')
      ->get();

    return response()->json($rows);
  }
}
