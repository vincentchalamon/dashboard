<?php

declare(strict_types=1);

namespace App\Twig;

use App\Model\Group;
use App\Repository\GroupRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class GroupExtension extends AbstractExtension
{
    public function __construct(private readonly GroupRepository $groupRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGroups', function (): iterable {
                return $this->groupRepository->findAll();
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('isDefault', static function (Group $group): bool {
                return 'Default' === $group->getName();
            }),
        ];
    }
}
