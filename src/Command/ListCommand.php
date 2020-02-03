<?php

namespace App\Command;

use App\Entity\ShortURL;
use App\Helper\ShortURLHelper;
use App\Repository\ShortURLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListCommand extends Command {

    protected static $defaultName = "short-url:list";

    /**
     * @var ShortURLHelper
     */
    protected $shortURLHelper;

    /**
     * @var ShortURLRepository
     */
    protected $shortURLRepository;

    /**
     * ListCommand constructor.
     *
     * @param ShortURLHelper     $shortURLHelper
     * @param ShortURLRepository $shortURLRepository
     */
    public function __construct(ShortURLHelper $shortURLHelper, ShortURLRepository $shortURLRepository) {
        parent::__construct();

        $this->shortURLHelper = $shortURLHelper;
        $this->shortURLRepository = $shortURLRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure() {
        $this
            ->setDescription("Displays a list of short links")
            ->addOption("source-name", "s", InputOption::VALUE_REQUIRED, "Searches for shortened links by the given name")
            ->addOption("destination-url", "d", InputOption::VALUE_REQUIRED, "Searches for shortened links by the given destination URL")
            ->addOption("only-active", "a", InputOption::VALUE_NONE, "Only active entries");
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        // Define options
        $optSourceName = $input->getOption("source-name");
        $optDestinationURL = $input->getOption("destination-url");
        $optOnlyActive = $input->getOption("only-active");

        // Search for items
        $itemsCollection = $this->shortURLRepository->search($optSourceName, $optDestinationURL, $optOnlyActive);
        $itemsCollection = array_map(function (ShortURL $shortURL) {
            return [
                $shortURL->getId(),
                $shortURL->getAdditionalID(),
                $this->shortURLHelper->renderURL($shortURL->getSourceName()),
                $shortURL->getDestinationURL(),
                $shortURL->getValidSince() ? $this->shortURLHelper->formatDate($shortURL->getValidSince()) : "-",
                $shortURL->getValidUntil() ? $this->shortURLHelper->formatDate($shortURL->getValidUntil()) : "-",
                $shortURL->isActive() ? "true" : "false",
                $shortURL->getCreatedAt()->format("Y-m-d H:i:s")
            ];
        }, $itemsCollection);

        // Create table
        $currentTable = new Table($output);
        $currentTable->setStyle("box");
        $currentTable->setHeaders(["ID", "Additional ID", "Source Name", "Destination URL", "Valid Since", "Valid Until", "Active", "Creation Date"]);
        $currentTable->setRows($itemsCollection);

        // Display summary table
        $currentTable->render();

        return 0;
    }
}
