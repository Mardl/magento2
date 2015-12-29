<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Model\Entity;

use Psr\Log\LoggerInterface;

/**
 * Class SequenceManager
 */
class SequenceManager
{
    /**
     * @var array
     */
    protected $registry;

    /**
     * @var SequenceRegistry
     */
    protected $sequenceRegistry;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param MetadataPool $metadataPool
     * @param SequenceRegistry $sequenceRegistry
     * @param LoggerInterface $logger
     */
    public function __construct(
        MetadataPool $metadataPool,
        SequenceRegistry $sequenceRegistry,
        LoggerInterface $logger
    ) {
        $this->metadataPool = $metadataPool;
        $this->sequenceRegistry = $sequenceRegistry;
        $this->logger = $logger;
    }

    /**
     * Force sequence value creation
     *
     * @param string $entityType
     * @param string|int $identifier
     * @return int
     * @throws \Exception
     */
    public function force($entityType, $identifier)
    {
        $metadata = $this->metadataPool->getMetadata($entityType);
        $sequenceInfo = $this->sequenceRegistry->retrieve($entityType);

        if (!isset($sequenceInfo['sequenceTable'])) {
            throw new \Exception('TODO: use correct Exception class');
        }
        try {
            return $metadata->getEntityConnection()->insert(
                $sequenceInfo['sequenceTable'],
                ['sequence_value' => $identifier]
            );
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
            throw new \Exception('TODO: use correct Exception class');
        }
    }
}
