<?php
$access_token = 'U5BKa4Ux7/5j8a6uoQBELYHT5DAgXJb9IJ5jyUc3sWSm62AwsgzDZ5oLSbOVW6XWEmZG0D1Vn/IM24s64N/4hKXZ5pKsL/Ff5e/8YZ1FovYynqrEC/qzGoSKUSGHs0ir1AvwGDqiUO40iyvZRuvNsgdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');

$file_path = 'userId.txt';
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Write new only if not unfollow type
		if ($event['type'] != 'unfollow' && $event['source']['type'] == 'user') {
			$userId = $event['source']['userId'];
			$isToWrite = true;
			$file = fopen($file_path, 'r');
			if ($file) {
				while (($line = fgets($file)) !== false) {
					// process the line read.
					if (strpos( $line, $userId ) !== false) {
						$isToWrite = false;
						break;
					}
						
				}
				
				fclose($file);
			} else {
				// error opening the file.
			} 
			if ($isToWrite) {
				$file = fopen($file_path, 'a');
				fwrite($file, $userId . "\n");
				fclose($file);
			}
			// Get text sent
			//$text = $event['message']['text'];
			// // Get replyToken
			// $replyToken = $event['replyToken'];

			// build message to reply back
			$text = 'ขอบคุณครับ เราได้แอดท่านแล้ว เพื่อรับแจ้งเตือนระบบ Truck Tracking';
			$messages = [
			'type' => 'text',
			'text' => $text
			];

			// if ($event['message']['type'] == 'text') {
				// // Make a POST Request to Messaging API to reply to sender
				$url = 'https://api.line.me/v2/bot/message/push';
				$data = [
				'to' => $userId,
				'messages' => [$messages],
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);

				// echo $result . "\r\n";
			// }
			
			
		}
		else if ($event['type'] == 'unfollow' && $event['source']['type'] == 'user') {
			$userId = $event['source']['userId'];
			$file_contents = file_get_contents($file_path);
			$file_contents = str_replace($userId . "\n","",$file_contents);
			file_put_contents($file_path,$file_contents);
		}
	}
}
echo "OK";