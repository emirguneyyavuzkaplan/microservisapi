<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GateController extends Controller
{
    private $salt = 'case2023';
    private $callback_success_url = 'https://case.altpay.dev/success';
    private $callback_fail_url = 'https://case.altpay.dev/fail';
    private $price = '55.33';

    public function openGate(Request $request)
    {
        $data = $request->all();

        if (!$this->verifyRequestHash($data['hash'], $data)) {
            return response()->json(['message' => 'Hash is invalid'], 403);
        }

        // Ödeme yapma işlemleri burada gerçekleştirilebilir

        // Başarılı ödeme durumunda callback_success_url'ye bildirim gönder
        $this->pingCallbackSuccess($data);

        return response()->json(['message' => 'Success'], 200);
    }

    private function verifyRequestHash($hash, $data)
    {
        $calculatedHash = hash('sha256', $this->salt . $data['callback_fail_url'] . $data['callback_success_url'] . $this->price);
        return $hash === $calculatedHash;
    }

    private function callbackPostData()
    {
        $calculatedHash = hash('sha256', $this->price . $this->callback_success_url . $this->callback_fail_url . $this->salt);
        return ['hash' => $calculatedHash];
    }

    private function pingCallbackSuccess($data)
    {
        $postData = $this->callbackPostData();
        Http::post($data['callback_success_url'], $postData);
    }

    private function pingCallbackFail($data)
    {
        $postData = $this->callbackPostData();
        Http::post($data['callback_fail_url'], $postData);
    }
}
