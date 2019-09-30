$client = new \GuzzleHttp\Client();
$clientId = 'your-client-id';
$secret = 'your-secret';
$signingKey = 'your-signing-key';

$res = $client->request('POST', 'api.v2.staging.odeo.co.id/oauth2/token', [
  'json' => [
    'client_id' => $clientId,
    'client_secret' => $secret,
    'grant_type' => 'client_credentials'
  ]
]);

$accessToken = json_decode($res->getBody()->getContents())->access_token;
$timestamp = time();
$bodyHash = base64_encode(hash('sha256', '', true));
$path = '/dg/v1/banks';
$method = 'GET';
$stringToSign = "$method:$path::$accessToken:$timestamp:$bodyHash";
$signature = base64_encode(hash_hmac('sha256', $stringToSign, $signingKey, true));
$headers = [
  'X-Odeo-Timestamp' => $timestamp,
  'X-Odeo-Signature' => $signature,
  'Authorization' => "Bearer $accessToken"
];

$res = $client->request($method, "api.v2.staging.odeo.co.id/$path", [
  'headers' => $headers
]);

return [
  'stringToString' => $stringToSign,
  'headers' => $headers,
  'result' => $res->getBody()->getContents()
];