<?php

$flag = true;

$file_content = explode("\n", file_get_contents($argv[1]));

unset($file_content[0]);

foreach ($file_content as $key) {
	$data = str_getcsv($key);
	$devicesData[$data[0]][$data[1]] = $data[2];
}

function getChild($parent, $deviceData) {
	return isset($deviceData[$parent]) ? $deviceData[$parent] : array();
}

function getPath($deviceStart, $deviceEnd, $deviceData, $path, $totalTime, $targetTime) {
	$flag = true;
	$device = $deviceStart;
	$target = $deviceEnd;
	$sum = $totalTime;

	while($flag) {
		$childData = getChild($device, $deviceData);
		if(count($childData) == 0) {
			break;
		} else if(count($childData) == 1) {
			foreach ($childData as $key => $value) {
				if($key == $target) {
					$sum = $sum + $value;
					array_push($path, $key);

					if($sum <= $targetTime) {
						return array($path, $sum);
						$flag = false;
					} else {
						return array("", "");
						$flag = false;
					}
				} else {
					$device = $key;
					$sum = $sum + $value;
					array_push($path, $key);
				}
			}
		} else {
			$deviceTime = $sum;
			foreach ($childData as $key => $value) {
				if($key == $target) {
					$sum = $sum + $value;
					array_push($path, $key);

					if($sum <= $targetTime) {
						return array($path, $sum);
						$flag = false;
					} else {
						return array("", "");
						$flag = false;
					}
				} else {
					array_push($path, $key);
					list($final_path, $final_time) = getPath($key, $target, $deviceData, $path, $deviceTime, $targetTime);
					$deviceTime = $deviceTime + $value + $final_time;

					if($deviceTime <= $targetTime && !empty($final_path)) {
						return array($final_path, $deviceTime);
					} else {
						unset($path[array_search($key, $path)]);
						$deviceTime = $sum;
					}
				}
			}
			$flag = false;
		}
	}	
}

while($flag) {

	echo "Input: ";

	$content = fgets(STDIN, 1024);

	if(trim(strtolower($content)) === "quit") {
		break;
	}

	$output = explode(" ", $content);

	//User Input - From Device
	$deviceFrom = strtoupper($output[0]);

	//User Input - To Device
	$deviceTo = strtoupper($output[1]);

	//User Input - Traverse Time
	$traverseTime = $output[2];

	//User Input Count
	$inputCount = count($output);

	//Check User Input is in acceptable format
	if($inputCount == 3) {
		list($devicePath, $totalPathTime) = getPath($deviceFrom, $deviceTo, $devicesData, array($deviceFrom), 0, $traverseTime);

		if(count($devicePath) > 1) {
			printOutput($devicePath, $totalPathTime);
		}
		else {
			list($devicePath, $totalPathTime) = getPath($deviceTo, $deviceFrom, $devicesData, array($deviceTo), 0, $traverseTime);

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

function printOutput($path, $time) {
	print "Output: ".implode("=>", $path)."=>".$time."\n";
}


?>
