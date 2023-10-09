<?php

namespace app\Lib;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use DateTimeImmutable;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Validator;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeZone;

class JWT
{
    public function issue(string $username): string
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::plainText(getenv('JWT_SECRET'));

        $now   = new DateTimeImmutable('', new DateTimeZone('UTC'));
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
            // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
            // Configures the id (jti claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now)
            // Configures the time that the token can be used (nbf claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('username', $username)
            // Builds a new token
            ->getToken($algorithm, $signingKey);

        return $token->toString();
    }

    public function parse(string $jwt): array
    {
        $parser = new Parser(new JoseEncoder());
        $signingKey = InMemory::plainText(getenv('JWT_SECRET'));

        $token = $parser->parse($jwt);
        $clock = (new SystemClock(new DateTimeZone('UTC')));

        $validator = new Validator();
        $result = [];
        $result['type'] = 'success';

        if (!$validator->validate($token, new SignedWith(new Sha256(), $signingKey ))) {
            $result['message'] = 'invalid key';
            $result['type'] = 'fail';
        }

        if (!$validator->validate($token, new StrictValidAt($clock))) {
            $result['message'] = 'token expired';
            $result['type'] = 'fail';
        }

        return $result;
    }
}