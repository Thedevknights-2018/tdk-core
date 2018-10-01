<?php

namespace TDK\Core\Api;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface OutputAwareInterface
 *
 * @package Courts\Core\Api
 */
interface OutputAwareInterface
{
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return static
     */
    public function setOutput(OutputInterface $output);
}
