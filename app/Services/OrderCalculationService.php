<?php

namespace App\Services;

class OrderCalculationService
{
    /**
     * Calculate order totals.
     *
     * @param array $items Array of cart items with 'price' and 'quantity'
     * @param array $options Calculation options
     * @return array
     */
    public function calculate(array $items, array $options = []): array
    {
        $discountType = $options['discount_type'] ?? null;
        $discountValue = (float) ($options['discount_value'] ?? 0);
        $taxPercent = (float) ($options['tax_percent'] ?? 0);
        $servicePercent = (float) ($options['service_percent'] ?? 0);

        // Calculate subtotal
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item['price'] * (int) $item['quantity'];
        }

        // Calculate discount
        $discountAmount = 0;
        if ($discountType === 'percent' && $discountValue > 0) {
            $discountAmount = $subtotal * ($discountValue / 100);
        } elseif ($discountType === 'nominal' && $discountValue > 0) {
            $discountAmount = min($discountValue, $subtotal);
        }

        $afterDiscount = $subtotal - $discountAmount;

        // Calculate tax
        $taxAmount = 0;
        if ($taxPercent > 0) {
            $taxAmount = $afterDiscount * ($taxPercent / 100);
        }

        // Calculate service charge
        $serviceAmount = 0;
        if ($servicePercent > 0) {
            $serviceAmount = $afterDiscount * ($servicePercent / 100);
        }

        // Calculate grand total
        $grandTotal = $afterDiscount + $taxAmount + $serviceAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_type' => $discountType,
            'discount_value' => round($discountValue, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_percent' => round($taxPercent, 2),
            'tax_amount' => round($taxAmount, 2),
            'service_percent' => round($servicePercent, 2),
            'service_amount' => round($serviceAmount, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }

    /**
     * Calculate change for cash payment.
     *
     * @param float $grandTotal
     * @param float $amountPaid
     * @return float
     */
    public function calculateChange(float $grandTotal, float $amountPaid): float
    {
        return max(0, $amountPaid - $grandTotal);
    }
}
