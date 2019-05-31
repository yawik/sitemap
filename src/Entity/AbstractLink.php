<?php
/**
 * YAWIK Sitemap
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Sitemap\Entity;

use DateTime;
use samdark\sitemap\Sitemap;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
abstract class AbstractLink
{
    /** @var DateTime */
    private $lastModified;
    /** @var string */
    private $changeFrequency;
    /** @var float */
    private $priority;

    public function setLastModified($lastModified): void
    {
        if (!$lastModified instanceof DateTime) {
            if (is_int($lastModified)) {
                $lastModified = '@' . $lastModified;
            }
            $lastModified = new DateTime($lastModified);
        }

        $this->lastModified = $lastModified;
    }

    public function getLastModified(): ?int
    {
        if (!$this->lastModified) {
            return null;
        }
        return $this->lastModified->getTimestamp();
    }

    public function setChangeFrequency(?string $frequency): void
    {
        if ($frequency === null) {
            $this->changeFrequency = null;
            return;
        }

        $valid = [
            Sitemap::ALWAYS, Sitemap::DAILY, Sitemap::HOURLY, Sitemap::MONTHLY,
            Sitemap::NEVER, Sitemap::WEEKLY, Sitemap::YEARLY,
        ];

        if (!in_array($frequency, $valid)) {
            throw new \UnexpectedValueException('Invalid Frequency. Must be one of: ' . join(', ', $valid));
        }

        $this->changeFrequency = $frequency;
    }

    public function getChangeFrequency(): ?string
    {
        return $this->changeFrequency;
    }

    public function setPriority(?float $priority): void
    {
        if ($priority === null) {
            $this->priority = null;
            return;
        }

        if ($priority < 0 || $priority > 1.0) {
            throw new \RangeException('Priority must be between 0 and 1.0');
        }

        $this->priority = $priority;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }
}
