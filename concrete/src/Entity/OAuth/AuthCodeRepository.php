<?php

namespace Concrete\Core\Entity\OAuth;

use Concrete\Core\Entity\Express\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository extends EntityRepository implements AuthCodeRepositoryInterface
{

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     * @throws \Exception
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->getEntityManager()->transactional(function(EntityManagerInterface $em) use ($authCodeEntity) {
            $em->persist($authCodeEntity);
        });
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     * @throws \Exception
     */
    public function revokeAuthCode($codeId)
    {
        $this->getEntityManager()->transactional(function(EntityManagerInterface $em) use ($codeId) {
            $code = $em->find($codeId);

            if ($code) {
                $em->detach($code);
            }
        });
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        return (bool) $this->find($codeId);
    }
}
