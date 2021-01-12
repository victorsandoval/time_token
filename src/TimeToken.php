<?php
namespace TimeToken;

class TimeToken
{
    /**
     * @param $interval
     * @param $secretKey
     * @param $secretIv
     * @param string $encryptionMethod
     * @param string $algorithm
     * @return string
     */
    public function generateTimeToken(
        $interval,
        $secretKey,
        $secretIv,
        $encryptionMethod = "AES-256-CBC",
        $algorithm="sha256"
    )
    {
        $timestamp = new \DateTime();

        $timestamp->add(new \DateInterval("PT0H{$interval}S"));
        $data = $timestamp->format("U");

        $key = hash($algorithm, $secretKey);
        $iv = substr(hash($algorithm, $secretIv), 0, 16);

        $output = openssl_encrypt($data, $encryptionMethod, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    /**
     * @param $secretKey
     * @param string $encryptionMethod
     * @param string $algorithm
     * @return bool
     */
    public function tokenIsValid(
        $data,
        $secretKey,
        $secretIv,
        $encryptionMethod = "AES-256-CBC",
        $algorithm="sha256"
    )
    {
        $key = hash($algorithm, $secretKey);
        $iv = substr(hash($algorithm, $secretIv), 0, 16);
        $output = openssl_decrypt(base64_decode($data), $encryptionMethod, $key, 0, $iv);
        $timestamp = new \DateTime();
        $timestamp->setTimestamp($output);
        $now = new \DateTime();

        if ($timestamp >= $now) {
            return true;
        }

        return false;
    }
}
