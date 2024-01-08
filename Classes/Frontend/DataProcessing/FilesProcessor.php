<?php
declare(strict_types=1);

namespace WapplerSystems\WsT3bootstrap\Frontend\DataProcessing;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FilesProcessor extends \TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
{


    protected $contentDataProcessor;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
    }

    /**
     * Process data of a record to resolve File objects to the view
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'files');

        if (isset($processorConfiguration['dataProcessing.'])) {
            $processedFiles = [];

            $files = $processedData[$targetVariableName] ?? [];
            /** @var FileReference $file */
            foreach ($files as $file) {

                $fileProperties = $file->getReferenceProperties();

                switch ($processorConfiguration['dataProcessing.']['recordTable'] ?? '') {
                    case 'sys_file':
                        $record = $file->getOriginalFile()->getProperties();
                        $table = 'sys_file';
                        break;
                    case 'sys_file_metadata':
                        $record = $file->getOriginalFile()->getMetaData()->get();
                        $table = 'sys_file_metadata';
                        break;
                    default:
                        $record = $file->getReferenceProperties();
                        $table = 'sys_file_reference';
                        break;
                }

                $processedRecordVariables = [];

                $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
                $recordContentObjectRenderer->start($record, $table, $cObj->getRequest());
                $processedRecordVariables = $this->contentDataProcessor->process($recordContentObjectRenderer, $processorConfiguration, $processedRecordVariables);

                $processedFiles[] = new FileReference(array_merge($fileProperties, $processedRecordVariables));
            }

            $processedData[$targetVariableName] = $processedFiles;
        }

        return $processedData;
    }

}
