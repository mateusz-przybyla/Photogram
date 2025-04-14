<?php

namespace App\Security;

use DateTime;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
  /**
   * @param User $user
   */
  public function checkPreAuth(UserInterface $user): void
  {
    if (null === $user->getBannedUntil()) {
      return;
    }

    $now = new DateTime();

    if ($now < $user->getBannedUntil()) {
      throw new CustomUserMessageAccountStatusException('Your account is banned until ' . $user->getBannedUntil()->format('Y-m-d H:i:s'));
    }
  }

  /**
   * @param User $user
   */
  public function checkPostAuth(UserInterface $user): void {}
}
