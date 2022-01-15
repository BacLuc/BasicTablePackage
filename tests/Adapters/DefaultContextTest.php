<?php

namespace BaclucC5Crud\Adapters;

use BaclucC5Crud\Test\Adapters\DefaultContext;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class DefaultContextTest extends TestCase {
    public const VAR_VALUE = 'test';

    public function testExportVariables() {
        $defaultContext = new DefaultContext();
        $defaultContext->set('test', self::VAR_VALUE);
        extract($defaultContext->getContext());

        // @noinspection PhpUndefinedVariableInspection
        $this->assertThat($test, $this->equalTo(self::VAR_VALUE));
    }
}
