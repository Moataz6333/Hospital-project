<?php

namespace App\Interfaces;

interface PaymentInterface{
    public function pay($appointment);
    public function donate($donation);
} 