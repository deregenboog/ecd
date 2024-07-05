<?php

namespace AppBundle\Export;

use AppBundle\Service\AbstractDao;
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
    protected $serviceId;

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

    /**
     * @var string
     */
    protected $friendlyName;

    /**
     * @var AbstractDao
     */
    protected $dao;

    public function __construct($class, array $configuration, $friendlyName = null, $dao = null)
    {
        $this->class = $class;
        $this->configuration = $configuration;
        $this->headers = $this->getHeaders($configuration);

        $this->friendlyName = $friendlyName;
        $this->dao = $dao;
    }

    public function create($entities): ExportInterface
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
                    throw new ExportException('Class %s only supports array\'s', self::class);
                }
            } elseif (!$entity instanceof $this->class) {
                throw new ExportException(sprintf('Class %s only supports %s (%s given)', self::class, $this->class, get_class($entity)));
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
                            $sheet->getCell([$column, $this->row])
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
                            break;
                        case 'date':
                            if (!$value instanceof \DateTime) {
                                $value = new \DateTime($value);
                            }
                            $value = @Date::PHPToExcel($value);
                            $sheet->getCell([$column, $this->row])
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
                            break;
                        case 'time':
                            if (!$value instanceof \DateTime) {
                                $value = new \DateTime($value);
                            }
                            $value = @Date::PHPToExcel($value);
                            $sheet->getCell([$column, $this->row])
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME3);
                            break;
                        case 'percentage':
                            $sheet->getCell([$column, $this->row])
                                ->setValue($value)
                                ->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                            break;
                        default:
                            if (is_iterable($value)) {
                                $values = [];
                                foreach ($value as $v) {
                                    $values[] = (string) $v;
                                }
                                $value = implode(', ', $values);
                            }
                            $sheet->getCell([$column, $this->row])
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
        if (null !== $this->friendlyName) {
            $sheet->setTitle($this->friendlyName);
        }

        $column = 1;
        foreach ($this->headers as $header) {
            $sheet->getCell([$column, 1])
                ->setValue($header)
                ->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
            ++$column;
        }

        return $sheet;
    }

    public function getSheet($idx = 0)
    {
        return $this->excel->getSheet($idx);
    }

    public function addSheet(Worksheet\Worksheet $sheet)
    {
        $this->excel->addSheet($sheet);
    }

    protected function getHeaders($configuration)
    {
        $headers = [];
        foreach (array_keys($configuration) as $header) {
            $headers[] = $header;
        }

        return $headers;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getFriendlyClassname()
    {
        if (!$this->friendlyName) {
            $path = explode('\\', $this->class);

            return array_pop($path);
        }

        return $this->friendlyName;
    }

    public function getHeadersAsArray()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getFriendlyName()
    {
        return $this->friendlyName;
    }

    /**
     * @return AbstractDao
     */
    public function getDao()
    {
        if (null == $this->dao) {
            throw new ExportException('Geen DAO ingesteld voor deze download');
        }

        return $this->dao;
    }
}
