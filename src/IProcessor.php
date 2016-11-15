<?php

namespace Acme;

interface IProcessor {
//    public function log($message);
    public function process($file, $fileType);
}
