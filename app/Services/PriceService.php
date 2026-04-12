<?php

namespace App\Services;

class PriceService
{
    public static function calculate($basePrice, $quantity, $color, $quality)
    {
        // 💸 DISCOUNT
        $discountRate = 0;

        if ($quantity >= 500) {
            $discountRate = 0.20;
        } elseif ($quantity >= 100) {
            $discountRate = 0.10;
        }

        $discountedPrice = $basePrice - ($basePrice * $discountRate);

        // 🎨 COLOR
        $colorFee = $color === 'Full Color' ? 10 : 0;

        // 📄 QUALITY
        $qualityFees = [
            'Matte' => 0,
            'Glossy' => -5,
            'Premium' => 20,
        ];

        $qualityFee = $qualityFees[$quality] ?? 0;

        // 💰 FINAL
        $finalPerUnit = $discountedPrice + $colorFee + $qualityFee;
        $subtotal = $finalPerUnit * $quantity;

        return [
            'base_price' => $basePrice,
            'discount_rate' => $discountRate,
            'discounted_price' => $discountedPrice,
            'color_fee' => $colorFee,
            'quality_fee' => $qualityFee,
            'final_per_unit' => $finalPerUnit,
            'subtotal' => $subtotal,
        ];
    }
}