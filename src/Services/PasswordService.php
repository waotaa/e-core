<?php


namespace Vng\EvaCore\Services;


class PasswordService
{
    public static function generatePassword(): string
    {
        $characterGroups = [
            'abcdefghijklmnopqrstuvwxyz',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789',
            '=+-^$*.[]{}()?"!@#%&/\,><\':;|_~'
        ];
        $characterSet = join('', $characterGroups);

        $password = static::generateFromCharacterSet($characterSet, 12);
        return static::ensurePasswordValidation($password, $characterGroups);
    }

    private static function generateFromCharacterSet($characterSet, $length = 8)
    {
        $pieces = [];
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= static::pickRandomCharacter($characterSet);
        }
        return implode('', $pieces);
    }

    private static function ensurePasswordValidation($password, $characterGroups)
    {
        if (!static::isPasswordValid($password, $characterGroups)) {
            foreach ($characterGroups as $characterGroup) {
                if (!static::passwordContainsFromCharacterSet($password, $characterGroup)) {
                    // There is no character from this group present in the password
                    // Place a character from this group somewhere in the password
                    $password = static::replaceRandomCharacter($password, $characterGroup);
                    // Make sure the password is valid after replacing the character
                    $password = static::ensurePasswordValidation($password, $characterGroups);
                    break;
                }
            }
        }

        return $password;
    }

    private static function isPasswordValid($password, $characterGroups)
    {
        foreach ($characterGroups as $characterGroup) {
            if (!static::passwordContainsFromCharacterSet($password, $characterGroup)) {
                return false;
            }
        }
        return true;
    }

    private static function passwordContainsFromCharacterSet($password, $characterSet)
    {
        return mb_strlen(strpbrk($password, $characterSet), '8bit') !== false;
    }

    private static function pickRandomCharacter($characterSet)
    {
        $max = mb_strlen($characterSet, '8bit') - 1;
        return $characterSet[random_int(0, $max)];
    }

    private static function pickRandomPosition($password)
    {
        $length = mb_strlen($password, '8bit') - 1;
        return random_int(0, $length);
    }

    private static function replaceRandomCharacter($password, $characterSet): string
    {
        $password[static::pickRandomPosition($password)] = static::pickRandomCharacter($characterSet);
        return $password;
    }
}
