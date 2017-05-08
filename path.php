<?php

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

?>