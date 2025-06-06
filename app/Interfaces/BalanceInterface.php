<?php

namespace App\Interfaces;

interface BalanceInterface {
    public function getBalance();
    public function getTransactions();
    public function getCash();
    public function getSalaries();
    public function getdonations();
    public function weekData($week);
    public function weeks();
    public function months();
    public function increase($amount);
}