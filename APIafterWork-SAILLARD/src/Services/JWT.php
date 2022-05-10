<?php

class JWT
{
    private $supportedAlgos = [
        "HS256" => "SHA256",
        "HS512" => "SHA512"
    ];

    public function encodebase64URL($message)
    {
        $encode = str_replace(['+','/','='],['-','_',''],base64_encode($message));

        return $encode;
    }

    public function decodebase64URL($message)
    {
        $base64 = str_replace(['-','_'],['+','/'],$message);
        $reste = strlen($message) % 4;
        if ($reste) {
            $reste = 4 - $reste;
            $base64 .= str_repeat('=',$reste);
        }

        $texte = base64_decode($base64);

        return $texte;
    }

    public function generate(array $payload, string $key, int $validity, $algo="HS256") : string {
        // Générer le header
        $header = [
            "typ" => "JWT",
            "alg" => $algo
        ];

        // La validité
        if ($validity <= 0) $validity = 900;

        $payload['iat'] = time();
        $payload['exp'] = time()+$validity;

        $jwt = array();
        $jwt[] = $this->encodebase64URL(json_encode($header));
        $jwt[] = $this->encodebase64URL(json_encode($payload));
        $token = implode(".",$jwt);

        $jwt[] = $this->sign($token,$key,$algo);

        // Retourne le token généré
        return implode(".",$jwt);
    }

    private function sign(string $message, string $key, string $algo) : string {

        $hashFunction = $this->supportedAlgos[$algo];
        $signature = hash_hmac($hashFunction,$message,$key,true);
        return $this->encodebase64URL($signature);
    }

    public function verif(string $token,$key){
        $partiesToken = explode(".", $token);

        list ($headerBase64URL,$payloadBase64URL,$signatureBase64URL) = $partiesToken;

        $payload = $partiesToken[1];

        $payload = $this->decodebase64URL($payload);
        $payload = json_decode($payload,true);



        if (sizeof($partiesToken) != 3){
            return false;
        }


        if ($payload['exp'] < time() ) {
            return false;
        }

        $signature = $this->decodebase64URL($signatureBase64URL);
        $header = json_decode($this->decodebase64URL($headerBase64URL),true);
        $hashFunction = $this->supportedAlgos[$header["alg"]];
        $hashMac = hash_hmac($hashFunction,$headerBase64URL.'.'.$payloadBase64URL,$key,true);

        return $signature === $hashMac;


    }

}