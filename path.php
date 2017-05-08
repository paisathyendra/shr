<?php

function getChild($parent, $deviceData) {
	return isset($deviceData[$parent]) ? $deviceData[$parent] : array();
}

function fetchNetworkPath($deviceStart, $deviceEnd, $deviceData, $path, $totalTime, $targetTime) {
	$findPath = true;
	$device = $deviceStart;
	$target = $deviceEnd;
	$pathTime = $totalTime;

	while($findPath) {
		$childData = getChild($device, $deviceData);
		if(count($childData) == 0) {
			break;
		} else if(count($childData) == 1) {
			foreach ($childData as $key => $value) {
				if($key == $target) {
					$pathTime = $pathTime + $value;
					array_push($path, $key);

					if($pathTime <= $targetTime) {
						return array($path, $pathTime);
						$findPath = false;
					} else {
						return array("", "");
						$findPath = false;
					}
				} else {
					$device = $key;
					$pathTime = $pathTime + $value;
					array_push($path, $key);
				}
			}
		} else {
			$deviceTime = $pathTime;
			foreach ($childData as $key => $value) {
				if($key == $target) {
					$pathTime = $pathTime + $value;
					array_push($path, $key);

					if($pathTime <= $targetTime) {
						return array($path, $pathTime);
						$findPath = false;
					} else {
						return array("", "");
						$findPath = false;
					}
				} else {
					array_push($path, $key);
					list($final_path, $final_time) = fetchNetworkPath($key, $target, $deviceData, $path, $deviceTime, $targetTime);
					$deviceTime = $deviceTime + $value + $final_time;

					if($deviceTime <= $targetTime && !empty($final_path)) {
						return array($final_path, $deviceTime);
					} else {
						unset($path[array_search($key, $path)]);
						$deviceTime = $pathTime;
					}
				}
			}
			$findPath = false;
		}
	}	
}

?>