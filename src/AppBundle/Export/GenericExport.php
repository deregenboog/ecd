<?php

namespace AppBundle\Export;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
        $sheet = $this->prepare();

        $language = new ExpressionLanguage();
        $language->register('empty', function ($array) {}, function ($arguments, $array) {
            return empty($array);
        });

        $column = 1;

        foreach ($entities as $entity) {
            if ('array' === $this->class) {
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
                        case 'money':
                            $sheet->getCellByColumnAndRow($column, $this->row)
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
                            break;
                        case 'date':
                            if (!$value instanceof \DateTime) {
                                $value = new \DateTime($value);
                            }
                            $value = @Date::PHPToExcel($value);
                            $sheet->getCellByColumnAndRow($column, $this->row)
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);
                            break;
                        case 'time':
                            if (!$value instanceof \DateTime) {
                                $value = new \DateTime($value);
                            }
                            $value = @Date::PHPToExcel($value);
                            $sheet->getCellByColumnAndRow($column, $this->row)
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);
                            break;
                        default:
                            if (is_array($value) || $value instanceof \Traversable) {
                                $values = [];
                                foreach ($value as $v) {
                                    $values[] = (string) $v;
                                }
                                $value = implode(', ', $values);
                            }
                            $sheet->getCellByColumnAndRow($column, $this->row)
                                ->setValue($value);
                            break;
                    }
                }

                ++$column;
            }

            $column = 1;
            ++$this->row;
        }

        return $this;
    }

    /**
     * @return Worksheet
     */
    protected function prepare()
    {
        $this->excel = new Spreadsheet();
        $sheet = $this->excel->getActiveSheet();

        $column = 1;
        foreach ($this->headers as $header) {
            $sheet->getCellByColumnAndRow($column, 1)
                ->setValue($header)
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
