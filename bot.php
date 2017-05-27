<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
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
			error_log($event);
			if ($event['type'] == 'message' && $event['message']['text'] == "Internal") {
				if ($result = $conn->query("SELECT userId FROM LineIDInternal WHERE userId = '" . $userId . "'")) {
					if ($result->num_rows == 0) {
						if ($conn->query("INSERT INTO LineIDInternal (userId) VALUES ('" . $userId . "')") === TRUE) {
							$text = 'ขอบคุณครับ เราได้แอดท่านใน List internal แล้ว เพื่อรับแจ้งเตือนระบบ Truck Tracking';
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
						}

					}
				}
			}
			else {
				if ($result = $conn->query("SELECT userId FROM LineID WHERE userId = '" . $userId . "'")) {
    //printf("Select returned %d rows.\n", $result->num_rows);
					if ($result->num_rows == 0) {
						if ($conn->query("INSERT INTO LineID (userId) VALUES ('" . $userId . "')") === TRUE) {
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
						}

					}
				}
			}
    /* free result set */
    		$result->close();
		}
			// Get text sent
			//$text = $event['message']['text'];
			// // Get replyToken
			// $replyToken = $event['replyToken'];

			// build message to reply back


				// echo $result . "\r\n";
			// }
	}
		else if ($event['type'] == 'unfollow' && $event['source']['type'] == 'user') {
			$userId = $event['source']['userId'];
			$conn->query("DELETE FROM LineID WHERE userId = '" . $userId . "'");
		}
	}
}
echo "OK";
