<?php

interface Subject {
    public function add(Observer $observer): void;
    public function remove(Observer $observer): void;
    public function notify(): void;
}

