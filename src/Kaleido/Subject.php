<?php
namespace Kaleido;

abstract class KaleidoSubject
{
    const FIRST_PRIORITY = 100;
    
    /**
     * Observers list
     *
     * @var array
     */
    private $observers = array();
    
    /**
     * The flag used to determine if an update is in progress.
     *
     * @var boolean
     */
    private $updating = false;
    
    /**
     * Attach an observer to the set of observers for this object.
     *
     * @param KaleidoObserver $observer
     * @param int $priority
     */
    public function attach(KaleidoObserver $observer, int $priority = self::FIRST_PRIORITY) {
        $objectId = spl_object_hash($observer);
        if (!isset($this->observers[$priority])) {
            $this->observers[$priority] = [];
        }
        $this->observers[$priority][$objectId] = $observer;
    }
    
    /**
     * Detach an observer from the set of observers for this object.
     *
     * @param KaleidoObserver $observer
     * @param int $priority
     */
    public function detach(KaleidoObserver $observer, int $priority = null)
    {
        if (null === $priority) {
            foreach ($this->observers as $priority => $observers) {
                if (false !== ($key = array_search($observer, $observers, true))) {
                    unset($this->observers[$priority][$key]);
                    break;
                }
            }
        } elseif (isset($this->observers[$priority])) {
            if (false !== ($key = array_search($observer, $this->observers[$priority], true))) {
                unset($this->observers[$priority][$key]);
            }
        }
    }
    
    /**
     * Notify all of this object's observers.
     */
    public function notify()
    {
        if ($this->updating) {
            throw new \Exception('Subject alreadyUpdating');
        }
        
        $this->updating = true;
        
        /** @var ObserverInterface $observer */
        ksort($this->observers);
        foreach ($this->observers as $observers) {
            foreach ($observers as $observer) {
                try {
                    $observer->update($this);
                }
                catch (\Exception $exception) {
                    $this->updating = false;
                    throw $exception;
                }
            }
        }
        $this->updating = false;
    }
    
    /**
     * Detaches all observers.
     */
    public function detachAll(): void
    {
        $this->observers = [];
    }
    
    /**
     * {@inheritDoc}
     */
    public function isUpdating()
    {
        return $this->updating;
    }
    
}