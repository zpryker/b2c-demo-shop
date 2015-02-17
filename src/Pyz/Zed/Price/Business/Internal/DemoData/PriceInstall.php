<?php

namespace Pyz\Zed\Price\Business\Internal\DemoData;

use ProjectA\Zed\Console\Business\Model\Console;
use ProjectA\Zed\Kernel\Locator;
use ProjectA\Zed\Library\Business\DemoDataInstallInterface;
use ProjectA\Zed\Library\Import\Reader\CsvFileReader;
use ProjectA\Zed\Price\Business\PriceFacade;

class PriceInstall implements DemoDataInstallInterface
{
    const SKU = 'sku';
    const PRICE = 'price';
    const VALID_FROM = 'valid_from';
    const PRICE_TYPE = 'price_type';
    const VALID_TO = 'valid_to';

    /** @var PriceFacade */
    protected $priceFacade;

    public function __construct()
    {
        $locator = new Locator();
        $this->priceFacade = $locator->price()->facade();
    }

    /**
     * @param Console $console
     */
    public function install(Console $console)
    {
        $console->info("This will install a dummy set of prices in the demo shop ");
        if ($console->askConfirmation('Do you really want this?')) {
            $demoPrices = $this->getDemoPrices();
            $this->writePrices($demoPrices);
        }
    }

    /**
     * @param array $demoPrices
     */
    protected function writePrices(array $demoPrices)
    {
        foreach ($demoPrices as $row) {
            $this->addEntry($row);
        }
    }

    /**
     * @return array
     */
    protected function getDemoPrices()
    {
        $reader = new CsvFileReader();

        return $reader->read(__DIR__ . '/demo-price.csv')->getData();
    }

    /**
     * @param array $row
     */
    protected function addEntry(array $row)
    {
        $stockType = $this->priceFacade->createPriceType($row[self::PRICE_TYPE]);

        $validFrom = new \DateTime($row[self::VALID_FROM]);
        $validTo = new \DateTime($row[self::VALID_TO]);
        $this->priceFacade->setPrice($row[self::SKU], $row[self::PRICE], $stockType->getName(), $validFrom, $validTo);
    }

}
