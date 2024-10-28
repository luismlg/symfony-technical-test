<?php

namespace App\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use App\Application\Service\AvailabilityService;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(name: 'lleego:avail')]
class AvailabilityCommand extends Command {

    private $availabilityService;

    public function __construct(AvailabilityService $availabilityService) {
        $this->availabilityService = $availabilityService;
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setDescription('Get flight availability')
            ->addArgument('origin', InputArgument::REQUIRED, 'Origin airport code')
            ->addArgument('destination', InputArgument::REQUIRED, 'Destination airport code')
            ->addArgument('date', InputArgument::REQUIRED, 'Flight date');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $origin = $input->getArgument('origin');
        $destination = $input->getArgument('destination');
        $date = $input->getArgument('date');

        $flights = $this->availabilityService->getAvailability($origin, $destination, $date);



        $table = new Table($output);
        $table
            ->setHeaders(['Origin Code', 'Origin Name', 'Destination Code', 'Destination Name', 'Start', 'End', 'Transport Number', 'Company Code', 'Company Name']);

        foreach ($flights as $flight) {
            $table->addRow($flight->toArray());
        }
        $table->render();

        return Command::SUCCESS;
    }
}
