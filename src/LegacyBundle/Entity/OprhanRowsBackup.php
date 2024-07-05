<?php

namespace LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orphan rows backup Table to store orphan rows of M2M rows with data integrity problems
 * created 20230207.
 *
 * @ORM\Table(name="orphan_rows_backup")
 *
 * @ORM\Entity
 */
class OprhanRowsBackup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="string", length=50, nullable=false)
     */
    private $tableName;

    /**
     * @var string
     *
     * @ORM\Column(name="left_col_name", type="string", length=50, nullable=false)
     */
    private $leftColName;

    /**
     * @var string
     *
     * @ORM\Column(name="orphan_col_name", type="string", length=50, nullable=false)
     */
    private $orphanColName;

    /**
     * @var int
     *
     * @ORM\Column(name="left_id", type="integer", nullable=false)
     */
    private $leftId;

    /**
     * @var int
     *
     * @ORM\Column(name="orphan_id", type="integer", nullable=false)
     */
    private $orphanId;

    /**
     * @var string
     *
     *  @ORM\Column(name="extra_row_data", type="text", nullable=true)
     */
    private $extraRowData;
}
