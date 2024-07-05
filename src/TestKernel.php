<?php

namespace App;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Exception;
use SebastianBergmann\CodeCoverage\Filter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TestKernel extends Kernel
{
    public const COVERAGE_CACHE_DIR = __DIR__.'/../.panther/coverage';
    public const COVERAGE_CACHE_EXT = 'cov';

    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): Response
    {
        if ('test' !== $this->environment || !extension_loaded('xdebug')) {
            return parent::handle($request, $type, $catch);
        }

        return $this->handleWithCoverage($request, $type, $catch);
    }

    private function handleWithCoverage(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): Response
    {
        $filter = new Filter();
        $filter->includeDirectory(realpath(__DIR__));

        try {
            $driver = (new Selector())->forLineCoverage($filter);
        } catch (Exception $e) {
            return parent::handle($request, $type, $catch);
        }

        $coverage = new CodeCoverage($driver, $filter);
        $id = md5(uniqid((string) mt_rand(), true));
        $coverage->start($id, true);
        $response = parent::handle($request, $type, $catch);
        $data = $coverage->stop();

        file_put_contents(
            sprintf('%s/%s.%s', realpath(self::COVERAGE_CACHE_DIR), $id, self::COVERAGE_CACHE_EXT),
            serialize($data->lineCoverage())
        );

        return $response;
    }
}
