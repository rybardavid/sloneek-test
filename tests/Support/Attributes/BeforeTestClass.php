<?php

namespace Tests\Support\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class BeforeTestClass
{}