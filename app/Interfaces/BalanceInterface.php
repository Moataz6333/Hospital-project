<?php

namespace App\Interfaces;

interface BalanceInterface {
    public function getBalance();
    public function getTransactions();
    public function getCash();
    public function getSalaries();
    public function increase($amount);
}