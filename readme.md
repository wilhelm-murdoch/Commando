Documentation is on its way. Just be warned that the first few versions of this library will be highly unstable.

###Verbose Example

	try {
		if(Command::isCli() === false) {
			die('must be in PHP cli');
		}

		Command::singleton($configArray)->addArgument(array(
			Command_Argument::factory($argumentTitle, $isRequired)->addValue(
				Command_Argument_Value::factory($isRequired, $validationPattern)
			)
			->attach(ClassWhichObservesArgument::singleton()),
			Command_Argument::factory($argumentTitle, $isRequired)->addValue(array(
				Command_Argument_Value::factory($isRequired, $validationPattern),
				Command_Argument_Value::factory($isRequired, $validationPattern)
			))
			->attach(array(
				ClassWhichObservesArgument::singleton(),
				ClassWhichObservesArgument::singleton(),
				ClassWhichObservesArgument::singleton()
			)),
		))
		->validate($_SERVER['argv'])
		->execute(array(ClassWhichObservesArgument::singleton(), 'execute'));
	} catch(Exception $Exception) {
		echo $Exception->getMessage();
	}