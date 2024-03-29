<?php

declare(strict_types=1);

namespace App\Twig;

use App\Model\Run;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author Vincent Chalamon <vincentchalamon@gmail.com>
 */
final class RunExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('isSuccess', static function (Run $run): bool {
                return 'success' === $run->getState();
            }),
            new TwigFilter('isFailure', static function (Run $run): bool {
                return in_array($run->getState(), ['cancelled', 'failure', 'skipped'], true);
            }),
            new TwigFilter('isPending', static function (Run $run): bool {
                return !in_array($run->getState(), ['success', 'cancelled', 'failure', 'skipped'], true);
            }),
        ];
    }
}
