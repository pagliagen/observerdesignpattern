<?php
use Kaleido\KaleidoSubject;
use Kaleido\KaleidoObserver;

/**
 * Subject,that who makes news
 */ 
class Newspaper extends KaleidoSubject
{ 
    private $name;
    private $content;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    //set breakouts news
    public function breakOutNews($content) {
        $this->content = $content;
        $this->notify();
    }
    
    public function getContent() {
        return $this->content." ({$this->name})";
    }
}
  
class Reader extends KaleidoObserver
{
    private $name;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    /** 
     * Update Listner
     * 
     * {@inheritDoc}
     * @see KaleidoObserver::update()
     */
    public function update(KaleidoSubject $subject) {
        echo $this->name.' is reading breakout news <b>'.$subject->getContent().'</b><br>';
    }
}


$newspaper = new Newspaper('Newyork Times');

$allen = new Reader('Allen');
$jim = new Reader('Jim');
$linda = new Reader('Linda');

//add reader
$newspaper->attach($allen, 3);
$newspaper->attach($jim, 1);
$newspaper->attach($linda, 2);

//remove reader
$newspaper->detach($linda);

//set break outs
$newspaper->breakOutNews('USA break down!');

//=====output======
//Allen is reading breakout news USA break down! (Newyork Times)
//Jim is reading breakout news USA break down! (Newyork Times)