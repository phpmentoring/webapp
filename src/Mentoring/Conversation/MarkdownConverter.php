<?php

namespace Mentoring\Conversation;

class MarkdownConverter
{
    /**
     * @var \Parsedown
     */
    protected $parsedown;

    /**
     * @var \HTMLPurifier
     */
    protected $purifier;

    public function __construct()
    {
        $this->parsedown = new \Parsedown();
        $this->purifier = \HTMLPurifier::getInstance();
    }

    public function convert($string)
    {
        return $this->purifier->purify($this->parsedown->text($string));
    }
}
