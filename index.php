<?php

require_once("path.php");

//Read content from CSV file
$fileContent = explode("\n", file_get_contents($argv[1]));

//Remove CSV Header
unset($fileContent[0]);

//Loop through CSV Content and form Network Path Data array
foreach ($fileContent as $key => $value) {
	if(!empty(trim($value))) {
		$data = str_getcsv($value);
		$networkData[$data[0]][$data[1]] = $data[2];
	}
}

function printOutput($path, $time) {
	print "Output: ".implode("=>", $path)."=>".$time."\n";
}

$readUserInput = true;

while($readUserInput) {

	print "Input: ";

	//Read User Input String from Command Line
	$userInputString = trim(fgets(STDIN, 1024));

	//Check if User Input String is blank
	if(empty($userInputString)) {
		print "Output: Invalid Format"."\n";
	} else {

		//User - Quit the application if user input is QUIT or quit
		if(strtolower($userInputString) === "quit") {
			break;
		}

		//Convert User Input String to Array
		$userInput = explode(" ", $userInputString);

		//User Input Parameters Count
		$userInputParamsCount = count($userInput);

		//Check User Input is in acceptable format
		if($userInputParamsCount == 3) {

			//User Input - From Device
			$deviceFrom = strtoupper($userInput[0]);

			//User Input - To Device
			$deviceTo = strtoupper($userInput[1]);

			//User Input - Traverse Time
			$traverseTime = $userInput[2];

			list($devicePath, $totalPathTime) = getPath($deviceFrom, $deviceTo, $networkData, array($deviceFrom), 0, $traverseTime);

			if(count($devicePath) > 1) {
				printOutput($devicePath, $totalPathTime);
			}
			else {
				list($devicePath, $totalPathTime) = getPath($deviceTo, $deviceFrom, $networkData, array($deviceTo), 0, $traverseTime);

				if(empty($devicePath) || count($devicePath) <= 1) {
					echo "Output: Path not found"."\n";
				} else {
					printOutput(array_reverse($devicePath), $totalPathTime);
				}
			}
		} else {
			print "Output: Invalid Format"."\n";
		}
	}
}

?>
