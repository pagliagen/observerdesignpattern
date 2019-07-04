<?php
namespace Kaleido;

abstract class KaleidoObserver
{
    /**
     * Update Listner
     *
     * @param KaleidoSubject $subject
     */
    abstract public function update(KaleidoSubject $subject);
}