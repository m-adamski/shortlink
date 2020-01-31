<?php

namespace App\Command;

use App\Helper\ShortURLHelper;
use App\Repository\ShortURLRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangeStatusCommand extends Command {

    protected static $defaultName = "short-url:change-status";

    /**
     * @var ShortURLHelper
     */
    protected $shortURLHelper;

    /**
     * @var ShortURLRepository
     */
    protected $shortURLRepository;

    /**
     * ChangeStatusCommand constructor.
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
            ->setDescription("Changes the status of the selected shortened link")
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
                $shortURL->toggleActive();

                // Save changes
                if (true === $this->shortURLRepository->saveShortURL($shortURL)) {
                    $io->writeln("<fg=green>The status of the selected short link has been successfully changed</>");
                } else {
                    $io->writeln("<fg=red>An error occurred while trying to change the status of the selected short link!</>");
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
