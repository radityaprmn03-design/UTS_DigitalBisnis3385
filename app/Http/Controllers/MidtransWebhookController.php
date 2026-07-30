<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return $this->jsonResponse(['message' => 'Invalid payload'], 400);
        }

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            if (str_starts_with($orderId, 'payment_notif_test')) {
                return $this->jsonResponse(['message' => 'Test notification received successfully'], 200);
            }
            return $this->jsonResponse(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->status === 'settlement' || $transaction->status === 'success') {
            return $this->jsonResponse(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API & Reserved Ticket Release
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'success';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            // Pelepasan Stok Tiket (Reserved Ticket (+1)) jika pembayaran expired/batal
            if ($transaction->status === 'pending' || $transaction->status === 'Pending') {
                if ($transaction->event) {
                    $transaction->event->increment('stock');
                }
            }
            $transaction->status = 'expired';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();
        return $this->jsonResponse(['message' => 'OK']);
    }

    private function jsonResponse($data, $status = 200)
    {
        $response = response()->json($data, $status);
        $response->header('Content-Length', strlen($response->getContent()));
        return $response;
    }

    private function processSuccess(Transaction $transaction)
    {
        // Stok sudah ditahan saat checkout. Kirim E-Ticket ke email pelanggan.
        try {
            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)->send(new \App\Mail\EventTicketMail($transaction));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
        }
    }
}