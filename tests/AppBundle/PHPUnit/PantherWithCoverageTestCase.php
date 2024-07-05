<?php

declare(strict_types=1);

namespace Tests\AppBundle\PHPUnit;

use App\TestKernel;
use PHPUnit\Framework\TestResult;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Exception;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\RawCodeCoverageData;
use SplFileInfo;
use Symfony\Component\Panther\PantherTestCase;

class PantherWithCoverageTestCase extends PantherTestCase
{
	public function run(TestResult $result = null): TestResult
	{
		$result = parent::run($result);

		if (!$this->isCoverageEnabled()) {
			return $result;
		}

		foreach (new \DirectoryIterator(realpath(TestKernel::COVERAGE_CACHE_DIR)) as $file) {
			/** @var SplFileInfo $file */
			if ($file->getExtension() === TestKernel::COVERAGE_CACHE_EXT) {
				$data = $this->processCodeCoverageFile($file);
				$result->getCodeCoverage()->append($data, $this);
			}
		}

		return $result;
	}

	private function isCoverageEnabled(): bool
	{
		try {
			(new Selector)->forLineCoverage(new Filter());
			return true;
        } catch (Exception $e) {
			return false;
		}
	}

	private function processCodeCoverageFile(SplFileInfo $file): RawCodeCoverageData
	{
		$content = file_get_contents($file->getRealPath());
		$content = unserialize($content);
		$data = RawCodeCoverageData::fromXdebugWithoutPathCoverage($content);
		unlink($file->getRealPath());

		return $data;
	}
}
