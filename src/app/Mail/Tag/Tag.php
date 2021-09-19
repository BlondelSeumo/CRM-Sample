<?php


namespace App\Mail\Tag;


abstract class Tag
{
    protected $notifier;

    protected $receiver;

    protected $resourceurl;

    abstract function notification();
}
