<?php

namespace App\Http\Controllers;

use App\Helpers\FlashData;
use App\Models\RequestDiskon;
use App\Models\RequestDiskonNotification;
use App\Models\Store;
use Illuminate\Http\Request;

class RequestDiskonController extends Controller
{
    public function requestDiskon(Request $request)
    {
        try {

            $roleuser = userRoleName();
            if ($roleuser === 'Super Admin') {
                $diskon = RequestDiskon::create([
                    'id' => generateUuid(),
                    'number' => 'DSK' . date('YmdHis'),
                    'amount_discount' => $request->amount_discount,
                    'status' => 1,
                    'user_id' => auth()->user()->id
                ]);
                RequestDiskonNotification::create([
                    'title' => 'Permintaan Diskon',
                    'discount_id' => $diskon->id,
                    'description' => 'Permintaan diskon pada user ' . auth()->user()->name . "dengan nominal " . $request->amount_discount,
                ]);
            } else {
                $userStore = userStore();
                $stores = Store::where('name', $userStore)->first();
                $diskon = RequestDiskon::create([
                    'id' => generateUuid(),
                    'number' => 'DSK' . date('YmdHis'),
                    'amount_discount' => $request->amount_discount,
                    'status' => 1,
                    'user_id' => auth()->user()->id
                ]);
                RequestDiskonNotification::create([
                    'title' => 'Permintaan Diskon',
                    'discount_id' => $diskon->id,
                    'description' => 'Permintaan diskon pada user ' . auth()->user()->name . "dengan nominal " . $request->amount_discount,
                    'store_id' => $stores->id
                ]);
            }

            FlashData::success_alert('Berhasil mengirim permintaan diskon');
            return redirect()->route('cashier.index');
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }

    public function confirmDiskon($diskonId)
    {
        RequestDiskonNotification::where('discount_id', $diskonId)->update(['readed' => 1]);
        $diskon = RequestDiskon::with('user')->where('id', $diskonId)->first();
        return view('pages.diskon.confirm-diskon', compact('diskon'));
    }

    public function storeDiskon($diskonId, Request $request)
    {
        try {
            if ($request->status === '') {
                FlashData::danger_alert('Silahkan pilih status konfirmasi');
                return redirect()->back();
            }
            RequestDiskon::where('id', $diskonId)->update([
                'status' => $request->status,
                'note' => $request->note ?? '-'
            ]);

            FlashData::success_alert('Berhasil mengkonfirmasi permintaan diskon');

            return redirect()->route('diskon.confirm_diskon', $diskonId);
        } catch (\Throwable $th) {
            FlashData::danger_alert($th->getMessage());
            return redirect()->back();
        }
    }
}
