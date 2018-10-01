<?php

namespace TDK\Core\Api;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Interface InputAwareInterface
 *
 * @package TDK\Core\Api
 */
interface InputAwareInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return static
     */
    public function setInput(InputInterface $input);
}
