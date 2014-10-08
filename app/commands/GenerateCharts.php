<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCharts extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'charts:generate';
	protected $chartsRepo ;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate Server Side Charts.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(KodeInfo\ChartsRepo $chartsRepo)
	{

        $this->chartsRepo = $chartsRepo;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->chartsRepo->generate();
	}

}
