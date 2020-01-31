<?php

namespace App\Command;

use App\Helper\ShortURLHelper;
use App\Repository\ShortURLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCommand extends Command {

    protected static $defaultName = "short-url:generate";

    /**
     * @var ShortURLHelper
     */
    protected $shortURLHelper;

    /**
     * @var ShortURLRepository
     */
    protected $shortURLRepository;

    /**
     * ShortUrlGenerateCommand constructor.
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
            ->setDescription("Generates a short URL for provided destination URL")
            ->addArgument("destination-url", InputArgument::OPTIONAL, "Destination URL")
            ->addArgument("source-name", InputArgument::OPTIONAL, "Name of the short link")
            ->addOption("valid-since", "s", InputOption::VALUE_REQUIRED, "The date from which the link will redirect to the destination URL")
            ->addOption("valid-until", "u", InputOption::VALUE_REQUIRED, "The expiry date of the shortened link")
            ->addOption("length", "l", InputOption::VALUE_REQUIRED, "Number of characters of generated shortened link [default: 8]");
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        // Define arguments & options
        $argSourceName = $input->getArgument("source-name");
        $argDestinationURL = $input->getArgument("destination-url");
        $optValidSince = $input->getOption("valid-since");
        $optValidUntil = $input->getOption("valid-until");
        $optLength = $input->getOption("length");

        // Validate provided options
        $optValidSince = null !== $optValidSince ? $this->shortURLHelper->validateDate($optValidSince) : null;
        $optValidUntil = null !== $optValidUntil ? $this->shortURLHelper->validateDate($optValidUntil) : null;
        $optLength = null !== $optLength ? $this->shortURLHelper->validateLength($optLength) : 8;

        if (null !== $argDestinationURL && "" !== $argDestinationURL && true === $this->shortURLHelper->validateURL($argDestinationURL)) {

            // Validate provided source name
            if (null === $argSourceName || (null !== $argSourceName && $this->shortURLHelper->validateSourceName($argSourceName))) {

                // Create instance of ShortURL
                $shortURL = $this->shortURLRepository->generateShortURL($argSourceName, $optLength);
                $shortURL->setDestinationURL($argDestinationURL);
                $shortURL->setValidSince($optValidSince);
                $shortURL->setValidUntil($optValidUntil);

                // Save created ShortURL
                if (true === $this->shortURLRepository->saveShortURL($shortURL)) {
                    $io->newLine();
                    $io->writeln("Generated URL: <fg=green>" . $this->shortURLHelper->renderURL($shortURL->getSourceName()) . "</>");
                    $io->writeln("Destination URL: <fg=green>" . $shortURL->getDestinationURL() . "</>");
                } else {
                    $io->writeln("<fg=red>An error occurred while trying to save the short link!</>");
                }
            } else {
                $io->writeln("<fg=red>The name provided for the shortened link is invalid!</>");
            }
        } else {
            $io->writeln("<fg=red>Destination URL was not provided or is incorrect!</>");
        }

        return 0;
    }
}
