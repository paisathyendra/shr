<?php

require_once("path.php");

//Check file path is metioned to read
if(isset($argv[1])) {

	//Read content from CSV file
	$fileContent = explode("\n", @file_get_contents($argv[1]));

} else {
	print "Please mention file path. Eg: php index.php \"network_path.csv\"". "\n";
	exit;
}

//Remove CSV Header
unset($fileContent[0]);

if(count($fileContent) == 0) {
	print "File is empty";
	exit;
}

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

			//Fetch Network Path
			list($networkPath, $totalPathTime) = fetchNetworkPath($deviceFrom, $deviceTo, $networkData, array($deviceFrom), 0, $traverseTime);

			if(count($networkPath) > 1) {
				printOutput($networkPath, $totalPathTime);
			}
			else {
				list($networkPath, $totalPathTime) = fetchNetworkPath($deviceTo, $deviceFrom, $networkData, array($deviceTo), 0, $traverseTime);

				if(empty($networkPath) || count($networkPath) <= 1) {
					echo "Output: Path not found"."\n";
				} else {
					printOutput(array_reverse($networkPath), $totalPathTime);
				}
			}
		} else {
			print "Output: Invalid Format"."\n";
		}
	}
}

?>
