<?php

namespace AppBundle\Export;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use AppBundle\Export\ExportException;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class GenericExport extends AbstractExport
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var int
     */
    protected $row = 2;

    public function __construct($class, array $configuration)
    {
        $this->class = $class;
        $this->configuration = $configuration;
        $this->headers = $this->getHeaders($configuration);
    }

    public function create($entities)
    {
        $language = new ExpressionLanguage();
        $language->register('empty', function($array) {}, function($arguments, $array) {
            return empty($array);
        });

        $sheet = $this->prepare();

        $column = 0;

        foreach ($entities as $entity) {

            if ($this->class === 'array') {
                if (!is_array($entity)) {
                    throw new ExportException('Class %s only supports array\'s', __CLASS__);
                }
            } elseif (!$entity instanceof $this->class) {
                throw new ExportException(sprintf('Class %s only supports %s (%s given)', __CLASS__, $this->class, get_class($entity)));
            }

            $params = ['entity' => $entity, 'export' => $this];

            foreach ($this->configuration as $header => $config) {
                if (isset($config['expression'])) {
                    if (isset($config['condition'])) {
                        if ($language->evaluate($config['condition'], $params)) {
                            $value = $language->evaluate($config['expression'], $params);
                        } else {
                            $value = null;
                        }
                    } else {
                        $value = $language->evaluate($config['expression'], $params);
                    }
                } else {
                    throw new ExportException(sprintf('Expression must be set for header "%s".', $header));
                }

                if (!is_null($value)) {
                    switch (@$config['type']) {
                        case 'date':
                            if (!$value instanceof \DateTime) {
                                $value = new \DateTime($value);
                            }
                            $value = \PHPExcel_Shared_Date::PHPToExcel($value);
                            $sheet->setCellValueByColumnAndRow($column, $this->row, $value);
                            $sheet->getCellByColumnAndRow($column, $this->row)->getStyle()->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                            break;
                        default:
                            $sheet->setCellValueByColumnAndRow($column, $this->row, $value);
                            break;
                    }
                }

                ++$column;
            }

            $column = 0;
            ++$this->row;
        }

        return $this;
    }

    /**
     * @return \PHPExcel_Worksheet
     */
    protected function prepare()
    {
        $this->excel = new \PHPExcel();
        $sheet = $this->excel->getActiveSheet();

        $column = 0;
        foreach ($this->headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header, true)
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
            ++$column;
        }

        return $sheet;
    }

    protected function getHeaders($configuration)
    {
        $headers = [];
        foreach (array_keys($configuration) as $header) {
            $headers[] = $header;
        }

        return $headers;
    }
}
