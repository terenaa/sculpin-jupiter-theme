<?php
/**
 * This file is part of Sculpin Snappy Bundle.
 *
 * (c) Eternal Apps
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EternalApps\Sculpin\Bundle\SnappyBundle;

use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\FileSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Pdf.
 *
 * @author Krzysztof Janda <k.janda@eternalapps.pl>
 */
final class Pdf implements EventSubscriberInterface
{
    /**
     * @var \Knp\Snappy\Pdf
     */
    private $snappy;

    /**
     * @var string
     */
    private $outputDir;

    /**
     * Pdf constructor.
     *
     * @param string $binary
     * @param array $options
     * @param string $env
     */
    public function __construct($binary, array $options = [], $env)
    {
        $this->snappy = new \Knp\Snappy\Pdf($binary, $this->fixOptions($options));
        $this->outputDir = __DIR__ . '/../output_' . $env . '/';
    }

    /**
     * @param SourceSetEvent $sourceSetEvent
     */
    public function afterRun(SourceSetEvent $sourceSetEvent)
    {
        /** @var FileSource $source */
        foreach ($sourceSetEvent->updatedSources() as $source) {
            if ('index.html.twig' !== $source->file()->getFilename()) {
                continue;
            }

            if (file_exists($this->outputDir . 'index.html')) {
                $this->snappy->generate($this->outputDir . 'index.html', $this->outputDir . 'files/resume.pdf', [], true);
            }
        }
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function fixOptions(array $options)
    {
        $fixedOptions = [];

        foreach ($options as $key => $value) {
            $fixedKey = str_replace('_', '-', $key);
            $fixedOptions[$fixedKey] = $value;
        }

        return $fixedOptions;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Sculpin::EVENT_AFTER_RUN => 'afterRun',
        ];
    }
}
