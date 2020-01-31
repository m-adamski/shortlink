<?php

namespace App\Command;

use App\Helper\ShortURLHelper;
use App\Repository\ShortURLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteCommand extends Command {

    protected static $defaultName = "short-url:delete";

    /**
     * @var ShortURLHelper
     */
    protected $shortURLHelper;

    /**
     * @var ShortURLRepository
     */
    protected $shortURLRepository;

    /**
     * DeleteCommand constructor.
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
            ->setDescription("Deletes the short link with provided ID")
            ->addArgument("link-id", InputArgument::OPTIONAL, "Short link ID");
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        // Define arguments
        $argLinkID = $input->getArgument("link-id");

        // Search for ShortURL with provided ID
        if (null !== $argLinkID && null !== $this->shortURLHelper->validateInt($argLinkID)) {
            if (null !== ($shortURL = $this->shortURLRepository->getOne($argLinkID))) {

                // Remove found ShortURL
                if (true === $this->shortURLRepository->removeShortURL($shortURL)) {
                    $io->writeln("<fg=green>The selected short link has been successfully deleted</>");
                } else {
                    $io->writeln("<fg=red>An error occurred while trying to delete the selected short link!</>");
                }
            } else {
                $io->writeln("<fg=red>No short link found with the given ID number!</>");
            }
        } else {
            $io->writeln("<fg=red>The short link ID was not provided or is incorrect!</>");
        }

        return 0;
    }
}
