<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class DigiflazzService
{
    protected $username = 'zelifog5Rwpo';
    protected $apiKey = 'aba1c630-fb42-5892-926b-98dee3705174';
    protected $baseUrl = 'https://api.digiflazz.com/v1';

    public function inquiryPln($customerNo)
    {
        // Check cache first
        $customer = Customer::where('customer_no', $customerNo)->first();
        if ($customer) {
            return [
                'status' => 'success',
                'name' => $customer->name,
                'segment_power' => $customer->segment_power,
                'customer_no' => $customer->customer_no,
                'meter_no' => $customer->meter_no,
                'subscriber_id' => $customer->subscriber_id,
            ];
        }

        // Call API
        $sign = md5($this->username . $this->apiKey . $customerNo);

        try {
            $response = Http::post($this->baseUrl . '/inquiry-pln', [
                'username' => $this->username,
                'customer_no' => $customerNo,
                'sign' => $sign,
            ]);

            $data = $response->json();

            if (isset($data['data']['status']) && $data['data']['status'] == 'Sukses') {
                $result = $data['data'];

                // Cache to DB
                Customer::updateOrCreate(
                    ['customer_no' => $customerNo],
                    [
                        'meter_no' => $result['meter_no'] ?? null,
                        'name' => trim($result['name']),
                        'segment_power' => trim($result['segment_power']),
                        'subscriber_id' => $result['subscriber_id'] ?? null,
                    ]
                );

                return [
                    'status' => 'success',
                    'name' => trim($result['name']),
                    'segment_power' => trim($result['segment_power']),
                    'customer_no' => $result['customer_no'],
                    'meter_no' => $result['meter_no'] ?? null,
                    'subscriber_id' => $result['subscriber_id'],
                ];
            }

            return [
                'status' => 'failed',
                'message' => $data['data']['message'] ?? 'Customer not found or API error.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
