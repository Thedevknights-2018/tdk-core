<?php

namespace TDK\Core\Console;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class InputAwareTrait
 *
 * @package TDK\Core\Console
 */
trait InputAwareTrait
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return $this
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }
}
