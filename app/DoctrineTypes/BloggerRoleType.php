<?php

namespace App\DoctrineTypes;

use App\Enums\BloggerRole;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use ValueError;

class BloggerRoleType extends Type
{
    public const NAME = 'blogger_role';

    /**
     * @param  array<string, mixed>  $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'blogger_role';
    }

    /**
     * @param  mixed  $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BloggerRole
    {
        if ($value === null) {
            return null;
        }

        if (! is_string($value) && ! is_int($value)) {
            throw new ConversionException('Invalid value type for BloggerRole: '.var_export($value, true));
        }

        try {
            return BloggerRole::from($value);
        } catch (ValueError $e) {
            throw new ConversionException('Invalid value for BloggerRole: '.var_export($value, true));
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof BloggerRole ? $value->value : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
