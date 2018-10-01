<?php

namespace TDK\Core\Console;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputAwareTrait
 *
 * @package TDK\Core\Console
 */
trait OutputAwareTrait
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return static
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param bool         $newline  Whether to add a newline
     * @param int          $type     The type of output (one of the OUTPUT constants)
     *
     * @throws \InvalidArgumentException When unknown output type is given
     *
     * @api
     */
    public function write($messages, $newline = false, $type = OutputInterface::OUTPUT_NORMAL)
    {
        if ($this->output) {
            $this->output->write($messages, $newline, $type);
        }
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string|array $messages The message as an array of lines of a single string
     * @param int          $type     The type of output (one of the OUTPUT constants)
     *
     * @throws \InvalidArgumentException When unknown output type is given
     *
     * @api
     */
    public function writeln($messages, $type = OutputInterface::OUTPUT_NORMAL)
    {
        if ($this->output) {
            $this->output->writeln($messages, $type);
        }
    }
}
