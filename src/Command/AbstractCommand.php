<?php

namespace TDK\Core\Command;

use Carbon\Carbon;
use TDK\Core\Api\InputAwareInterface;
use TDK\Core\Api\OutputAwareInterface;
use TDK\Core\Console\InputAwareTrait;
use TDK\Core\Console\OutputAwareTrait;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command implements OutputAwareInterface, InputAwareInterface
{
    use OutputAwareTrait;
    use InputAwareTrait;

    const BATCH_SIZE = 1000;
    const MAX_RETRY = 5;
    const SLEEP = 60;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;
    /**
     * @var \Carbon\Carbon
     */
    protected $startTime;
    /**
     * @var \Carbon\Carbon
     */
    protected $endTime;
    /**
     * Object manager factory
     *
     * @var \Magento\Framework\App\ObjectManagerFactory
     */
    private $objectManagerFactory;

    public function __construct(\Magento\Framework\App\ObjectManagerFactory $objectManagerFactory)
    {
        parent::__construct();

        $this->objectManagerFactory = $objectManagerFactory;

        // $omParams = $_SERVER;
        // $omParams[StoreManager::PARAM_RUN_CODE] = 'admin';
        // $omParams[Store::CUSTOM_ENTRY_POINT_PARAM] = true;
        $this->objectManager = ObjectManager::getInstance();

        // $area = FrontNameResolver::AREA_CODE;

        /** @var \Magento\Framework\App\State $appState */
        // $appState = $this->objectManager->get(State::class);
        // $appState->setAreaCode($area);
        // $configLoader = $this->objectManager->get(ConfigLoaderInterface::class);
        // $this->objectManager->configure($configLoader->load($area));
        $this->timezone = $this->objectManager->get(\Magento\Framework\Stdlib\DateTime\TimezoneInterface\Proxy::class);

        $this->_construct();
    }

    /**
     * @return void
     */
    protected function _construct()
    {
    }

    /**
     * @return void
     */
    protected function beforeExecute()
    {
    }

    /**
     * @return void
     */
    abstract protected function _execute();

    /**
     * @return void
     */
    protected function afterExecute()
    {
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Magento\Framework\App\State $appState */
        $appState = $this->objectManager->get(State::class);
        $appState->setAreaCode(FrontNameResolver::AREA_CODE);

        $this->setInput($input);
        $this->setOutput($output);

        $this->beforeExecute();

        $this->startTime = Carbon::now()->timezone($this->timezone->getConfigTimezone());
        $output->writeln(sprintf(
            '<info>%s started  - %s</info>',
            $this->getName(),
            $this->startTime->toAtomString()
        ));

        $this->_execute();

        $this->endTime = Carbon::now()->timezone($this->timezone->getConfigTimezone());
        $this->printMemoryUsage(sprintf(
            '<info>%s finished - %s.</info> <comment>Elapsed time: %s.</comment>',
            $this->getName(),
            $this->endTime->toAtomString(),
            $this->endTime->diffForHumans($this->startTime, true, true)
        ));
        $output->writeln('');

        $this->afterExecute();

        return 0;
    }

    /**
     * @param string|null $message
     */
    protected function printMemoryUsage($message = null)
    {
        if (isset($message)) {
            $this->writeln(sprintf(
                '<comment>%s Memory usage: %s</comment>',
                $message,
                $this->formatBytes(memory_get_peak_usage(true))
            ));
        } else {
            $this->writeln(sprintf(
                '<comment>Memory usage: %s</comment>',
                $this->formatBytes(memory_get_peak_usage(true))
            ));
        }
    }

    /**
     * @param int $bytes
     * @param int $precision
     *
     * @return string
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        return @round($bytes / pow(1024, ($i = (int)floor(log($bytes, 1024)))), $precision) . ' ' . $unit[$i];
    }

    /**
     * @param $data
     *
     * @return null|string
     */
    protected function arrayToAttributeString($data)
    {
        $attributes = [];
        foreach ($data as $attribute => $value) {
            $attributes[] = "$attribute=$value";
        }

        return implode(',', $attributes);
    }
}
