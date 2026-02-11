<?php

namespace App\Listeners;

use App\Services\PaymentService;
use SePay\SePay\Events\SePayWebhookEvent;

class SePayWebhookListener
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle the event.
     */
    public function handle(SePayWebhookEvent $event): void
    {
        // Log để debug
        \Log::info('SePay Webhook Received', [
            'transferType' => $event->sePayWebhookData->transferType,
            'info' => $event->info,
            'data' => (array) $event->sePayWebhookData
        ]);

        // Chỉ xử lý tiền vào
        if ($event->sePayWebhookData->transferType !== 'in') {
            return;
        }

        // $event->info đã loại bỏ prefix "SE" rồi
        // Ví dụ: content = "SEC8B4BB" → info = "C8B4BB"
        // Nên ta phải thêm lại prefix "SE"
        $orderCode = config('sepay.pattern', 'SE') . $event->info;

        \Log::info('Extracted Order Code', [
            'raw_info' => $event->info,
            'order_code' => $orderCode,
            'content' => $event->sePayWebhookData->content
        ]);

        // Chuyển webhook data sang array
        $webhookData = [
            'transaction_id' => $event->sePayWebhookData->id ?? null,
            'amount' => $event->sePayWebhookData->transferAmount ?? 0, // ← SỬA ĐÂY
            'content' => $event->sePayWebhookData->content ?? '',
            'description' => $event->sePayWebhookData->description ?? '',
            'gateway' => $event->sePayWebhookData->gateway ?? '',
            'when' => $event->sePayWebhookData->transactionDate ?? now(),
            'reference_code' => $event->sePayWebhookData->referenceCode ?? null,
            'raw_data' => (array) $event->sePayWebhookData,
        ];

        \Log::info('Processing Payment', [
            'order_code' => $orderCode,
            'amount' => $webhookData['amount'],
            'transaction_id' => $webhookData['transaction_id']
        ]);

        // Xử lý thanh toán
        try {
            $result = $this->paymentService->processPayment($orderCode, $webhookData);

            if ($result) {
                \Log::info('✅ Payment processed successfully', [
                    'order_code' => $orderCode,
                    'amount' => $webhookData['amount']
                ]);
            } else {
                \Log::warning('⚠️ Payment processing failed', [
                    'order_code' => $orderCode
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('❌ Payment processing error', [
                'order_code' => $orderCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
